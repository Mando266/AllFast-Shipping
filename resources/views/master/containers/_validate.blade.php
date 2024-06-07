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

        /**
         * Check if the container has a valid container code
         *
         * @return boolean
         */
        this.isValid = function (containerNumber) {
            valid = this.validate(containerNumber);
            if (this.empty(this.errorMessages)) {
                return true;
            }
            return false;
        }

        this.validate = function (containerNumber) {
            matches = [];

            if (!this.empty(containerNumber) && this.is_string(containerNumber)) {
                matches = this.identify(containerNumber);

                if (this.count(matches) !== 5) {
                    this.errorMessages.push('The container number is invalid');
                } else {
                    checkDigit = this.buildCheckDigit(matches);

                    if (this.checkDigit != checkDigit) {
                        this.errorMessages.push('The check digit does not match');
                        matches = [];
                    }
                }
            } else {
                this.errorMessages = {0: 'The container number must be a string'};
            }
            return matches;
        }

        this.getErrorMessages = function () {
            return this.errorMessages;
        }

        this.getOwnerCode = function () {
            if (this.empty(this.ownerCode)) {
                this.errorMessages.push('You must call validate or isValid first');
            }
            return this.ownerCode;
        }

        this.getProductGroupCode = function () {
            if (this.empty(this.productGroupCode)) {
                this.errorMessages.push('You must call validate or isValid first');
            }
            return this.productGroupCode;
        }

        this.getRegistrationDigit = function () {
            if (this.empty(this.registrationDigit)) {
                this.errorMessages.push('You must call validate or isValid first');
            }
            return this.registrationDigit;
        }

        this.getCheckDigit = function () {
            if (this.empty(this.checkDigit)) {
                this.errorMessages.push('You must call validate or isValid first');
            }
            return this.checkDigit;
        }

        this.generate = function (ownerCode, productGroupCode, from, to) {
            //SHS set default values for params
            from = typeof from !== 'undefined' ? from : 0;
            to = typeof to !== 'undefined' ? to : 999999;

            alphabetCode = this.strtoupper(ownerCode + productGroupCode);
            containers_no = [];

            if (this.is_string(alphabetCode) && this.strlen(ownerCode) === 3 && this.strlen(productGroupCode) === 1) {
                containers_no = [];
                current_container_no = '';
                current_container_check_digit = '';

                if ((from >= 0) && (to < 1000000) && ((to - from) > 0)) {
                    for (var i = from; i <= to; i++) {
                        current_container_no = alphabetCode + this.str_pad(i, 6, '0', STR_PAD_LEFT);
                        current_container_check_digit = this.createCheckDigit(current_container_no);

                        if (current_container_check_digit < 0) {
                            this.errorMessages.push('Error generating container number at number ' + i);
                            return containers_no;
                        }

                        containers_no[i] = current_container_no + current_container_check_digit;
                    }
                } else {
                    this.errorMessages.push('Invalid number to generate, minimal is 0 and maximal is 999999');
                }

            } else {
                this.errorMessages.push('Invalid owner code or product group code');
            }

            return containers_no;
        }

        this.createCheckDigit = function (containerNumber) {
            checkDigit = -1;
            if (!this.empty(containerNumber) && this.is_string(containerNumber)) {
                matches = this.identify(containerNumber, true);

                if (this.count(matches) !== 4 || (matches[4])) {
                    this.errorMessages.push('Invalid container number');
                } else {
                    checkDigit = this.buildCheckDigit(matches);
                    if (checkDigit < 0) {
                        this.errorMessages.push('Invalid container number');
                    }
                }
            } else {
                this.errorMessages.push('Container number must be a string');
            }
            return checkDigit;
        }

        this.clearErrors = function () {
            this.errorMessages = [];
        }

        this.buildCheckDigit = function (matches) {

            if ((matches[1])) {
                this.ownerCode = this.str_split(matches[1]);
            }
            if ((matches[2])) {
                this.productGroupCode = matches[2];
            }
            if ((matches[3])) {
                this.registrationDigit = this.str_split(matches[3]);
            }
            if ((matches[4])) {
                this.checkDigit = matches[4];
            }

            // convert owner code + product group code to its numerical value
            numericalOwnerCode = [];
            for (var i = 0; i < this.count(this.ownerCode); i++) {
                numericalOwnerCode[i] = this.alphabetNumerical[this.ownerCode[i]];
            }
            numericalOwnerCode.push(this.alphabetNumerical[this.productGroupCode]);

            // merge numerical owner code with registration digit
            numericalCode = this.array_merge(numericalOwnerCode, this.registrationDigit);
            sumDigit = 0;

            // check six-digit registration number and last check digit
            for (var i = 0; i < this.count(numericalCode); i++) {
                sumDigit += numericalCode[i] * Math.pow(2, i);
            }

            sumDigitDiff = Math.floor(sumDigit / 11) * 11;
            checkDigit = sumDigit - sumDigitDiff;
            return (checkDigit == 10) ? 0 : checkDigit;
        }

        this.identify = function (containerNumber, withoutCheckDigit) {

            withoutCheckDigit = typeof withoutCheckDigit !== 'undefined' ? withoutCheckDigit : false;

            this.clearErrors();

            if (withoutCheckDigit) {
                matches = this.preg_match(this.patternWithoutCheckDigit, this.strtoupper(containerNumber));
            } else {
                matches = this.preg_match(this.pattern, this.strtoupper(containerNumber));
            }
            return matches;
        }


        this.is_string = function (param) {
            return typeof param == 'string' ? true : false;
        }

        this.preg_match = function (pattern, string) {
            var regex = new RegExp(pattern);
            return regex.exec(string);
        }

        this.strtoupper = function (string) {
            return string.toUpperCase();
        }

        this.count = function (array) {
            if (array == null) {
                return 0;
            } else {
                return array.length;
            }
        }

        this.strlen = function (string) {
            return string.length;
        }


        this.str_split = function (string, split_length) {

            if (split_length == null) {
                split_length = 1;
            }
            if (string == null || split_length < 1) {
                return false;
            }
            string += '';
            var chunks = [],
                pos = 0,
                len = string.length;
            while (pos < len) {
                chunks.push(string.slice(pos, pos += split_length));
            }

            return chunks;
        }

        this.str_pad = function (input, pad_length, pad_string, pad_type) {

            var half = '',
                pad_to_go;

            var str_pad_repeater = function (s, len) {
                var collect = '',
                    i;

                while (collect.length < len) {
                    collect += s;
                }
                collect = collect.substr(0, len);

                return collect;
            };

            input += '';
            pad_string = pad_string !== undefined ? pad_string : ' ';

            if (pad_type !== 'STR_PAD_LEFT' && pad_type !== 'STR_PAD_RIGHT' && pad_type !== 'STR_PAD_BOTH') {
                pad_type = 'STR_PAD_RIGHT';
            }
            if ((pad_to_go = pad_length - input.length) > 0) {
                if (pad_type === 'STR_PAD_LEFT') {
                    input = str_pad_repeater(pad_string, pad_to_go) + input;
                } else if (pad_type === 'STR_PAD_RIGHT') {
                    input = input + str_pad_repeater(pad_string, pad_to_go);
                } else if (pad_type === 'STR_PAD_BOTH') {
                    half = str_pad_repeater(pad_string, Math.ceil(pad_to_go / 2));
                    input = half + input + half;
                    input = input.substr(0, pad_length);
                }
            }

            return input;
        }

        this.array_merge = function () {

            var args = Array.prototype.slice.call(arguments),
                argl = args.length,
                arg,
                retObj = {},
                k = '',
                argil = 0,
                j = 0,
                i = 0,
                ct = 0,
                toStr = Object.prototype.toString,
                retArr = true;

            for (i = 0; i < argl; i++) {
                if (toStr.call(args[i]) !== '[object Array]') {
                    retArr = false;
                    break;
                }
            }

            if (retArr) {
                retArr = [];
                for (i = 0; i < argl; i++) {
                    retArr = retArr.concat(args[i]);
                }
                return retArr;
            }

            for (i = 0, ct = 0; i < argl; i++) {
                arg = args[i];
                if (toStr.call(arg) === '[object Array]') {
                    for (j = 0, argil = arg.length; j < argil; j++) {
                        retObj[ct++] = arg[j];
                    }
                } else {
                    for (k in arg) {
                        if (arg.hasOwnProperty(k)) {
                            if (parseInt(k, 10) + '' === k) {
                                retObj[ct++] = arg[k];
                            } else {
                                retObj[k] = arg[k];
                            }
                        }
                    }
                }
            }
            return retObj;
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

    validator = new ContainerValidator();
    let codeInput = $("#codeInput")
    let form = codeInput.closest('form')
    codeInput.on('input', e => {
        let result = validator.isValid(e.target.value)
        if (result) {
            $("#codeInput").css({
                "border-color": "#00de28",
                "border-width": "2px",
                "border-style": "solid"
            }).addClass("valid").removeClass("invalid")
            form.off("submit").on("submit", function(event) {
                event.returnValue = true
            })
        } else {
            $("#codeInput").css({
                "border-color": "#ff0015",
                "border-width": "2px",
                "border-style": "solid"
            }).addClass("invalid").removeClass("valid")
            form.on("submit",function (s) {
                s.preventDefault()
            })
        }
    });
</script>
