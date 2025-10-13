@extends('admin.layouts.app', [
    'pageName' => __('trans.sales'),
])

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@lang('trans.sales_create')</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}" id="main-form">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="client_id">@lang('trans.client')</label>
                                    <select
                                        name="client_id"
                                        id="client_id"
                                        class="form-control select2 @error('client_id') is-invalid @enderror">
                                        <option value="">@lang('trans.choose')</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6">
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
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="invoice_number">@lang('trans.invoice_number')</label>
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
                                    <th>Qnt</th>
                                    <th>Total</th>
                                    <th>Notes</th>
                                </tr>
                                <tbody id="items_list">
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total</th>
                                    <th id="total_price">0</th>
                                </tr>
                                </tfoot>
                            </table>
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
      var counter = 1;
      var totalPrice = 0;
      $("#add_item").on('click', function(e){
        e.preventDefault();
        let itemID = $("#item_id").val();
        let itemName = $("#item_id option:selected").text();
        let itemPrice = $("#item_id option:selected").data('price');
        var itemQty = $("#qty").val();
        let itemNotes = $("#notes").val();
        let itemTotal = itemPrice * itemQty;

        // validate inputs : item choosen , qty > 0, available qty
        if(!itemID){
          // sweet alart error
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please choose an item',
          })
          return;
        }
        if(!itemQty || itemQty <= 0 || itemQty > $("#item_id option:selected").data('quantity')){
          // sweet alart error
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please enter a valid quantity',
          })
          return;
        }
        console.log("clicked");

        console.log(itemID);
        console.log(itemName);
        console.log(itemPrice);
        console.log(itemQty);
        console.log(itemNotes);

        $("#items_list").append('' +
          '<tr>' +
            '<td>'+ counter +'</td>'+
            '<td>'+ itemName +'</td>'+
            '<td>'+ itemPrice +'</td>'+
            '<td>'+ itemQty +'</td>'+
            '<td>'+ itemTotal +'</td>'+
            '<td>'+ itemNotes +'</td>'+
          '</tr>');
        counter++;
        totalPrice += itemTotal;

        $("#item_id").val("");
        $("#qty").val("");
        $("#notes").val("");
      })
    </script>
@endpush