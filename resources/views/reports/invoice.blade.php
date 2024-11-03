@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    <!-- Internal Spectrum-colorpicker css -->
    <link href="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.css') }}" rel="stylesheet">

    <!-- Internal Select2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

@section('title')
    تقرير الفواتير - مورا سوفت للادارة الفواتير
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">التقارير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تقرير
                الفواتير</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection
@section('content')

@if (count($errors) > 0)
    <div class="alert alert-danger">
        <button aria-label="Close" class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong>خطا</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- row -->
<div class="row">

    <div class="col-xl-12">
        <div class="card mg-b-20">


            <div class="card-header pb-0">

                <form action="{{url('/report/invoices/search' )}}" id="myForm" method="POST" role="search" autocomplete="off">
                    @csrf


                    <div class="col-lg-3">
                        <label class="rdiobox">
                            <input checked name="radio" type="radio" value="1" id="type_div"> <span>بحث بنوع
                                الفاتورة</span></label>
                    </div>


                    <div class="col-lg-3 mg-t-20 mg-lg-t-0">
                        <label class="rdiobox"><input name="radio" value="2" type="radio"><span>بحث برقم الفاتورة
                            </span></label>
                    </div><br><br>

                    <div class="row">

                        <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="type">
                            <p class="mg-b-10">تحديد نوع الفواتير</p><select class="form-control select2" name="type"
                                required>
                                <option value="{{ $type ?? 'حدد نوع الفواتير' }}" selected>
                                    {{ $type ?? 'حدد نوع الفواتير' }}
                                </option>

                                @foreach($statuses as $status)
                                    <option value="{{$status->id}}">{{$status->name}}</option>

                                @endforeach


                            </select>
                        </div><!-- col-4 -->


                        <div class="col-lg-3 mg-t-20 mg-lg-t-0" id="invoice_number">
                            <p class="mg-b-10">البحث برقم الفاتورة</p>
                            <input type="text" class="form-control" id="invoice_number" name="invoice_number">

                        </div><!-- col-4 -->

                        <div class="col-lg-3" id="start_at">
                            <label for="exampleFormControlSelect1">من تاريخ</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div><input class="form-control fc-datepicker" value="{{ $start_at ?? '' }}"
                                    name="start_at" placeholder="YYYY-MM-DD" type="text">
                            </div><!-- input-group -->
                        </div>

                        <div class="col-lg-3" id="end_at">
                            <label for="exampleFormControlSelect1">الي تاريخ</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div><input class="form-control fc-datepicker" name="end_at"
                                    value="{{ $end_at ?? '' }}" placeholder="YYYY-MM-DD" type="text">
                            </div><!-- input-group -->
                        </div>
                    </div><br>

                    <div class="row">
                        <div class="col-sm-1 col-md-1">
                            <button class="btn btn-primary btn-block">بحث</button>
                        </div>
                        <div class="col-sm-2 col-md-2">
                            <button type="button" id="reset" class="btn btn-primary">إعادة بحث</button>
                        </div>
                        <div class="spinner-border text-primary mx-2" id="spinner" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </form>

            </div>
            <div>

            <div class="card-body" >
                <div class="table-responsive" id="empty_table">
                        <table id="example" class="table key-buttons text-md-nowrap" style=" text-align: center">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">رقم الفاتورة</th>
                                    <th class="border-bottom-0">تاريخ القاتورة</th>
                                    <th class="border-bottom-0">تاريخ الاستحقاق</th>
                                    <th class="border-bottom-0">المنتج</th>
                                    <th class="border-bottom-0">القسم</th>
                                    <th class="border-bottom-0">الخصم</th>
                                    <th class="border-bottom-0">نسبة الضريبة</th>
                                    <th class="border-bottom-0">قيمة الضريبة</th>
                                    <th class="border-bottom-0">الاجمالي العمولة</th>
                                    <th class="border-bottom-0">الاجمالي</th>
                                    <th class="border-bottom-0">الحالة</th>
{{--                                    <th class="border-bottom-0">ملاحظات</th>--}}

                                </tr>
                            </thead>
                            <tbody>
                                                            </tbody>
                        </table>

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
<!-- Internal Data tables -->
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>--}}
<!--Internal  Datatable js -->
{{--<script src="{{ URL::asset('assets/js/table-data.js') }}"></script>--}}

<!--Internal  Datepicker js -->
<script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
<!--Internal  jquery.maskedinput js -->
<script src="{{ URL::asset('assets/plugins/jquery.maskedinput/jquery.maskedinput.js') }}"></script>
<!--Internal  spectrum-colorpicker js -->
<script src="{{ URL::asset('assets/plugins/spectrum-colorpicker/spectrum.js') }}"></script>
<!-- Internal Select2.min js -->
<script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Internal Ion.rangeSlider.min js -->
<script src="{{ URL::asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<!--Internal  jquery-simple-datetimepicker js -->
<script src="{{ URL::asset('assets/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js') }}"></script>
<!-- Ionicons js -->
<script src="{{ URL::asset('assets/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js') }}"></script>
<!--Internal  pickerjs js -->
<script src="{{ URL::asset('assets/plugins/pickerjs/picker.min.js') }}"></script>
<!-- Internal form-elements js -->
<script src="{{ URL::asset('assets/js/form-elements.js') }}"></script>
<script>
    var date = $('.fc-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    }).val();

</script>

<script>
    function formatDate(dateString) {
        const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', options);
    }
    $('#reset').on('click', ()=>{
        $('input[type="text"]').val('');
        $('#invoice_number').val('');
        $('#start_at').val('');
        $('#end_at').val('');
    })
    $(document).ready(function() {


        $('#invoice_number').hide();

        $('input[type="radio"]').click(function() {
            if ($(this).attr('id') == 'type_div') {
                $('#invoice_number').hide();
                $('#type').show();
                $('#start_at').show();
                $('#end_at').show();
            } else {
                $('#invoice_number').show();
                $('#type').hide();
                $('#start_at').hide();
                $('#end_at').hide();
            }
        });
        $('#spinner').hide();
        $('#empty_table').hide();
        $('#myForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            $('#spinner').show();
            $.ajax({
                url: '{{url('/report/invoices/search' )}}',  // Laravel route for handling form submission
                type: 'POST',                       // POST request
                data: $(this).serialize(),          // Serialize form data
                success: function(response) {
                    $('#empty_table').show();
                    $('#spinner').hide();
                    $('#example tbody').empty();
                    response =   JSON.parse(response);

                    // Loop through the response data and append rows to the table
                    response.forEach(function(invoice, index) {
                        let statusLabel = '';

                        // Determine the status label color based on Value_Status
                        if (invoice.status_id === 2) {
                            statusLabel = '<span class="text-success">مدفوعة</span>';
                        } else if (invoice.status_id === 4) {
                            statusLabel = '<span class="text-warning">مدفوعة جزئيا</span>';
                        } else {
                            statusLabel = '<span class="text-danger">غير مدفوعة</span>';
                        }

                        // Append the row to the table body
                        $('#example tbody').append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>
                        <a href="{{url('/')}}/invoices/${invoice.id}">${invoice.invoice_number}</a>
                        </td>
                        <td>${formatDate(invoice.invoice_date)}</td>
                        <td>${formatDate(invoice.due_date)}</td>
                        <td>${invoice.product.name}</td> <!-- Adjust this with actual product name if needed -->
                        <td><a href="{{url('/')}}/invoices/${invoice.id}">${invoice.section.title}</a></td> <!-- Adjust section display as needed -->
                        <td>${invoice.discount}</td>
                        <td>${invoice.rate_vat}</td>
                        <td>${invoice.value_vat}</td>
                        <td>${invoice.total}</td>
                        <td>${invoice.total + invoice.amount_collection}</td>
                        <td> <a href="{{url('/')}}/invoices/${invoice.id}">${statusLabel}</a></td>

                    </tr>
                `);
                    });
                },
                error: function(xhr, status, error) {
                    $('#spinner').hide();
                    // Handle error response
                    console.log(xhr.responseJSON);
                    alert('An error occurred: ' + xhr.responseJSON.message);
                }
            });
        });


    });

</script>


@endsection
