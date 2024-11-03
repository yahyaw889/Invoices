@extends('layouts.master')
@section('css')
@endsection
@section('title')
    تغير حالة الدفع
@stop
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    تغير حالة الدفع</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif


    <!-- row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('status.update',  $invoices->id) }}" method="post" autocomplete="off">
                        @csrf
                        {{-- 1 --}}
                        <div class="row">
                            <div class="col">
                                <label for="inputName" class="control-label">رقم الفاتورة</label>
                                <input type="hidden" name="invoice_id" value="{{ $invoices->id }}">
                                <input type="text" class="form-control" id="inputName" name="invoice_number"
                                       title="يرجي ادخال رقم الفاتورة" value="{{ $invoices->invoice_number }}" required
                                       readonly>
                            </div>

                            <div class="col">
                                <label>تاريخ الفاتورة</label>
                                <input class="form-control fc-datepicker" name="invoice_Date" placeholder="YYYY-MM-DD"
                                       type="text" value="{{ $invoices->invoice_date->format('Y-m-d') }}" required readonly>
                            </div>

                            <div class="col">
                                <label>تاريخ الاستحقاق</label>
                                <input class="form-control fc-datepicker" name="Due_date" placeholder="YYYY-MM-DD"
                                       type="text" value="{{ $invoices->due_date->format('Y-m-d') }}" required readonly>
                            </div>

                        </div>

                        {{-- 2 --}}
                        <div class="row">
                            <div class="col">
                                <label for="inputName" class="control-label">القسم</label>
                                <select name="Section" class="form-control SlectBox" onclick="console.log($(this).val())"
                                        onchange="console.log('change is firing')" readonly>
                                    <!--placeholder-->
                                    <option value=" {{ $invoices->section_id }}">
                                        {{ $invoices->section->title }}
                                    </option>

                                </select>
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">المنتج</label>
                                <select id="product" name="product" class="form-control" readonly>
                                    <option value="{{ $invoices->product_id }}"> {{ $invoices->product->name }}</option>
                                </select>
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">مبلغ التحصيل</label>
                                <input type="text" class="form-control" id="inputName" name="Amount_collection"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                       value="{{ $invoices->amount_collection }}" readonly>
                            </div>
                        </div>


                        {{-- 3 --}}

                        <div class="row">

                            <div class="col">
                                <label for="inputName" class="control-label">مبلغ العمولة</label>
                                <input type="text" class="form-control form-control-lg" id="Amount_Commission"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                       value="{{ $invoices->amount_commission }}" required readonly>
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">الخصم</label>
                                <input type="text" class="form-control form-control-lg" id="Discount" name="Discount"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                       value="{{ $invoices->discount }}" required readonly>
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">نسبة ضريبة القيمة المضافة</label>
                                <select name="Rate_VAT" id="Rate_VAT" class="form-control" onchange="myFunction()" readonly>
                                    <!--placeholder-->
                                    <option value=" {{ $invoices->rate_vat }}">
                                    {{ $invoices->rate_vat }}
                                </select>
                            </div>

                        </div>

                        {{-- 4 --}}

                        <div class="row">
                            <div class="col">
                                <label for="inputName" class="control-label">قيمة ضريبة القيمة المضافة</label>
                                <input type="text" class="form-control" id="Value_VAT" name="Value_VAT"
                                       value="{{ $invoices->value_vat }}" readonly>
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">الاجمالي شامل الضريبة</label>
                                <input type="text" class="form-control" id="Total" name="Total" readonly
                                       value="{{ $invoices->total }}">
                            </div>
                            <div class="col">
                                <label for="inputName" class="control-label">الاجمالي شامل الضريبة</label>
                                <input type="text" class="form-control" id="fullTotal" name="fullTotal" readonly
                                       value="{{ number_format( $invoices->total + $invoices->amount_collection , 2) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <label for="inputName" class="control-label">المبلغ المدفوع</label>
                                <input type="text" class="form-control" id="total_payment" name="total_payment"
                                       value="{{ number_format( $invoices->total + $invoices->amount_collection - $details->remaining_payment  , 2)}}" readonly>
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">المبلغ المتبقي</label>
                                <input type="text" class="form-control" id="remaining_payment" name="remaining_payment" readonly
                                       value="{{ $details->remaining_payment  }}">
                            </div>

                        </div>

                        {{-- 5 --}}
                        <div class="row">
                            <div class="col">
                                <label for="exampleTextarea">ملاحظات</label>
                                <textarea class="form-control" id="exampleTextarea" name="note" rows="3" readonly>{{ strip_tags($invoices->note) }}</textarea>
                            </div>
                        </div><br>

                        <div class="row">
                            <div class="col">
                                <label for="exampleTextarea">حالة الدفع</label>
                                <select class="form-control" id="Status" name="status" required>
                                    <option  disabled="disabled">-- حدد حالة الدفع --</option>
                                    @foreach($statuses as $status)
                                        <option {{$invoices->status_id == $status->id ? 'selected' : '' }} value="{{$status->id}}">{{$status->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col">
                                <label>تاريخ الدفع</label>
                                <input class="form-control fc-datepicker" name="payment_date" placeholder="YYYY-MM-DD"
                                  value="{{date('Y-m-d')}}"      type="text" required>
                            </div>


                        </div><br>
{{--                        testing--}}
                        <div class="row" id="additionalInputs" style="display: none;">
                            <div class="col" >
                                <label for="inputName" class="control-label">المبلغ المدفوع</label>
                                <input type="number"  class="form-control" id="additionalField1" name="total_payment"
                                       placeholder="المبلغ المدفوع"
                                       title="يرجي ادخال الرقم المدفوع"
                                       >
                                <small style="color: red; display: none" id="total_payment_input">المبلغ اكبر من المطلوب</small>
                                @error('total_payment')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <label>طريقة الدفع</label>
                                <input class="form-control " name="payment_method" placeholder="يرجي ادخال طريقه الدفع"
                                       type="text"  value="{{ old('payment_method') }}">
                                @error('payment_method')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <div class="col" id="additionalInputs1" style="display: none;">
                            <label>طريقة الدفع</label>
                            <select class="form-control" id="optionSelect" name="payment_method1">
                                <option value="يرجي ادخال طريقه الدفع">يرجي ادخال طريقه الدفع.</option>
                                <option value="instapay">instapay</option>
                                <option value="vodavone">vodavone</option>
                                <option value="paypal">paypal</option>
                                <option value="card">card</option>
                                <option value="offline">offline</option>

                            </select>
                            
                            @error('payment_method1')
                            <div style="color: red;">{{ $message }}</div>
                            @enderror
                        </div>
                        <br>
                        <div class="col">
                            <label>ملحوظات</label>
                            <input class="form-control" name="payment_note" placeholder="يرجي ادخال اي ملاحظات علي الفاتورة"
                                   type="text"  value="{{old('payment_note')}}">

                        </div>

                        <br>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">تحديث حالة الدفع</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!--Internal  Form-elements js-->
    <script src="{{ URL::asset('assets/js/advanced-form-elements.js') }}"></script>
    <script src="{{ URL::asset('assets/js/select2.js') }}"></script>
    <!--Internal Sumoselect js-->
    <script src="{{ URL::asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
    <!-- Internal form-elements js -->
    <script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>

    <script>
        var date = $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        }).val();

    </script>
    <script>
        $(document).ready(function() {

            $('#Status').on('change', function() {
                if ($(this).val() == 4) {
                    $('#additionalInputs').show(); // Show the additional inputs
                } else {
                    $('#additionalInputs').hide(); // Hide the additional inputs
                }
            });
            $('#Status').on('change', function() {
                if ($(this).val() == 2) {
                    $('#additionalInputs1').show(); // Show the additional inputs
                } else {
                    $('#additionalInputs1').hide(); // Hide the additional inputs
                }
            });

                let status =$('#Status').val();
                if(status == 4){
                    $('#additionalInputs').show();
                }else if(status == 2){
                    $('#additionalInputs1').show();
                }else {
                    $('#additionalInputs').hide();
                    $('#additionalInputs1').hide();
                }

            $('#additionalField1').on('input', function() {
                let additionalField1 = $('#additionalField1').val();
                let remaining_payment = $('#remaining_payment').val();

                if (parseFloat(additionalField1) > parseFloat(remaining_payment)) {
                    $('#additionalField1').css('border', '1px solid red');
                    $('#total_payment_input').show();
                } else {
                    $('#additionalField1').css('border', '1px solid blue');
                    $('#total_payment_input').hide();
                }
            });


        });
    </script>
@endsection
