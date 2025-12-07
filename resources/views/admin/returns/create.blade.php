@extends('admin.layouts.app', [
    'pageName' => __('trans.returns'),
])

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@lang('trans.returns_create')</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.returns.store') }}" id="main-form">
                        @csrf
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="client_id">@lang('trans.client')</label>
                                    <select
                                        name="client_id"
                                        id="client_id"
                                        class="form-control select2 @error('client_id') is-invalid @enderror">
                                        <option value="">@lang('trans.choose')</option>
                                        @foreach($clients as $client)
                                            <option
                                                @if(old('client_id') == $client->id) selected @endif
                                                value="{{ $client->id }}"
                                            >{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="sale_date">@lang('trans.date')</label>
                                    <input
                                        type="text"
                                        class="form-control datepicker @error('sale_date') is-invalid @enderror"
                                        id="sale_date"
                                        placeholder="@lang('trans.date')"
                                        name="sale_date">
                                    @error('sale_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="return_number">@lang('trans.return_number')</label>
                                    <input
                                        type="text"
                                        class="form-control @error('invoice_number') is-invalid @enderror"
                                        id="invoice_number"
                                        placeholder="@lang('trans.invoice_number')"
                                        name="invoice_number">
                                    @error('invoice_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="safe_id">@lang('trans.safe')</label>
                                    <select
                                        name="safe_id"
                                        id="safe_id"
                                        class="form-control select2 @error('safe_id') is-invalid @enderror">
                                        @foreach($safes as $safe)
                                            <option value="{{ $safe->id }}">{{ $safe->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('safe_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="warehouse_id">@lang('trans.warehouse')</label>
                                    <select id="warehouse_id" name="warehouse_id" class="form-control @error('warehouse_id') is-invalid @enderror">
                                    <option value="">@lang('trans.choose_warehouse')</option>
                                    @foreach($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}"
                                        {{ (string) old('warehouse_id') === (string) $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="item_id">@lang('trans.item')</label>
                                    <select
                                        id="item_id"
                                        class="form-control select2">
                                        <option value="">@lang('trans.choose')</option>
                                        @foreach($items as $item)
                                            <option
                                                data-price="{{$item->price}}"
                                                data-quantity="{{$item->quantity}}"
                                                    value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="qty">@lang('trans.qty')</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="qty"
                                        placeholder="@lang('trans.qty')">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="notes">@lang('trans.notes')</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="notes"
                                        placeholder="@lang('trans.notes')">
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <button
                                    type="button"
                                    id="add_item"
                                    class="btn btn-primary mb-2 btn-block"
                                    style="margin-top: 32px">
                                    <i class="fa fa-plus-circle"></i>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 40px">#</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th style="width: 120px">Qnt</th>
                                    <th style="width: 100px">Total</th>
                                    <th>Notes</th>
                                    <th></th>
                                </tr>
                                <tbody id="items_list">
                                    @foreach((array)old('items') as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <span>{{ $item['name'] }}</span>
                                                <input type="hidden" name="items[{{ $item['id'] }}][id]" value="{{ $item['id'] }}">
                                                <input type="hidden" name="items[{{ $item['id'] }}][name]" value="{{ $item['name'] }}">
                                            </td>
                                            <td>
                                                {{ $item['price'] }}
                                                <input type="hidden" name="items[{{ $item['id'] }}][price]" value="{{ $item['price'] }}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="items[{{ $item['id'] }}][qty]" value="{{ $item['qty'] }}">
                                            </td>
                                            <td>
                                                {{ $item['itemTotal'] }}
                                                <input type="hidden"  name="items[{{ $item['id'] }}][itemTotal]" value="{{ $item['itemTotal'] }}">
                                            </td>
                                            <td>
                                                {{ $item['notes'] }}
                                                <input type="hidden" name="items[{{ $item['id'] }}][notes]" value="{{ $item['notes'] }}">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm deleteItem"><i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total</th>
                                    <th id="total_price">{{ collect(old('items'))->sum('itemTotal') }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Discount</th>
                                    <th id="discount"></th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Net</th>
                                    <th id="net"></th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Paid</th>
                                    <th id="paid"></th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="text-right">Remaining</th>
                                    <th id="remaining"></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="discount-box">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>@lang('trans.discount_type')</label>
                                    @foreach($discountTypes as $discountTypeVal => $discountType)
                                        <div class="form-check">
                                            <input class="form-check-input" id="discount{{$discountTypeVal}}" type="radio" name="discount_type"
                                                   value="{{ $discountTypeVal }}"
                                                   @if(old('discount_type') == $discountTypeVal || $loop->first) checked @endif>
                                            <label for="discount{{$discountTypeVal}}" class="form-check-label">{{ $discountType }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="notes">@lang('trans.discount_value')</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="discount_value"
                                            name="discount_value"
                                            value="{{ old('discount_value') }}"
                                            placeholder="@lang('trans.discount_value')">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="payment-type">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>@lang('trans.payment_type')</label>
                                    <div class="form-check">
                                        <input class="form-check-input" id="payment_type{{\App\Enums\PaymentTypeEnum::cash}}" type="radio" name="payment_type"
                                               value="{{\App\Enums\PaymentTypeEnum::cash}}"
                                               @if(old('payment_type') == \App\Enums\PaymentTypeEnum::cash->value || true) checked @endif>
                                        <label for="payment_type{{\App\Enums\PaymentTypeEnum::cash}}" class="form-check-label">{{ \App\Enums\PaymentTypeEnum::cash->label() }}</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" id="payment_type{{\App\Enums\PaymentTypeEnum::debt}}" type="radio" name="payment_type"
                                               value="{{\App\Enums\PaymentTypeEnum::debt}}"
                                               @if(old('payment_type') == \App\Enums\PaymentTypeEnum::debt->value) checked @endif>
                                        <label for="payment_type{{\App\Enums\PaymentTypeEnum::debt}}" class="form-check-label">{{ \App\Enums\PaymentTypeEnum::debt->label() }}</label>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="notes">@lang('trans.payment_amount')</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="payment_amount"
                                            name="payment_amount"
                                            value="{{ old('payment_amount') }}"
                                            @if(!old('payment_amount')) disabled @endif
                                            placeholder="@lang('trans.payment_amount')">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <x-form-submit text="Create"></x-form-submit>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection


@push('js')
    <script>
        $(document).ready(function (){
            calculateDiscount();
        });

        var counter = 1
        var totalPrice = parseFloat("{{ collect(old('items'))->sum('itemTotal') ?? 0 }}");
        var net = 0;
        $("#add_item").on('click', function (e) {
            e.preventDefault();
            let item = $("#item_id");
            let itemID = item.val();
            let selectedItem = $("#item_id option:selected");
            let itemName = selectedItem.text()
            let itemPrice = selectedItem.data('price');
            let qnt = $("#qty")
            var itemQty = qnt.val();
            let notes = $("#notes")
            let itemNotes = notes.val();
            let itemTotal = itemPrice * itemQty;

            // validate inputs : item chosen , qnt , qnt > 0 , qnt <= available qnt
            if (!itemID) {
                // sweelalet error
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please choose an item',
                })
                return;
            }
            // if (!itemQty || itemQty <= 0 || itemQty > selectedItem.data('quantity')) {
            //     // sweelalet error
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Error',
            //         text: 'Please enter a valid quantity',
            //     })
            //     return;
            // }

            $("#items_list").append('' +
                '<tr>' +
                '<td>' + counter + '</td>' +
                '<td><span>' + itemName + '</span><input type="hidden" name="items['+itemID+'][id]" value="'+itemID+'">' +
                '<input type="hidden" name="items['+itemID+'][name]" value="'+itemName+'">' +
                '</td>' +
                '<td>' + itemPrice +
                '<input type="hidden" name="items['+itemID+'][price]" value="'+itemPrice+'">' +
                '</td>' +
                '<td><input type="number" class="form-control" name="items['+itemID+'][qty]" value="'+itemQty+'">' +
                '</td>' +
                '<td>' + itemTotal +
                '<input type="hidden" name="items['+itemID+'][itemTotal]" value="'+itemTotal+'">' +
                '</td>' +
                '<td>' + itemNotes + '<input type="hidden" name="items['+itemID+'][notes]" value="'+itemNotes+'">' +
                '</td>' +
                '<td><button type="button" class="btn btn-danger btn-sm deleteItem"><i class="fa fa-trash"></i></button></td>' +
                '</tr>');
            counter++

            totalPrice += itemTotal;
            totalPrice = Math.round((totalPrice + Number.EPSILON) * 100) / 100;
            $("#total_price").text(totalPrice);

            calculateDiscount()


            item.val("").trigger('change')
            qnt.val("")
            notes.val("")
        })

        $("#discount_value").on('keyup', function (e){
            e.preventDefault()
            calculateDiscount()
        })
        $('input[name="discount_type"]').on('change', function (e){
            e.preventDefault()
            calculateDiscount()
        })

        $(document).on('click', '.deleteItem', function (e) {
            // get the total of the item in the same row
            let itemTotal = $(this).closest('tr').find('td:nth-child(5)').text();
            totalPrice -= itemTotal;
            totalPrice = Math.round((totalPrice + Number.EPSILON) * 100) / 100;
            $("#total_price").text(totalPrice);
            calculateDiscount()
            $(this).closest('tr').remove();
        })




        function calculateDiscount(){
            let discount = 0;
            // get discount type using input name
            let discountType = $('input[name="discount_type"]:checked').val();
            if (discountType === '{{ \App\Enums\DiscountTypeEnum::fixed->value }}') {
                discount = parseFloat($("#discount_value").val() || 0);
            } else {
                let discountPercent = parseFloat($("#discount_value").val() || 0);
                discount = (totalPrice * discountPercent) / 100;
                // round to 2 decimal places
                discount = Math.round((discount + Number.EPSILON) * 100) / 100;
            }
            net = totalPrice - discount;
            net = Math.round((net + Number.EPSILON) * 100) / 100;
            $("#discount").text(discount);
            $("#net").text(net);
            calculateRemaining()
        }

        $('input[name="payment_type"]').on('change', function (e){
            let paymentType = $('input[name="payment_type"]:checked').val();
            if(paymentType == 1){
                $("#payment_amount").val("");
                $("#payment_amount").attr('disabled', true);
                // update payment_amount
            }else{
                $("#payment_amount").attr('disabled', false);
            }
            e.preventDefault()
            calculateRemaining()
        })

        $("#payment_amount").on('keyup', function (e){
            e.preventDefault()
            calculateRemaining()

        })

        function calculateRemaining(){
            let paymentType = $('input[name="payment_type"]:checked').val();
            let paid = parseFloat($("#payment_amount").val());
            if(paymentType == 1){
                paid = net;
            }
            let remaining = net - paid;
            remaining = Math.round((remaining + Number.EPSILON) * 100) / 100;
            $("#paid").text(paid);
            $("#remaining").text(remaining);
        }
    </script>
@endpush
