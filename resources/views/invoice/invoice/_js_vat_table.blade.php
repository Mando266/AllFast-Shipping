<script>
    document.addEventListener('DOMContentLoaded', function () {
        handleVatInput()
        calculateTotals()
        document.getElementById('vat').addEventListener('input', handleVatInput)
        document.querySelectorAll('.vatRadio').forEach(radio => {
            radio.addEventListener('change', handleVatInput);
        })
    })

    const handleVatInput = () => {
        const tableBody = document.querySelector('#charges tbody')
        const rows = tableBody.querySelectorAll('tr')
        rows.forEach((row, index) => handleRowVat(row, index));
        calculateTotals()
    }

    const handleRowVat = (row, index) => {
        const vatInput = document.getElementById('vat')
        const vatValue = parseFloat(vatInput.value)

        const usdInput = row.querySelector(`#usd_${index}`)
        const egpInput = row.querySelector(`#egp_${index}`)
        const usdValue = parseFloat(usdInput.value)
        const egpValue = parseFloat(egpInput.value)

        const addVatRadio = row.querySelector(`#item_${index}_enabled_yes`)
        let usdValueWithVat = usdValue
        let egpValueWithVat = egpValue
        if (addVatRadio && addVatRadio.checked) {
            usdValueWithVat = usdValue + (usdValue * (vatValue / 100))
            egpValueWithVat = egpValue + (egpValue * (vatValue / 100))
        }
        row.querySelector(`#usd_vat_${index}`).value = usdValueWithVat.toFixed(2)
        row.querySelector(`#egp_vat_${index}`).value = egpValueWithVat.toFixed(2)
    }

    function calculateTotals() {
        let totEgp = 0;
        let totUsd = 0;
        $('#charges tbody tr').each(function () {
            var egpAmount = $(this).find('input[name$="[egp_vat]"]').val();
            var usdAmount = $(this).find('input[name$="[usd_vat]"]').val();
            totEgp = totEgp + parseFloat(egpAmount);
            totUsd = totUsd + parseFloat(usdAmount);
        });
        var egpRoundedValue = Math.round(totEgp * 100) / 100;
        var usdRoundedValue = Math.round(totUsd * 100) / 100;
        $('input[id="total_egp"]').val(egpRoundedValue);
        $('input[id="total_usd"]').val(usdRoundedValue);
    }

    $("#add").click(function(){
        var counter = $('#charges tbody tr').length; // Count existing rows
        var tr = '<tr>' +
            '<td><input type="text" name="invoiceChargeDesc['+counter+'][charge_description]" class="form-control" autocomplete="off" placeholder="Charge Description"  required></td>' +

            // '<td><select class="selectpicker form-control" data-live-search="true" id="selectpickers" name="invoiceChargeDesc['+counter+'][charge_description]" data-size="10"><option>Select</option>@foreach ($charges as $item)<option value="{{$item->name}}">{{$item->name}}</option>@endforeach</select></td>' +
            '<td><input type="text" name="invoiceChargeDesc['+counter+'][size_small]" class="form-control" autocomplete="off" placeholder="Amount" value="0" required></td>' +
            '<td>' +
            '<div class="form-check">' +
            '<input class="form-check-input vatRadio" type="radio" name="invoiceChargeDesc['+counter+'][add_vat]" id="item_'+counter+'_enabled_yes" value="1">' +
            '<label class="form-check-label" for="item_'+counter+'_enabled_yes">Yes</label>' +
            '</div>' +
            '<div class="form-check">' +
            '<input class="form-check-input vatRadio" type="radio" name="invoiceChargeDesc['+counter+'][add_vat]" id="item_'+counter+'_enabled_no" value="0" checked>' +
            '<label class="form-check-label" for="item_'+counter+'_enabled_no">No</label>' +
            '</div>' +
            '</td>' +
            '<td>' +
            '<div class="form-check">' +
            '<input class="form-check-input" type="radio" name="invoiceChargeDesc['+counter+'][enabled]" id="item_'+counter+'_enabled_yes" value="1">' +
            '<label class="form-check-label" for="item_'+counter+'_enabled_yes">Yes</label>' +
            '</div>' +
            '<div class="form-check">' +
            '<input class="form-check-input" type="radio" name="invoiceChargeDesc['+counter+'][enabled]" id="item_'+counter+'_enabled_no" value="0" checked>' +
            '<label class="form-check-label" for="item_'+counter+'_enabled_no">No</label>' +
            '</div>' +
            '</td>' +
            '<td><input type="text" class="form-control" id="usd_'+counter+'" name="invoiceChargeDesc['+counter+'][total_amount]" placeholder="Total" value="0" autocomplete="off" style="background-color: white;" required disabled></td>' +
            '<td><input type="text" id="usd_vat_'+counter+'" name="invoiceChargeDesc['+counter+'][usd_vat]" class="form-control" autocomplete="off" placeholder="USD After VAT" value="0" disabled></td>' +
            '<td><input type="text" class="form-control" id="egp_'+counter+'" name="invoiceChargeDesc['+counter+'][total_egy]" placeholder="Egp Amount" value="0" autocomplete="off" style="background-color: white;" required disabled></td>' +
            '<td><input id="egp_vat_'+counter+'" type="text" name="invoiceChargeDesc['+counter+'][egp_vat]" class="form-control" autocomplete="off" placeholder="Egp After VAT" value="0" disabled></td>' +
            '<td style="width:85px;"><button type="button" class="btn btn-danger remove"><i class="fa fa-trash"></i></button></td>'+
            '</tr>';
            $('#charges tbody').append(tr);
            $('.selectpicker').selectpicker("render");
            $('#selectpickers').selectpicker();
            document.querySelectorAll('.vatRadio').forEach(radio => {
            radio.addEventListener('change', handleVatInput);
        })

    });
</script>