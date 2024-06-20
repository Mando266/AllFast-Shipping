<script type="text/javascript">
function ContainerValidator() {
    var STR_PAD_LEFT = 'STR_PAD_LEFT';
    this.alphabetNumerical = {
        'A': 10, 'B': 12, 'C': 13, 'D': 14, 'E': 15, 'F': 16, 'G': 17, 'H': 18, 'I': 19,
        'J': 20, 'K': 21, 'L': 23, 'M': 24, 'N': 25, 'O': 26, 'P': 27, 'Q': 28, 'R': 29,
        'S': 30, 'T': 31, 'U': 32, 'V': 34, 'W': 35, 'X': 36, 'Y': 37, 'Z': 38
    };
    this.pattern = /^([A-Z]{3})(U|J|Z)(\d{6})(\d)$/;
    this.patternWithoutCheckDigit = /^([A-Z]{3})(U|J|Z)(\d{6})$/;
    this.errorMessages = [];
    this.ownerCode = [];
    this.productGroupCode;
    this.registrationDigit = [];
    this.checkDigit;
    this.containerNumber;

    this.isValid = function (containerNumber) {
        var valid = this.validate(containerNumber);
        return this.empty(this.errorMessages);
    }

    this.validate = function (containerNumber) {
        var matches = [];
        this.clearErrors();
        if (!this.empty(containerNumber) && this.is_string(containerNumber)) {
            matches = this.identify(containerNumber);
            if (this.count(matches) !== 5) {
                this.errorMessages.push('The container number is invalid');
            } else {
                var checkDigit = this.buildCheckDigit(matches);
                if (this.checkDigit != checkDigit) {
                    this.errorMessages.push('The check digit does not match');
                    matches = [];
                }
            }
        } else {
            this.errorMessages.push('The container number must be a string');
        }
        return matches;
    }

    this.clearErrors = function () {
        this.errorMessages = [];
    }

    this.buildCheckDigit = function (matches) {
        this.ownerCode = this.str_split(matches[1]);
        this.productGroupCode = matches[2];
        this.registrationDigit = this.str_split(matches[3]);
        this.checkDigit = matches[4];

        var numericalOwnerCode = this.ownerCode.map(char => this.alphabetNumerical[char]);
        numericalOwnerCode.push(this.alphabetNumerical[this.productGroupCode]);
        var numericalCode = this.array_merge(numericalOwnerCode, this.registrationDigit.map(Number));
        var sumDigit = numericalCode.reduce((sum, digit, index) => sum + digit * Math.pow(2, index), 0);
        var sumDigitDiff = Math.floor(sumDigit / 11) * 11;
        var checkDigit = sumDigit - sumDigitDiff;
        return (checkDigit == 10) ? 0 : checkDigit;
    }

    this.identify = function (containerNumber, withoutCheckDigit = false) {
        this.clearErrors();
        var pattern = withoutCheckDigit ? this.patternWithoutCheckDigit : this.pattern;
        return this.preg_match(pattern, this.strtoupper(containerNumber));
    }

    this.is_string = function (param) {
        return typeof param == 'string';
    }

    this.preg_match = function (pattern, string) {
        var regex = new RegExp(pattern);
        return regex.exec(string);
    }

    this.strtoupper = function (string) {
        return string.toUpperCase();
    }

    this.count = function (array) {
        return array ? array.length : 0;
    }

    this.str_split = function (string, split_length = 1) {
        var chunks = [];
        for (var pos = 0; pos < string.length; pos += split_length) {
            chunks.push(string.slice(pos, pos + split_length));
        }
        return chunks;
    }

    this.str_pad = function (input, pad_length, pad_string, pad_type) {
        pad_string = pad_string || ' ';
        if (pad_type !== 'STR_PAD_LEFT' && pad_type !== 'STR_PAD_RIGHT' && pad_type !== 'STR_PAD_BOTH') {
            pad_type = 'STR_PAD_RIGHT';
        }
        if (pad_length <= input.length) return input;
        var pad_to_go = pad_length - input.length;
        if (pad_type === 'STR_PAD_LEFT') {
            return this.str_pad_repeater(pad_string, pad_to_go) + input;
        } else if (pad_type === 'STR_PAD_RIGHT') {
            return input + this.str_pad_repeater(pad_string, pad_to_go);
        } else if (pad_type === 'STR_PAD_BOTH') {
            var half = this.str_pad_repeater(pad_string, Math.ceil(pad_to_go / 2));
            input = half + input + half;
            return input.substr(0, pad_length);
        }
    }

    this.str_pad_repeater = function (s, len) {
        var collect = '';
        while (collect.length < len) {
            collect += s;
        }
        return collect.substr(0, len);
    }

    this.array_merge = function () {
        var retArr = [];
        for (var i = 0; i < arguments.length; i++) {
            retArr = retArr.concat(arguments[i]);
        }
        return retArr;
    }

    this.empty = function (mixed_var) {
        var undef, key, i, len;
        var emptyValues = [undef, null, false, 0, '', '0'];
        for (i = 0, len = emptyValues.length; i < len; i++) {
            if (mixed_var === emptyValues[i]) {
                return true;
            }
        }
        if (typeof mixed_var === 'object') {
            for (key in mixed_var) {
                return false;
            }
            return true;
        }
        return false;
    }
}

// Initialize the validator
let validator = new ContainerValidator();

$(document).ready(function() {
    var containerIndex = 1; // Initialize container index

    // Function to add new container row
    function addContainerRow() {
        var newRow = `
            <tr>
                <td>
                    <input type="text" style="width: 155px;" name="containerDetails[${containerIndex}][container_number]" class="form-control container-number" placeholder="Container No" autocomplete="off" required>
                    <input type="hidden" name="containerDetails[${containerIndex}][container_id]" class="container-id" >
                </td>
                <td class="container_type">
                    <select class="selectpicker form-control" name="containerDetails[${containerIndex}][container_type]" data-live-search="true" data-size="10" title="Select" required>
                        @foreach ($equipmentTypes as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="containerDetails[${containerIndex}][qty]" class="form-control qty" placeholder="QTY" value = '1' disabled required></td>
                <td class="ports">
                    <select class="selectpicker form-control" name="containerDetails[${containerIndex}][activity_location_id]" data-live-search="true" data-size="10" title="Select" required>
                        @foreach ($activityLocations as $location)
                            <option value="{{ $location->id }}">{{ $location->code }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="containerDetails[${containerIndex}][seal_no]" class="form-control" placeholder="Seal No"></td>
                <td><input type="text" name="containerDetails[${containerIndex}][haz]" class="form-control" placeholder="HAZ / REEFER/ OOG DETAILS / HAZ APPROVAL REF"></td>
                <td><input type="text" name="containerDetails[${containerIndex}][packs]" class="form-control" autocomplete="off" placeholder="Packs" required></td>
                <td><input type="text" name="containerDetails[${containerIndex}][pack_type]" class="form-control" autocomplete="off" placeholder="Packs Type" required></td>
                <td><input type="text" name="containerDetails[${containerIndex}][descripion]" class="form-control input" autocomplete="off" placeholder="Commodity Des"></td>  
                <td><input type="text" name="containerDetails[${containerIndex}][weight]" class="form-control" placeholder="Gross Weight" required></td>
                <td><input type="text" name="containerDetails[${containerIndex}][net_weight]" class="form-control" autocomplete="off" placeholder="Net Weight"></td>
                <td><button type="button" class="btn btn-danger removeRow"><i class="fa fa-trash"></i></button></td>
            </tr>`;
        $('#containerDetails tbody').append(newRow);
        $('.selectpicker').selectpicker('refresh');
        containerIndex++;
    }

    // Add new row on button click
    $('#addContainerRow').click(function() {
        addContainerRow();
    });

    // Remove row on button click
    $(document).on('click', '.removeRow', function() {
        $(this).closest('tr').remove();
    });

    // Use event delegation to handle validation for dynamic rows
    $(document).on('input', '.container-number', function(e) {
        let result = validator.isValid(e.target.value);
        let inputElement = $(this);
        if (result) {
            inputElement.css({
                "border-color": "#00de28",
                "border-width": "2px",
                "border-style": "solid"
            }).addClass("valid").removeClass("invalid");
            inputElement.closest('form').off("submit").on("submit", function(event) {
                event.returnValue = true;
            });
        } else {
            inputElement.css({
                "border-color": "#ff0015",
                "border-width": "2px",
                "border-style": "solid"
            }).addClass("invalid").removeClass("valid");
            inputElement.closest('form').on("submit", function(s) {
                s.preventDefault();
            });
        }
    });

    // Handle AJAX call to check container
    $(document).on('change', '.container-number', function() {
        var containerNumber = $(this).val();
        var row = $(this).closest('tr');
        $.ajax({
            url: '/booking/check-container',
            method: 'GET',
            data: { number: containerNumber },
            success: function(response) {
                if (response.exists) {
                    row.find('[name^="containerDetails"]').each(function() {
                        var name = $(this).attr('name');
                        if (name.includes('[container_type]')) $(this).val(response.type).selectpicker('refresh');
                    });
                    row.find('.container-id').val(response.id);
                } else {
                    $('#errorModalMessage').text('Container not found! Please enter the container type manually.');
                    $('#containerErrorModal').modal('show');
                    row.find('[name*="[container_type]"]').val('').selectpicker('refresh');
                    row.find('.container-id').val('');
                }
            },
            error: function(xhr) {
                console.error('An error occurred:', xhr);
            }
        });
    });

    // Handle form submission
    $('#bookingForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serializeArray();
        var containerDetails = [];
        $('#containerDetails tbody tr').each(function() {
            var containerNumber = $(this).find('.container-number').val();
            var containerId = $(this).find('.container-id').val();
            if (!containerId) {
                containerDetails.push({
                    container_number: containerNumber,
                });
            }
        });

        if (containerDetails.length > 0) {
            $.ajax({
                url: '/booking/create-container',
                method: 'POST',
                data: JSON.stringify({ containers: containerDetails }),
                contentType: 'application/json',
                success: function(response) {
                    if (response.success) {
                        response.containers.forEach(function(container) {
                            $('#containerDetails tbody tr').each(function() {
                                var row = $(this);
                                if (row.find('.container-number').val() === container.container_number) {
                                    row.find('.container-id').val(container.id);
                                }
                            });
                        });
                        $('#bookingForm').off('submit').submit();
                    } else {
                        alert('Error creating containers!');
                    }
                },
                error: function(xhr) {
                    console.error('An error occurred:', xhr);
                }
            });
        } else {
            $('#bookingForm').off('submit').submit();
        }
    });
});

</script>



