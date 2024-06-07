@extends('layouts.app')
@section('content')
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-one">
                    <div class="widget-heading">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a a href="">Storage</a></li>
                                <li class="breadcrumb-item active"><a href="javascript:void(0);">Storage Calculation</a>
                                </li>
                                <li class="breadcrumb-item"></li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Display the Cart -->

                    <div class="widget-content widget-content-area">
                        @if(isset($error))
                            <div class="error-message">
                                {{ $error }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="error-message">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="validation-errors">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{route('storage.store')}}" method="POST">
                            @csrf

                            @php
                                $calculation = session('calculation');
                                $input = session('input');
                                // dd(isset($input) ? $input['bl_no'] : '');
                            @endphp
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>BL NO</label>
                                    <select class="selectpicker form-control" id="blno" data-live-search="true"
                                            name="bl_no" data-size="10"
                                            title="{{trans('forms.select')}}" required>
                                        @foreach($movementsBlNo as $item)
                                            <option
                                                    value="{{$item->id}}" {{$item->id == old('bl_no',isset($input) ? $input['bl_no'] : '') ? 'selected':''}}>{{$item->ref_no}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="Date">Container No</label>
                                    <select class="selectpicker form-control" id="port" data-live-search="true"
                                            name="container_code[]" data-size="10"
                                            title="{{trans('forms.select')}}" required multiple>
                                        <option
                                                value="all" {{ "all" == old('container_code',isset($input) ? $input['container_code'] : '') ? 'selected':'hidden'}}>
                                            All
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="Date">Services</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="service"
                                            data-size="10" id="service"
                                            title="{{trans('forms.select')}}" required>
                                        @foreach($services as $service)
                                            <option value="{{$service->id}}" {{$service->id == old('service',isset($input)? $input['service'] : '') ? 'selected':''}}>{{$service->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="countryInput">Select Triff</label>
                                    <select class="selectpicker form-control" id="triff_id" data-live-search="true"
                                            name="Triff_id" data-size="10"
                                            title="{{trans('forms.select')}}" required>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>From</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="from"
                                            data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($movementsCode as $item)
                                            <option
                                                    value="{{$item->id}}" {{$item->id == old('from',isset($input) ? $input['from'] : '') ? 'selected' : ''}}>{{$item->code}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('from'))
                                        <span class="text-danger">{{ $errors->first('from') }}</span>
                                    @endif
                                </div>
                                <div class="form-group col-md-4">
                                    <label>To</label>
                                    <select class="selectpicker form-control" data-live-search="true" name="to"
                                            data-size="10"
                                            title="{{trans('forms.select')}}">
                                        @foreach ($movementsCode as $item)
                                            <option
                                                    value="{{$item->id}}" {{$item->id == old('to',isset($input) ? $input['to'] : '') ? 'selected' : ''}}>{{$item->code}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('to'))
                                        <span class="text-danger">{{ $errors->first('to') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-row">
                            <div class="form-group col-md-4">
                                    <label>From Date</label>
                                    <input type="date" name="from_date" class="form-control"
                                           value="{{old('from_date',isset($input) ? $input['from_date'] : '')}}">
                                    @if ($errors->has('from_date'))
                                        <span class="text-danger">{{ $errors->first('from_date') }}</span>
                                    @endif
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Till Date</label>
                                    <input type="date" name="date" class="form-control"
                                           value="{{old('date',isset($input) ? $input['date'] : '')}}">
                                    @if ($errors->has('date'))
                                        <span class="text-danger">{{ $errors->first('date') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-info mt-3">Calculate</button>
                                </div>
                            </div>
                            @isset($calculation)
                                <h4 style="color:#1b55e2">Calculation
                                    <h4>
                                        <table id="charges" class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th class="col-md-2 text-center">Container No</th>
                                                <th class="col-md-8 text-center" colspan="4">Calculation Details</th>
                                                <th class="col-md-2 text-center">Total ({{$calculation['currency']}})
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($calculation['containers'] as $item)
                                                <tr>
                                                    <td class="col-md-2 text-center">{{$item['container_no']}} {{$item['container_type']}}</td>
                                                    <td class="col-md-2" style="border-right-style: hidden;">
                                                        From: {{$item['from']}} <br>
                                                        To: {{$item['to']}}
                                                    </td>
                                                    <td class="col-md-2" style="border-right-style: hidden;">
                                                        @foreach($item['periods'] as $period)
                                                            {{ $period['name'] }} <br>
                                                        @endforeach
                                                    </td>
                                                    <td class="col-md-2" style="border-right-style: hidden;">
                                                        @foreach($item['periods'] as $period)
                                                            {{ $period['days'] }} Days <br>
                                                        @endforeach
                                                    </td>
                                                    <td class="col-md-2">
                                                        @foreach($item['periods'] as $period)
                                                            {{ $period['total'] }} <br>
                                                        @endforeach
                                                    </td>
                                                    <td class="col-md-2 text-center">
                                                        {{$item['total']}}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td class="col-md-2" style="border-right-style: hidden;"></td>
                                                <td class="col-md-2" style="border-right-style: hidden;"></td>
                                                <td class="col-md-2" style="border-right-style: hidden;"></td>
                                                <td class="col-md-2" style="border-right-style: hidden;"></td>
                                                <td class="col-md-2"></td>
                                                <td class="col-md-2 text-center">
                                                    {{$calculation['grandTotal']}}
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>

                            @endisset
                        </form>
                        @if(isset($calculation))
                            <div class="layout-px-spacing">
                                <!-- ... Your existing HTML ... -->
                                <div class="row">


                                    <!-- Add a button to add the entire calculation to the cart -->
                                    <form id="add-to-cart-form">
                                        @csrf
                                        <input type="hidden" name="bl_no" value="{{$input['bl_no']}}">
                                        <input type="hidden" name="calculation_data"
                                               value="{{json_encode($calculation)}}">
                                        <button class="btn btn-primary" id="add-to-cart">Add to Cart</button>
                                    </form>
                                </div>

                                <div id="invoice-cart">
                                    <div class="cart-header">
                                        <h4>Invoice Cart</h4>
                                        <button type="button" class="btn btn-sm btn-danger" id="clear-cart">Clear Cart
                                        </button>
                                    </div>
                                    <ul id="cart-items" class="list-group">
                                    </ul>
                                </div>

                                <!-- <div class="button-container"> -->
                                <div class="form-row">
                                    <div class="col-md-2 text-center">
                                    </div>
                                    <div class="col-md-2 text-center">
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <form action="{{ route('preview.index') }}" method="post" id="preview-form">
                                            @csrf
                                            <input type="hidden" name="preview-data" id="preview-data">
                                            <button class="btn btn-success mt-3">Preview</button>

                                        </form>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <form action="{{ route('create-storage-invoice') }}" method="GET">
                                            <input type="hidden" name="calculation_data" id="create-invoice-data">
                                            <input type="hidden" name="bldraft_id" id="create-invoice-bl-no">
                                            <input type="hidden" name="amount" id="create-invoice-amount">
                                            <button class="btn btn-info mt-3">Create Invoice</button>
                                        </form>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <a class="btn btn-custom mt-3"
                                           href="{{ route('export.calculation') }}">Export</a>
                                    </div>
                                </div>

                                @endif

                            </div>
                    </div>
                </div>
            </div>
        </div>

        @endsection
        @push('styles')
            <style>
                .button-container {
                    display: flex;
                    flex-direction: column;
                    align-items: center; /* Center buttons horizontally */
                    gap: 10px; /* Adjust the space between the buttons */
                }

                .btn-custom {
                    background-color: #ffc107; /* Set the background color to yellow */
                    color: #000; /* Set the text color to black */
                    border: none; /* Remove button border */
                    padding: 10px 20px; /* Adjust padding as needed */
                    border-radius: 5px; /* Add rounded corners */
                    text-decoration: none; /* Remove underlines from links */
                    transition: background-color 0.3s; /* Add a smooth hover effect */
                }

                .btn-custom:hover {
                    background-color: #ff9800; /* Change the background color on hover */
                }

                /* Style the cart container */
                #invoice-cart {
                    border: 1px solid #ddd;
                    padding: 20px;
                    margin-top: 20px;
                    background-color: #f9f9f9;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                /* Style the cart header */
                .cart-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                /* Style the cart items list */
                #cart-items {
                    list-style-type: none;
                    padding: 0;
                }

                /* Style individual cart items */
                .cart-item {
                    border: 1px solid #ccc;
                    padding: 10px;
                    margin-bottom: 10px;
                    background-color: #fff;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                /* Style the Create Invoice button */
                #create-invoice {
                    margin-top: 20px;
                }

                /* Style the Add to Invoice button */
                #add-to-cart {
                    margin-bottom: 20px;
                }

                /* Style the Clear Cart button */
                #clear-cart {
                    margin-top: -10px;
                }

                /* Style the remove button */
                .remove-button {
                    background-color: #ff6961;
                    color: #fff;
                    border: none;
                    border-radius: 50%;
                    cursor: pointer;
                    font-size: 16px;
                    width: 30px;
                    height: 30px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin-left: 10px;
                }
            </style>
        @endpush
        @push('scripts')
            <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

            <script>
                let selectedCodes = '{{ implode(',',$input['container_code'] ??[]) }}'
                selectedCodes = selectedCodes.split(',')
                let selectedTriff = '{{ $input['Triff_id'] ?? '' }}'

                let service = $('#service');
                let company_id = "{{auth()->user()->company_id}}";

                $(document).ready(function () {
                    const getTriff = () => {
                        let value = service.val();
                        $.get(`/api/storage/triffs/${service.val()}/${company_id}`).then(function (data) {
                            let triffs = data.triffs || '';
                            let list2 = [];
                            for (let i = 0; i < triffs.length; i++) {
                                (triffs[i].id == selectedTriff) ?
                                    list2.push(`<option value="${triffs[i].id}" selected>${triffs[i].tariffTypeCode} ${triffs[i].portsCode} ${triffs[i].validfrom} ${triffs[i].validto} ${triffs[i].containersType}</option>`) :
                                    list2.push(`<option value="${triffs[i].id}">${triffs[i].tariffTypeCode}  ${triffs[i].portsCode} ${triffs[i].validfrom} ${triffs[i].validto} ${triffs[i].containersType}</option>`); 
                            }
                            let triff = $('#triff_id');
                            triff.html(list2.join(''));
                            $('.selectpicker').selectpicker('refresh');
                        });
                    }
                    if (service.val()) {
                        getTriff()
                    }

                    service.on('change', () => getTriff())

                    const getContainers = () => {
                        let bl = $('#blno');
                        $('#bldraft_id').val(bl.val());
                        let isSelected = "";
                        let company_id = "{{auth()->user()->company_id}}";

                        axios.get(`/api/bl/is-export/${bl.val()}`)
                            .then(response => {
                                const isExport = response.data.is_export;

                                $('#service option').each(function () {
                                    const text = $(this).text().toLowerCase();
                                    const hasExport = text.includes('export');

                                    if ((isExport && !hasExport) || (!isExport && hasExport)) {
                                        $(this).hide();
                                    } else {
                                        $(this).show();
                                    }
                                });
                            })
                            .catch(error => {
                                console.error('Error fetching data:', error);
                            });

                        let response = $.get(`/api/storage/bl/containers/${bl.val()}/${company_id}`).then(function (data) {
                            let containers = data.containers || '';
                            let list2 = [`<option value='all' ${selectedCodes[0] == 'all' ? 'selected' : ''}>All</option>`];
                            for (let i = 0; i < containers.length; i++) {
                                (selectedCodes.includes(containers[i].id.toString())) ?
                                    list2.push(`<option value='${containers[i].id}' selected> ${containers[i].code} </option>`) :
                                    list2.push(`<option value='${containers[i].id}'>${containers[i].code} </option>`)
                            }
                            let container = $('#port');
                            container.html(list2.join(''));
                            $('.selectpicker').selectpicker('refresh');
                        });
                    }
                    if ($('#blno').val()) {
                        getContainers()
                    }
                    document.getElementById('blno').addEventListener('change', getContainers)

                    $('#port').change(function () {
                        var selectedValue = $(this).val();
                        if (selectedValue.length > 1 && selectedValue.includes('all')) {
                            selectedValue = selectedValue.filter(function (value) {
                                return value !== 'all';
                            });
                            $(this).val(selectedValue);
                        }

                        if (selectedValue.includes('all')) {
                            $('#port option:not(:selected)').prop('disabled', true);
                        } else {
                            $('#port option').prop('disabled', false);
                        }
                        $('#port').selectpicker('refresh');
                    });
                });
            </script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
            <script>
                // Initialize the cart with data from localStorage or an empty array
                const cart = JSON.parse(localStorage.getItem('cart')) || [];

                $('#preview-form').submit(function (event) {
                    const storedItem = localStorage.getItem('cart');
                    if (storedItem) {
                        $('#preview-data').val(storedItem);
                    }
                });

                // function openPreview() {
                //     const cartData = JSON.parse(localStorage.getItem('cart')) || [];
                //     const cartDataParam = encodeURIComponent(JSON.stringify(cartData));
                //
                //     // Replace 'your-preview-url' with the actual URL for your preview page
                //     const previewUrl = `/preview`;
                //
                //     // Open a new window or popup
                //     const popup = window.open(previewUrl, 'Cart Preview', 'width=1280,height=900');
                //
                //     // Focus on the new window/popup (optional)
                //     if (popup) {
                //         popup.focus();
                //     }
                // }

                // Add click event listener to "Add to Invoice" button
                const addToCartButton = document.getElementById('add-to-cart');
                addToCartButton.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const form = document.getElementById('add-to-cart-form');
                    const selectedBl = document.getElementById('blno');
                    const bl_no = selectedBl.options[selectedBl.selectedIndex];
                    const blNo = bl_no.value;
                    const blNoText = bl_no.text;
                    const selectedtriff = document.getElementById('triff_id');
                    const triff = selectedtriff.options[selectedtriff.selectedIndex];
                    const triffValue = triff.value;
                    const triffText = triff.text;
                    const calculationData = JSON.parse(form.querySelector('input[name="calculation_data"]').value);

                    // Check if the same data already exists in the cart
                    // if (cart.some(item => item.blNo === blNo && item.triffValue === triffValue)) {
                    //     swal("Warning", "You cannot add the same data twice.", "warning");
                    //     return; // Don't proceed with adding to cart
                    // }

                    // Check if the blNo is different from existing cart items
                    if (cart.some(item => item.blNo !== blNo)) {
                        // BlNo is different, show confirmation dialog
                        swal({
                            title: "Warning",
                            text: "You are trying to add a different BL NO to the cart. Do you want to clear the cart and proceed?",
                            icon: "warning",
                            buttons: ["Back", "Clear Cart"],
                            dangerMode: true,
                        }).then(async (choice) => {
                            if (choice === null) {
                                // User clicked "Back," do nothing
                            } else if (choice) {
                                // User clicked "Clear Cart," clear the cart
                                let response = await clearCart();
                                if (response === 'clear') {
                                    updateCartDisplay();
                                    addToCart();
                                }
                            }
                        });
                    } else {
                        // BlNo is the same, proceed to add to cart
                        addToCart();
                    }

                    function addToCart() {
                        // Add the entire calculation to the cart
                        cart.push({blNo, blNoText, calculationData, triffValue, triffText});

                        // Update the cart display
                        updateCartDisplay();

                        // Save the cart data to localStorage
                        localStorage.setItem('cart', JSON.stringify(cart));
                    }
                });

                // Function to clear the entire cart
                function clearCart() {
                    return new Promise((resolve, reject) => {
                        swal({
                            title: "Clear Cart",
                            text: "Are you sure you want to clear the entire cart?",
                            icon: "warning",
                            buttons: ["Cancel", "Clear"],
                            dangerMode: true,
                        }).then((willClear) => {
                            if (willClear === null) {
                                resolve('cancel');
                            } else if (willClear) {
                                //success
                                cart.length = 0;
                                localStorage.removeItem('cart');
                                updateCartDisplay();
                                resolve('clear');
                            }
                        });
                    })
                }

                // ... Your existing JavaScript ...

                // Add click event listener to "Clear Cart" button
                const clearCartButton = document.getElementById('clear-cart');
                clearCartButton.addEventListener('click', clearCart);


                // Function to update the cart display
                // function updateCartDisplay() {
                //     const cartItems = document.getElementById('cart-items');
                //     cartItems.innerHTML = '';
                //
                //     cart.forEach(item => {
                //         const li = document.createElement('li');
                //         li.textContent = `BL NO: ${item.blNo}, Grand Total: ${item.calculationData.grandTotal} , Triff: ${item.triffText}`;
                //         cartItems.appendChild(li);
                //     });
                // }

                // Function to remove an item from the cart
                function removeFromCart(index) {
                    cart.splice(index, 1);
                    updateCartDisplay();
                    localStorage.setItem('cart', JSON.stringify(cart));
                }

                function updateCartDisplay() {
                    const cartItems = document.getElementById('cart-items');
                    cartItems.innerHTML = '';

                    cart.forEach((item, index) => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item cart-item';

                        // Create a container for cart item details and remove button
                        const itemDetails = document.createElement('div');
                        const containers = item.calculationData.containers.map(container => container.container_no).join(', ')
                        const text = `BL NO: ${item.blNoText}, Grand Total: ${item.calculationData.grandTotal}, Triff: ${item.triffText}, Containers: ${containers}`
                        itemDetails.innerHTML = `
                <div>${text}</div>
            `;

                        const createInvoiceData = $("#create-invoice-data")
                        const currentValue = createInvoiceData.val()
                        const updatedValue = currentValue + '_' + text;
                        createInvoiceData.val(updatedValue)
                        const createInvoiceBlNo = $("#create-invoice-bl-no")
                        createInvoiceBlNo.val(item.blNo)
                        const createInvoiceAmount = $("#create-invoice-amount")
                        createInvoiceAmount.val(item.calculationData.grandTotal)

                        // Create the remove button
                        const removeButton = document.createElement('button');
                        removeButton.className = 'remove-button';
                        removeButton.innerHTML = 'X';

                        // Add a click event listener to remove the item when the remove button is clicked
                        removeButton.addEventListener('click', () => {
                            removeFromCart(index);
                        });

                        // Append the item details and remove button to the cart item container
                        li.appendChild(itemDetails);
                        li.appendChild(removeButton);

                        // Append the cart item to the cart list
                        cartItems.appendChild(li);
                    });
                }

                // Add click event listener to "Create Invoice" button
                const createInvoiceButton = document.getElementById('create-invoice');
                createInvoiceButton.addEventListener('click', () => {
                    // Prepare the data to send with the form
                    const blNoForInvoice = document.getElementById('bl_no_for_invoice');
                    const cartDataForInvoice = document.getElementById('cart_data_for_invoice');

                    // Set the values of the hidden form fields
                    blNoForInvoice.value = cart[0].blNo; // Assuming there's only one BL NO in the cart
                    cartDataForInvoice.value = JSON.stringify(cart);

                    // Submit the form
                    document.getElementById('create-invoice-form').submit();

                    // Clear the cart and update the display
                    cart.length = 0;
                    localStorage.removeItem('cart');
                    updateCartDisplay();
                });

                // Load and display the cart data when the page loads
                window.addEventListener('load', () => {
                    updateCartDisplay();
                });


            </script>

    @endpush
