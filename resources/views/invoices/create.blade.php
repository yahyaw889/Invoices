@extends('layouts.master')
@section('css')
{{--    {{Internal Select2 css}} --}}
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
{{--    <!---Internal Fileupload css-->--}}
    <link href="{{ URL::asset('assets/plugins/fileuploads/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
{{--    <!---Internal Fancy uploader css-->--}}
    <link href="{{ URL::asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />
{{--    <!--Internal Sumoselect css-->--}}
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/sumoselect/sumoselect-rtl.css') }}">
{{--    <!--Internal  TelephoneInput css-->--}}
    <link rel="stylesheet" href="{{ URL::asset('assets/plugins/telephoneinput/telephoneinput-rtl.css') }}">

<link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

@endsection
@section('title')
    اضافة فاتورة
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    اضافة فاتورة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
{{--    @if ($errors->any())--}}
{{--        <div class="alert alert-danger">--}}
{{--            <ul>--}}
{{--                @foreach ($errors->all() as $error)--}}
{{--                    <li>{{ $error }}</li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    @endif--}}

    @if($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif
    @if (session()->has('success'))
        <script>
            window.onload = function() {
                notif({
                    msg: "{{session()->get('success')}}",
                    type: "success"
                })
            }

        </script>
    @endif
    @if (session()->has('error'))
        <script>
            window.onload = function() {
                notif({
                    msg: "{{session()->get('error')}}",
                    type: "error"
                })
            }

        </script>
    @endif


    <!-- row -->
    <div class="row">

        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('invoices.store') }}" method="post" enctype="multipart/form-data"
                          autocomplete="off">
                        {{ csrf_field() }}
                        {{-- 1 --}}

                        <div class="row">
                            <div class="col">
                                <label for="inputName" class="control-label">رقم الفاتورة</label>
                                <input type="text" class="form-control" id="inputName" name="invoice_number"
                                       placeholder="رقم الفاتورة"
                                       title="يرجي ادخال رقم الفاتورة"  value="{{ $invoice_number }}"
                                       required>
                                @error('invoice_number')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <label>تاريخ الفاتورة</label>
                                <input class="form-control fc-datepicker" name="invoice_date" placeholder="YYYY-MM-DD"
                                       type="text" value="{{ date('Y-m-d') }}" required>
                                @error('invoice_date')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <label>تاريخ الاستحقاق</label>
                                <input class="form-control fc-datepicker" name="due_date" placeholder="YYYY-MM-DD"
                                       type="text" required value="{{old('due_date')}}">
                                @error('due_date')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- 2 --}}
                        <div class="row">
                            <div class="col">
                                <label for="inputName" class="control-label">القسم</label>
                                <select name="section" class="form-control SlectBox" value="{{old('section')}}">
                                    <!--placeholder-->
                                    <option value="" selected disabled>حدد القسم</option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}"> {{ $section->title }}</option>
                                    @endforeach
                                </select>
                                @error('section')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">المنتج</label>
                                <select id="product" name="product" class="form-control">
                                </select>
                                @error('product')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">مبلغ التحصيل</label>
                                <input type="text" class="form-control Amount_collection" id="Amount_collection" name="amount_collection"
                                       value="{{old('amount_collection')}}"     oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                @error('amount_collection')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        {{-- 3 --}}

                        <div class="row">

                            <div class="col">
                                <label for="inputName" class="control-label">مبلغ العمولة</label>
                                <input type="text" class="form-control form-control-lg" id="Amount_Commission"
                                       value="{{old('amount_commission')}}"
                                       name="amount_commission" title="يرجي ادخال مبلغ العمولة "
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                       required>
                                @error('amount_commission')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">الخصم</label>
                                <input type="text" class="form-control form-control-lg" id="Discount" name="discount"
                                       value="{{old('discount')}}"
                                       title="يرجي ادخال مبلغ الخصم "
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                       value=0 required>
                                @error('discount')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">نسبة ضريبة القيمة المضافة</label>
                                <select name="rate_vat" id="Rate_VAT" class="form-control"  value="{{old('rate_vat')}}"  onchange="myFunction()">
                                    <!--placeholder-->
                                    <option value="" selected disabled>حدد نسبة الضريبة</option>
                                    <option value="2%">2%</option>
                                    <option value="5%">5%</option>
                                    <option value="10%">10%</option>
                                    <option value="13%">13%</option>
                                    <option value="15%">15%</option>
                                    <option value="20%">20%</option>
                                </select>
                                @error('rate_vat')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- 4 --}}

                        <div class="row">
                            <div class="col">
                                <label for="inputName" class="control-label">قيمة ضريبة القيمة المضافة</label>
                                <input type="text" class="form-control" id="Value_VAT" name="value_vat" readonly>
                                @error('value_vat')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                <label for="inputName" class="control-label">الاجمالي العمولة شامل الضريبة</label>
                                <input type="text" class="form-control" id="Total" name="total" readonly>
                                @error('total')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col">
                                    <label for="inputName" class="control-label">الاجمالي شامل الضريبة</label>
                                    <input type="text" class="form-control" id="FullTotal" name="full_total" readonly>
                                @error('full_total')
                                <div style="color: red;">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        {{-- 5 --}}
                        <div class="row">
                            <div class="col">
                                <label for="exampleTextarea">ملاحظات</label>
                                <textarea class="form-control" id="exampleTextarea" name="note" rows="3"></textarea>
                            </div>
                        </div><br>

                        <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
                        <h5 class="card-title">المرفقات</h5>

                        <div class="col-sm-12 col-md-12">
                            <input type="file" name="img" class="dropify" accept=".pdf,.jpg, .png, image/jpeg, image/png"
                                   data-height="70" />
                            @error('img')
                            <div style="color: red;">{{ $message }}</div>
                            @enderror
                        </div><br>

                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">حفظ البيانات</button>
                        </div>


                    </form>
                </div>
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
    <!--Internal Fileuploads js-->
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fileuploads/js/file-upload.js') }}"></script>
    <!--Internal Fancy uploader js-->
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>
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
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        var date = $('.fc-datepicker').datepicker({
            dateFormat: 'yy-mm-dd'
        }).val();

    </script>

    <script>
        $(document).ready(function() {
            $('select[name="section"]').on('change', function() {
                var SectionId = $(this).val();
                if (SectionId) {
                    $.ajax({
                        url: "{{ URL::to('section') }}/" + SectionId,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $('select[name="product"]').empty();
                            $.each(data, function(key, value) {
                                $('select[name="product"]').append('<option value="' +
                                    value + '">' + key + '</option>');
                            });
                        },
                    });

                } else {
                    console.log('AJAX load did not work');
                }
            });

        });

    </script>



    <script>
        $(document).ready(function() {
            // Validate amounts when the input changes
            $('#Amount_Commission, #Amount_collection').on('input', function() {
                var commissionAmount = parseFloat($('#Amount_Commission').val()) || 0;
                var collectionAmount = parseFloat($('.Amount_collection').val()) || 0;

                // Check if commission amount exceeds collection amount
                if (commissionAmount > collectionAmount) {
                    alert('المبلغ العمولة لا يجب أن يكون أكبر من المبلغ المتحصل عليه');
                    $('#Amount_Commission').val(''); // Clear the commission input
                }
            });
        });
    </script>


    <script>
        function myFunction() {

            var Amount_Commission = parseFloat(document.getElementById("Amount_Commission").value);
            var Amount_collection = parseFloat(document.getElementById("Amount_collection").value);
            var Discount = parseFloat(document.getElementById("Discount").value);
            var Rate_VAT = parseFloat(document.getElementById("Rate_VAT").value);
            var Value_VAT = parseFloat(document.getElementById("Value_VAT").value);
            var FullTotal = parseFloat(document.getElementById("FullTotal").value);

            var Amount_Commission2 = Amount_Commission - Discount;


            if (typeof Amount_Commission === 'undefined' || !Amount_Commission) {

                alert('يرجي ادخال مبلغ العمولة ');

            } else {
                var intResults = Amount_Commission2 * Rate_VAT / 100;

                var intResults2 = parseFloat(intResults + Amount_Commission2);
                var intResults3 = parseFloat(intResults2 + Amount_collection);

                sumq = parseFloat(intResults).toFixed(2);

                sumt = parseFloat(intResults2).toFixed(2);


                document.getElementById("Value_VAT").value = sumq;

                document.getElementById("Total").value = sumt;
                document.getElementById("FullTotal").value = intResults3;

            }

        }

    </script>


@endsection
