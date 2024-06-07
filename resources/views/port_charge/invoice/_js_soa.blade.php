<script>
    const appliedCostsClone = $('.dynamic-fields-clone').clone();
    var failedContainers = []
    var addedContainers = []

    $(document).ready(function () {
        $('#dynamic_fields').addClass('selectpicker').selectpicker('refresh');
        setupEventHandlers()
        switchTables()
        calculateTotals()
        hideCellsWithoutIncludedInput()
        handleDynamicFieldsChange()
        updateRowNumbers()
    })

    function setupEventHandlers() {
        $('#dynamic_fields').change(addOptionsToVoyageCosts);
        $('#voyage').change(handleVoyageChange);
        $(document).on('click', '.removeContact', handleRemoveRow)
        $("#add-row").on('click', handleAddRow)
        $(document).on('change', '.charge_type', handleChargeTypeChange)
        $(document).on('paste', '.container_no', handleContainerNoPaste)
        $(document).on('change', '.container_no', handleContainerNoChange)
        $('#vessel_id').on('change', loadVoyages)
        $(document).on('change', '#dynamic_fields', handleDynamicFieldsChange)
        $(document).on('input', '.dynamic-input', calculateTotals)
        $(document).on('change', '.pti-type', handlePtiTypeChange);
        $(document).on('change', '.power-days', handlePowerDaysChange);
        $(document).on('change', '.storage-days', handleStorageDaysChange);
        $(document).on('change', '.add-plan-select', handleAddPlanChange);
        $(document).on('change', '.quotation_type', () => $(".voyage-costs").trigger('change'));
        $(document).on('change', '.voyage-applied-costs select', handleVoyageCostsChange);
        $(document).on('click change keyup paste', () => calculateTotals());
        $(document).on('change', '#country', loadPorts)
        $(document).on('click', '.in-egp', handleInEgpCheck);
        $('#checkAll').change(function () {
            $('.in-egp').prop('checked', this.checked);
            calculateTotals()
        });
        $('form').on('submit', e => {
            setToZeroIfNull()
            deleteEmptyOnSubmit(e)
            addTypesToSelectedCosts(e)
            setEgpUsd()
        });
        $("#add-many-containers").on('click', handleAddContainers);
    }

    function handleInEgpCheck() {
        const target = $(this).data('target');
        const isChecked = $(this).is(':checked');
        const table = $(this).closest('table');

        const inputs = table.find('input[name*="' + target + '"]');

        inputs.each(function () {
            const inputName = $(this).attr('name');
            const newName = isChecked ? inputName.replace('usd', 'egp') : inputName.replace('egp', 'usd');
            $(this).attr('name', newName);
        });
        
    }

    function handleVoyageChange() {
        var selectedVoyages = $(this).val();
        $('.voyage-applied-costs').remove();

        selectedVoyages.forEach(function (voyageId) {
            var selectedOptions = $('#voyage option:selected');
            var desiredOption = selectedOptions.filter(function () {
                return $(this).val() === voyageId;
            });

            for (var i = 0; i < 2; i++) {
                var clonedDiv = appliedCostsClone.clone()
                    .removeClass('dynamic-fields-clone d-none')
                    .addClass('voyage-applied-costs');
                var select = clonedDiv.find('select')
                    .removeAttr('name')
                    .data('voyage-id', voyageId)
                    .prop('required', false)
                    .addClass('voyage-costs')
                    .addClass('selectpicker');

                if (i === 0) {
                    clonedDiv.find('label').text($(desiredOption[0]).data('name') + ' Full Costs');
                    clonedDiv.find('select').data('type', 'full');
                    select.attr('id', 'voyage_' + voyageId + '_applied_costs' + '_full')
                    select.attr('name', `voyage_costs[${voyageId}][full_costs][]`)
                    select.addClass(`voyage-${voyageId}-full`)
                } else {
                    clonedDiv.find('label').text($(desiredOption[0]).data('name') + ' Empty Costs');
                    clonedDiv.find('select').data('type', 'empty');
                    select.attr('id', 'voyage_' + voyageId + '_applied_costs' + '_empty')
                    select.attr('name', `voyage_costs[${voyageId}][empty_costs][]`)
                    select.addClass(`voyage-${voyageId}-empty`)
                }


                $('.dynamic-fields-clone').after(clonedDiv);
            }
        });

        $('.voyage-costs').trigger('change')
        addOptionsToVoyageCosts()
    }

    function handleVoyageCostsChange() {
        var voyageId = $(this).data('voyage-id');
        var voyageType = $(this).data('type');
        var voyageInputs = $('.voyage-' + voyageId);
        var voyageRows = voyageInputs.closest('tr');
        // console.log(voyageType)
        //filter the voyage rows by checking if full or empty
        voyageRows = voyageRows.filter((index, row) => row.querySelector('.quotation_type').value.toLowerCase() === voyageType.toLowerCase());
        // console.log(voyageRows)
        var selectedFields = $(this).val();

        voyageRows.find('.dynamic-input').removeClass('included');
        voyageRows.find('.dynamic-input').each(function () {
            var dynamicInput = $(this);
            let currentVal = dynamicInput.val()
            if (dynamicInput.val() != 0) {
                dynamicInput.data('old-value', currentVal)
                dynamicInput.val(0);
            }
        })

        selectedFields.forEach(function (selectedField) {
            var dynamicInput = voyageRows.find('.dynamic-input[data-field*="' + selectedField + '"]');
            voyageRows.find('.dynamic-input[data-field*="' + selectedField + '"]').each(function () {
                var dynamicInput = $(this);
                let oldVal = dynamicInput.data('old-value')
                dynamicInput.val(oldVal);
            })
            dynamicInput.addClass('included');
        });
    }

    function addOptionsToVoyageCosts() {
        const selectedOptions = $('#dynamic_fields').find('option:selected').clone();
        $('.voyage-applied-costs select').html(selectedOptions).val([]).trigger('change');
        $(".selectpicker").selectpicker('refresh');
    }


    function handleDynamicFieldsChange() {
        const selectedFields = $('#dynamic_fields').val();
        $('.dynamic-input').removeClass('included');

        selectedFields.forEach(selectedField => {
            $('.dynamic-input[data-field*="' + selectedField + '"]').addClass('included');
        });

        const options = $('#dynamic_fields option');

        options.each(function () {
            const optionValue = $(this).val();
            const formattedValue = optionValue.replace(/_/g, '-');
            const contains = selectedFields.some(field => field.includes(optionValue));
            $('[data-field="' + formattedValue + '"]').toggleClass('d-none', !contains);
        });
        hideCellsWithoutIncludedInput();
    }

    async function calculateTotals() {
        const exchangeRate = parseFloat($('#exchange_rate').val());
        let totalUSD = 0;
        let invoiceUSD = 0;
        let invoiceEGP = 0;
        let USD_to_EGP = 0;

        await new Promise((resolve) => {
            $('.included').each(function () {
                totalUSD += parseFloat($(this).val()) || 0;
            });
            resolve();
        });

        await new Promise((resolve) => {
            $('.included').each(function () {
                const table = $(this).closest('table');
                const field = $(this).data('field');
                let checkbox = table.find(`input[type="checkbox"].${field}`);
                if (checkbox.length === 0) {
                    checkbox = $("#checkAll")
                }

                if (checkbox.prop('checked')) {
                    USD_to_EGP += parseFloat($(this).val()) || 0;
                }
            });
            resolve();
        });

        invoiceUSD = totalUSD - USD_to_EGP;

        if (!isNaN(exchangeRate)) {
            invoiceEGP = USD_to_EGP * exchangeRate;
        }

        $("#total_usd").val(totalUSD);
        $("#invoice_usd").val(invoiceUSD);
        $("#invoice_egp").val(invoiceEGP);
    }

    const handleAddContainers = async e => {
        const vesselId = $('#vessel_id').val();
        const voyage = $('#voyage').val();
        if (vesselId === '' && voyage === '') {
            swal('Select Voyage First')
        } else {
            const success = await swal({
                content: {
                    element: "textarea",
                    attributes: {
                        placeholder: "Enter Containers Here",
                        id: "containers-auto"
                    },
                },
                buttons: ["no", "yes"]
            })

            if (success) {
                let containersText = document.getElementById("containers-auto").value
                $('tbody tr').filter((index, element) => !$('.container_no', element).val().trim()).remove();
                await autoAddContainers(containersText)
                if (failedContainers.length > 0) {
                    swal("Failed To Get Details For These Containers :" + failedContainers)
                    failedContainers = []
                }
                updateRowNumbers()
            }
        }
    }

    const autoAddContainers = async (containersText) => {
        const containers = Array.isArray(containersText)
            ? containersText
            : containersText.split('\n').map(containerNumber => containerNumber.trim()).filter(e => e !== "");

        const vesselId = $('#vessel_id').val();
        const voyage = $('#voyage').val();

        if (containers.length > 0 && vesselId && voyage) {
            const currentContainerInputs = document.querySelectorAll('.container_no');
            const existingContainers = Array.from(currentContainerInputs).map(input => input.value);

            const duplicates = [];

            for (const container of containers) {
                if (existingContainers.includes(container)) {
                    duplicates.push(container);
                } else {
                    await processContainer(container, vesselId, voyage);
                }
            }

            if (duplicates.length > 0) {
                swal(`Duplicate Containers: ${duplicates.join(', ')}`);
            }
        }
        checkForDuplicateContainers()
        handleDynamicFieldsChange()
        updateChargeTypeOptions("table1")
        updateChargeTypeOptions("table2")
        updateChargeTypeOptions("table3")
        updateChargeTypeOptions("table4")
    };

    function checkForDuplicateContainers() {
        const containerInputs = document.querySelectorAll('.container_no');
        const containerSet = new Set();
        const duplicateContainers = [];

        containerInputs.forEach(input => {
            const containerNumber = input.value.trim();
            if (containerNumber) {
                if (containerSet.has(containerNumber)) {
                    duplicateContainers.push(containerNumber);
                } else {
                    containerSet.add(containerNumber);
                }
            }
        });

        if (duplicateContainers.length > 0) {
            swal(`Duplicate Containers: ${duplicateContainers.join(', ')}`);
            return true;
        }
    }

    const processContainer = async (container, vesselId, voyage, service = null, ptiType = null, powerDay = null, storageDay = null, addPlan = null, additionalFee = null, additionalFeesDescription = null) => {
        return new Promise(async resolve => {
            try {
                const response = await axios.get('{{ route('port-charges.get-ref-no') }}', {
                    params: {
                        vessel: vesselId,
                        voyage: voyage,
                        container: container
                    }
                });

                const {ref_no, is_ts, shipment_type, quotation_type} = response.data;
                let table = '';
                let selectedCharge = null;

                if (is_ts == '1') {
                    table = 'table4';
                    if (quotation_type.toLowerCase() === 'full') {
                        selectedCharge = 5;
                    }
                    if (quotation_type.toLowerCase() === 'empty') {
                        selectedCharge = 6;
                    }
                } else if (quotation_type.toLowerCase() === 'full') {
                    table = 'table1';
                    selectedCharge = shipment_type.toLowerCase() === 'import' ? 1 : 2;
                } else if (quotation_type.toLowerCase() === 'empty' && shipment_type.toLowerCase() === 'export') {
                    table = 'table2';
                    selectedCharge = 4;
                } else if (quotation_type.toLowerCase() === 'empty' && shipment_type.toLowerCase() === 'import') {
                    table = 'table3';
                    selectedCharge = 3;
                }

                if (table !== '') {
                    const tbody = $(`#${table} tbody`);
                    appendSingleRow(container, tbody, selectedCharge, service, ptiType, powerDay, storageDay, addPlan, additionalFee, additionalFeesDescription);
                }
                resolve();
            } catch (error) {
                failedContainers.push(container);
                resolve();
            }
        });
    };

    function handleRemoveRow() {
        $(this).closest("tr").remove()
        calculateTotals()
        updateRowNumbers()
    }

    function handleAddRow() {
        const targetTable = $('table:not(.d-none)').first()
        const tableId = targetTable.attr('id')
        const newRow = getNewRow()

        targetTable.append(newRow)
        handleDynamicFieldsChange()
        updateChargeTypeOptions(tableId)
        updateRowNumbers()
    }

    function updateRowNumbers() {
        $('table').each(function (tableIndex, tableElement) {
            $(tableElement).find('tr').each(function (rowIndex, rowElement) {
                $(rowElement).find('td.row-number').text(rowIndex);
            });
        });
    }

    function handleChargeTypeChange() {
        const selectedOption = $(this).find('option:selected');
        const selectedValue = $(this).val();
        const row = $(this).closest('tr');
        const dynamicInputs = row.find('.dynamic-input');
        const containerInput = row.find('.container_no');
        const refNoInput = row.find('.ref-no-td');
        const ptiTypeSelect = row.find('.pti-type');
        const powerDaysSelect = row.find('.power-days');
        const storageDaysSelect = row.find('.storage-days');
        const addPlanSelect = row.find('.add-plan-select');
        const quotationType = row.find('.quotation_type');
        const emptyExportFromSelect = $('[name="empty_export_from_id"]');
        const emptyExportToSelect = $('[name="empty_export_to_id"]');
        const emptyImportFromSelect = $('[name="empty_import_from_id"]');
        const emptyImportToSelect = $('[name="empty_import_to_id"]');

        if (selectedValue && selectedValue !== "Select" && containerInput.val() && refNoInput.val()) {
            const requestData = {
                charge_type: selectedValue,
                container_no: containerInput.val(),
                bl_no: refNoInput.val(),
                quotation_type: quotationType.val(),
            };

            if (selectedOption.text() === 'EMPTY-EXPORT') {
                requestData.from = emptyExportFromSelect.find('option:selected').text();
                requestData.to = emptyExportToSelect.find('option:selected').text();
            } else if (selectedOption.text() === 'EMPTY-IMPORT') {
                requestData.from = emptyImportFromSelect.find('option:selected').text();
                requestData.to = emptyImportToSelect.find('option:selected').text();
            }


            axios.post('{{ route('port-charges.calculate-invoice-row') }}', requestData)
                .then(function (response) {
                    const dynamicFields = [
                        'thc', 'storage',
                        'power', 'shifting', 'disinf',
                        'hand_fes_em', 'gat_lift_off_inbnd_em_ft40',
                        'gat_lift_on_inbnd_em_ft40', 'add_plan','otbnd'
                    ]
                    dynamicFields.forEach(item => {
                        row.find(`[name*="[${item}]"]`).val(response.data[item]);
                    });
                    powerDaysSelect.find('option[value="normal"]').data('cost', response.data['power'])
                    powerDaysSelect.find('option[value="minus"]').data('cost', response.data['power_minus_one'])
                    storageDaysSelect.find('option[value="normal"]').data('cost', response.data['storage'])
                    storageDaysSelect.find('option[value="minus"]').data('cost', response.data['storage_minus_one'])
                    ptiTypeSelect.find('option[value="failed"]').data('cost', response.data['pti_failed']);
                    ptiTypeSelect.find('option[value="passed"]').data('cost', response.data['pti_passed'])
                    addPlanSelect.find('option[value="1"]').data('cost', response.data['add_plan'])
                    ptiTypeSelect.trigger('change')
                    addPlanSelect.trigger('change')
                    storageDaysSelect.trigger('change')
                    $(".voyage-costs").trigger('change')

                    calculateTotals();
                })
                .catch(function (error) {
                    console.error('Error:', error);
                });
        }
    }

    function handleAddPlanChange() {
        const addPlanCost = $(this).find(`option:selected`).data('cost');

        const row = $(this).closest('tr');
        const addPlanInput = row.find('input[name*="rows[add_plan][]"]')
        addPlanInput.val(addPlanCost);
    }

    function handlePowerDaysChange() {
        const powerCost = $(this).find(`option:selected`).data('cost');

        const row = $(this).closest('tr');
        const powerInput = row.find('input[name*="rows[power][]"]')
        powerInput.val(powerCost);
    }

    function handleStorageDaysChange() {
        const storageCost = $(this).find(`option:selected`).data('cost');

        const row = $(this).closest('tr');
        const storageInput = row.find('input[name*="rows[storage][]"]')
        storageInput.val(storageCost);
    }

    function addTypesToSelectedCosts(e) {
        const dynamicFieldsSelect = $('#dynamic_fields');
        if (dynamicFieldsSelect.find('option:selected[value="pti"]').length > 0) {
            dynamicFieldsSelect.append('<option value="pti_type" selected>PTI Type</option>');
        }
        if (dynamicFieldsSelect.find('option:selected[value="power"]').length > 0) {
            dynamicFieldsSelect.append('<option value="power_days" selected>power days</option>');
        }
        if (dynamicFieldsSelect.find('option:selected[value="storage"]').length > 0) {
            dynamicFieldsSelect.append('<option value="storage_days" selected>power days</option>');
        }
        if ($('.additional-cost').filter((index, input) => parseFloat($(input).val()) > 0).length > 0) {
            dynamicFieldsSelect.append('<option value="additional_fees" selected>additional_fees</option>');
        }

    }

    function setToZeroIfNull() {
        $('.dynamic-input').each(function () {
            if ($(this).val() === null || $(this).val() === '') {
                $(this).val('0');
            }
        });
    }

    function deleteEmptyOnSubmit(e) {
        $('form').find('tbody tr').each(function () {
            const containerNoInput = $(this).find('.container_no');
            const chargeTypeSelect = $(this).find('.charge_type');
            const blNoInput = $(this).find('.ref-no-td');

            const containerNoValue = containerNoInput[0].value;
            const selectedChargeType = chargeTypeSelect[0].value;
            const bl_no = blNoInput[0].value;

            if (containerNoValue === '') {
                $(this).remove()
            } else if (selectedChargeType === 'Select') {
                swal({
                    icon: 'error',
                    text: "Please Select Charge Type"
                })
                e.preventDefault()
            } else if (bl_no === "") {
                swal({
                    icon: 'error',
                    text: "No matching details for container " + containerNoValue
                })
                e.preventDefault()
            }
        });
        if ($('tbody tr').length === 0) {
            swal({
                icon: 'error',
                text: "Please Enter Rows Before Submitting"
            })
            e.preventDefault()
        }
        if (checkForDuplicateContainers()) {
            e.preventDefault()
        }
    }

    function handleContainerNoPaste(e) {
        e.preventDefault()

        const clipboardData = getClipboardData(e)
        const containerNumbers = getPastedContainerNumbers(clipboardData)

        const row = $(this).closest('tr')
        const selectedCharge = row.find('.charge_type')[0].value
        const selectedService = row.find('.service_type')[0].value
        const tableId = row.closest('table').attr('id')

        const tbody = row.closest('tbody')
        appendNewRows(containerNumbers, tbody, selectedCharge, selectedService)
        row.remove()
        handleDynamicFieldsChange()
        updateRowNumbers()
        updateChargeTypeOptions(tableId);
    }

    const getClipboardData = event => (event.originalEvent || event).clipboardData || window.clipboardData

    function getPastedContainerNumbers(clipboardData) {
        const pastedContent = clipboardData.getData('text/plain')
        return pastedContent.split('\n').map(containerNumber => containerNumber.trim())
    }

    function appendNewRows(containerNumbers, tbody, selectedCharge, selectedService) {
        containerNumbers.forEach(containerNumber => appendSingleRow(containerNumber, tbody, selectedCharge, selectedService))
    }

    function appendSingleRow(containerNumber, tbody, selectedCharge = null, selectedService = null, selectedPtiType = null, selectedPowerDay = null, selectedStorageDay = null, selectedAddPlan = null, additionalFees = null, additionalFeesDescription = null) {
        if (containerNumber !== '') {
            const newRow = getNewRow(containerNumber)
            tbody.append(newRow)
            setSelectsValue(newRow, selectedCharge, selectedService, selectedPtiType, selectedPowerDay, selectedStorageDay, selectedAddPlan)
            setAdditionalFees(newRow, additionalFees, additionalFeesDescription)
            newRow.find('.container_no').trigger('change')
            updateRowNumbers()
        }
    }

    function setAdditionalFees(row, additionalFees, additionalFeesDescription) {
        row.find('.additional-cost').val(additionalFees)
        row.find('.additional-description').val(additionalFeesDescription)
    }

    function setSelectsValue(newRow, selectedCharge, selectedService, selectedPtiType, selectedPowerDay, selectedStorageDay, selectedAddPlan) {
        const selectors = {
            '.charge_type': selectedCharge,
            '.service_type': selectedService,
            '.pti-type': selectedPtiType,
            '.power-days': selectedPowerDay,
            '.storage-days': selectedStorageDay,
            '.add-plan-select': selectedAddPlan,
        };

        for (const selector in selectors) {
            if (selectors[selector] !== null) {
                newRow.find(selector).val(selectors[selector]);
            }
        }
    }

    function setEgpUsd() {
        // $(".in-egp").trigger('click')
    }

    function handleContainerNoChange() {
        const containerNumber = $(this).val().trim();
        const vesselId = $('#vessel_id').val();
        const voyage = $('#voyage').val();

        if (containerNumber !== '' && vesselId !== '' && voyage !== '') {
            const row = $(this).closest('tr');
            const refNoCell = row.find('.ref-no-td')[0];
            const isTsCell = row.find('.is_transhipment')[0];
            const shipTypeCell = row.find('.shipment_type')[0];
            const quoteTypeCell = row.find('.quotation_type')[0];
            const containerTypeCell = row.find('.container-type')[0];
            const voyageCell = row.find('.voyage-name')[0];
            axios.get('{{ route('port-charges.get-ref-no') }}', {
                params: {
                    vessel: vesselId,
                    voyage: voyage,
                    container: containerNumber
                }
            }).then(response => {
                if (response.data.status === 'success') {
                    refNoCell.value = response.data.ref_no;
                    isTsCell.value = response.data.is_ts;
                    shipTypeCell.value = response.data.shipment_type;
                    quoteTypeCell.value = response.data.quotation_type;
                    containerTypeCell.value = response.data.container_type;
                    voyageCell.value = response.data.voyage_name;
                    voyageCell.classList.add('voyage-' + response.data.voyage_id)
                    row.find('.charge_type').trigger('change');
                }
            }).catch(() => {
                console.error('Could not find ref_no');
            });
        }
    }

    function handlePtiTypeChange() {
        const selectedPtiType = $(this).val();
        const ptiValue = $(this).find(`option:selected`).data('cost');

        const row = $(this).closest('tr');
        const ptiInput = row.find('input[data-field="pti_cost"]');
        ptiInput.val(ptiValue);
    }

    function loadVoyages() {
        return new Promise((resolve) => {
            const vessel = $('#vessel_id');
            const voyageSelect = $('#voyage');

            $.get(`/api/vessel/multi-voyages/${vessel.val()}`).then(data => {
                const voyages = data.voyages || [];
                const options = voyages.map(voyage => `<option value="${voyage.id}" data-name="${voyage.voyage_no} - ${voyage.leg ? voyage.leg.name : ''}"
                                                        >${voyage.vessel.name} - ${voyage.voyage_no} - ${voyage.leg ? voyage.leg.name : ''}</option>`);
                voyageSelect.html(options.join(''));
                voyageSelect.trigger('change');
                $('.selectpicker').selectpicker('refresh');

                resolve();
            });
        });
    }

    async function loadPorts() {
        try {
            const country = $('#country').val();
            const portsSelect = $('#ports');

            const {data} = await axios.get(`/api/master/ports/${country}`);

            const ports = data.ports || [];
            const options = ports.map(port => `<option value="${port.id}">${port.name}</option>`);

            portsSelect.html(options.join(''));
            $('.selectpicker').selectpicker('refresh');
        } catch (error) {
            console.error('An error occurred:', error);
        }
    }


    function switchTables() {
        const switchButtons = document.querySelectorAll('.switch-table')
        const tableContainer = document.querySelector('.table-container')
        const table2Selects = document.getElementById('table2-selects')
        const table3Selects = document.getElementById('table3-selects')

        switchButtons.forEach(button => {
            button.addEventListener('click', () => {
                switchButtons.forEach(btn => {
                    btn.classList.remove('active')
                })

                button.classList.add('active')
                const tableId = button.getAttribute('data-table')

                table2Selects.classList.toggle('d-none', tableId !== 'table2')
                table3Selects.classList.toggle('d-none', tableId !== 'table3')

                tableContainer.querySelectorAll('table').forEach(table => {
                    table.classList.add('d-none')
                })

                const selectedTable = document.getElementById(tableId)
                if (selectedTable) {
                    selectedTable.classList.remove('d-none')
                }
            })
        })
    }

    function getNewRow(containerNumber = '') {
        const newRow = $(`
                <tr>
                    <td style="width: 85px;">
                        <button type="button" class="btn btn-danger removeContact">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                    <td class="row-number"></td>
                    <td>
                        <select name="rows[port_charge_id][]" class="form-control charge_type new_charge" required>
                            <option hidden selected>Select</option>
                            @foreach($portCharges as $portCharge)
        <option value="{{ $portCharge->id }}">{{ $portCharge->name }}</option>
                            @endforeach
        </select>
    </td>
    <td>
        <select name="rows[service][]" class="form-control service_type" required>
            <option selected>Select</option>
            <option value="999990-HATCH-COVER-OPERATIONS">999990-HATCH-COVER-OPERATIONS</option>
            <option value="DISCHARGING-OPERATIONS">DISCHARGING-OPERATIONS</option>
            <option value="YARD-0060-AND-GATES-SERVICES">YARD-0060-AND-GATES-SERVICES</option>
            <option value="001-VSL-RE-STW-OPR">001-VSL-RE-STW-OPR</option>
            <option value="002-VSL-HATCH-CVR-OPR">002-VSL-HATCH-CVR-OPR</option>
            <option value="0021-LOADING-OPRERATION">0021-LOADING-OPRERATION</option>
            <option value="005-VSL-DIS-OPR">005-VSL-DIS-OPR</option>
            <option value="006-VSL-LOD-OPR">006-VSL-LOD-OPR</option>
            <option value="0061-SERVICE-OF-YARD-AND-GATES-TO-EXPORTS">0061-SERVICE-OF-YARD-AND-GATES-TO-EXPORTS</option>
            <option value="007-VSL-TRNSHP-OPR">007-VSL-TRNSHP-OPR</option>
            <option value="009-VSL-OPR-IMDG">009-VSL-OPR-IMDG</option>
            <option value="010-VSL-OPR-OOG">010-VSL-OPR-OOG</option>
            <option value="011-VSL-HOL-WRK">011-VSL-HOL-WRK</option>
            <option value="018-YARD-SERV">018-YARD-SERV</option>
            <option value="019-LOG-SERV">019-LOG-SERV</option>
            <option value="020-HAND-FES">020-HAND-FES</option>
            <option value="021-STRG-INBND-FL-CONTRS">021-STRG-INBND-FL-CONTRS</option>
            <option value="024-STRG-OUTBND-CONTRS-FL">024-STRG-OUTBND-CONTRS-FL</option>
            <option value="025-STRG-OUTBND-CONTRS-EM">025-STRG-OUTBND-CONTRS-EM</option>
            <option value="027-STRG-TRNSHP-CONTRS">027-STRG-TRNSHP-CONTRS</option>
            <option value="02821-POWER-SUPPLY-OF-REEFR-CONTAINER">02821-POWER-SUPPLY-OF-REEFR-CONTAINER</option>
            <option value="031-STRG-PR-DR-CONTRS">031-STRG-PR-DR-CONTRS</option>
            <option value="033-REFR-CONTR-PWR-SUP">033-REFR-CONTR-PWR-SUP</option>
            <option value="037-MISC-REV-GAT-SERV">037-MISC-REV-GAT-SERV</option>
            <option value="038-MISC-REV-YARD-CRN-SHIFTING">038-MISC-REV-YARD-CRN-SHIFTING</option>
            <option value="039-MISC-REV-GAT-SERV-LIFT OFF">039-MISC-REV-GAT-SERV-LIFT OFF</option>
            <option value="045-MISC-REV-ELEC-REP-SERV">045-MISC-REV-ELEC-REP-SERV</option>
            <option value="048-MISC-REV-OTBND-CONTRS-DR">048-MISC-REV-OTBND-CONTRS-DR</option>
            <option value="051-VSL-OPR-ADD-PLAN">051-VSL-OPR-ADD-PLAN</option>
            <option value="059-WAR-MARTYRS">059-WAR-MARTYRS</option>
            <option value="060-DISINFECTION-OF-CONTAINERS">060-DISINFECTION-OF-CONTAINERS</option>
            <option value="0991-HANDLING-FEES">0991-HANDLING-FEES</option>
            <option value="2981-ADMINISTRATIVE-EXPENSES">2981-ADMINISTRATIVE-EXPENSES</option>
            <option value="50-ELECTRONIC-REPORTS-SERVICE">50-ELECTRONIC-REPORTS-SERVICE</option>
        </select>
    </td>
    <td><input type="text" class="form-control voyage-name"></td>
    <td><input type="text" name="rows[bl_no][]" class="form-control ref-no-td"></td>
    <td><input type="text" name="rows[container_no][]" class="form-control container_no" value="${containerNumber}"></td>
                    <td><input type="text" class="form-control container-type"></td>
                    <td><input type="text" name="rows[is_transhipment][]" class="is_transhipment form-control"></td>
                    <td><input type="text" name="rows[shipment_type][]" class="shipment_type form-control"></td>
                    <td><input type="text" name="rows[quotation_type][]" class="quotation_type form-control"></td>
                    ${generateDynamicInputsHtml()}
                    <td><input type="number" name="rows[additional_fees][][usd]"
                       class="form-control additional-cost included"
                       step="0.01" placeholder="cost" data-field="additional_fees_cost">
                    </td>
                    <td><input placeholder="description" class="form-control additional-description" name="rows[additional_fees_description][]"
                               style="min-width: 200px">
                    </td>
                </tr>
            `);

        return newRow
    }

    const generateDynamicInputsHtml = () => {
        const dynamicFields = [
            'thc', 'storage', 'power',
            'shifting', 'disinf', 'hand_fes_em',
            'gat_lift_off_inbnd_em_ft40', 'gat_lift_on_inbnd_em_ft40',
            'otbnd','pti', 'add_plan'
        ];

        const fieldSelectOptions = {
            pti: `
                <select style="min-width: 100px" class="form-control pti-type" name="rows[pti_type][]">
                    <option value="passed" data-cost="0" selected>Passed</option>
                    <option value="failed" data-cost="0">Failed</option>
                </select>
                `,
            power: `
                <select style="min-width: 100px" class="form-control power-days" name="rows[power_days][]">
                    <option value="normal" data-cost="0" selected>Normal</option>
                    <option value="minus" data-cost="0">Minus One Day</option>
                </select>
                `,
            add_plan: `
                <select style="min-width: 100px" class="form-control add-plan-select">
                    <option value="1" data-cost="0" selected>Added</option>
                    <option value="0" data-cost="0">Not added</option>
                </select>
                `,
            storage: `
                <select style="min-width: 100px" class="form-control storage-days" name="rows[storage_days][]">
                    <option value="normal" data-cost="0" selected>Normal</option>
                    <option value="minus" data-cost="0">Minus One Day</option>
                </select>
                `
        };

        let dynamicInputsHtml = '';

        dynamicFields.forEach(field => {
            let td_dat_field = field.replace(/_/g, '-');
            const select = fieldSelectOptions[field] || '';
            const selectHtml = select ? `
                    <td data-field="${td_dat_field}">
                        ${select}
                    </td>
                ` : '';

            dynamicInputsHtml += `
                    <td data-field="${td_dat_field}">
                        <input type="text" name="rows[${field}][][usd]" class="form-control dynamic-input" data-field="${field}_cost">
                    </td>
                    ${selectHtml}
                `;
        });

        return dynamicInputsHtml;
    }

    function updateChargeTypeOptions(tableId) {
        const chargeTypeSelect = $(`#${tableId} .new_charge`)
        const options = chargeTypeSelect.find('option:not([hidden])')

        const allowedValues = {
            'table1': ['FULL-IMPORT', 'FULL-EXPORT'],
            'table2': ['EMPTY-EXPORT'],
            'table3': ['EMPTY-IMPORT'],
            'table4': ['TRANSHIP']
        }

        options.each((index, option) => {
            const optionValue = option.textContent

            if (!(allowedValues[tableId].some(allowed => optionValue.includes(allowed)))) {
                option.remove()
            }
        })
    }

    function hideCellsWithoutIncludedInput() {
        const firstVisibleTable = $('table:not(.d-none):first');
        firstVisibleTable.find('td:has(input.dynamic-input)').each(function () {
            const $input = $(this).find('input.dynamic-input');
            if (!$input.hasClass('included')) {
                $(this).addClass('d-none');
            } else {
                $(this).removeClass('d-none');
            }
        });
    }

</script>
