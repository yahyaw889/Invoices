@extends('layouts.master')
@section('css')
    <!---Internal  Prism css-->
    <link href="{{ URL::asset('assets/plugins/prism/prism.css') }}" rel="stylesheet">
    <!---Internal Input tags css-->
    <link href="{{ URL::asset('assets/plugins/inputtags/inputtags.css') }}" rel="stylesheet">
    <!--- Custom-scroll -->
    <link href="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

@endsection
@section('title')
    تفاصيل فاتورة
@stop
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">قائمة الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    تفاصيل الفاتورة</span>
            </div>
        </div>

    </div>
    <!-- breadcrumb -->
@endsection
@section('content')


    @if (session()->has('success'))
        <script>
            window.onload = function() {
                notif({
                    msg: "{{ session()->get('success') }} ",
                    type: "success"
                })
            }

        </script>
    @endif
    @if (session()->has('error'))
        <script>
            window.onload = function() {
                notif({
                    msg: "{{ session()->get('error') }}",
                    type: "error"
                })
            }

        </script>
    @endif
    {{Session::forget('success')}}
    {{Session::forget('error')}}

    <div class="d-flex my-5">
        @can('edit-invoice')
        <a href="{{ route('invoices.edit' , $invoices->id) }}" class="btn btn-primary mx-2">تعديل الفاتورة</a>
        @endcan
        @can('edit-status-invoice')
        <a href="{{ route('invoices.status' , $invoices->id) }}" class="btn btn-success mx-2">تحديث حالة الفاتورة</a>
        @endcan
    </div>

    <!-- row opened -->
    <div class="row row-sm">

        <div class="col-xl-12">
            <!-- div -->
            <div class="card mg-b-20" id="tabs-style2">
                <div class="card-body">
                    <div class="text-wrap">
                        <div class="example">
                            <div class="panel panel-primary tabs-style-2">
                                <div class=" tab-menu-heading">
                                    <div class="tabs-menu1">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs main-nav-line">
                                            <li><a href="#tab4" class="nav-link active" data-toggle="tab">معلومات
                                                    الفاتورة</a></li>
                                            <li><a href="#tab5" class="nav-link" data-toggle="tab">حالات الدفع</a></li>
                                            <li><a href="#tab6" class="nav-link" data-toggle="tab">المرفقات</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body main-content-body-right border">
                                    <div class="tab-content">


                                        <div class="tab-pane active" id="tab4">
                                            <div class="table-responsive mt-15">

                                                <table class="table table-striped" style="text-align:center">
                                                    <tbody>
                                                    <tr>
                                                        <th scope="row">رقم الفاتورة</th>
                                                        <td>{{ $invoices->invoice_number }}</td>
                                                        <th scope="row">تاريخ الاصدار</th>
                                                        <td>{{ $invoices->invoice_date->format('Y-m-d') }}</td>
                                                        <th scope="row">تاريخ الاستحقاق</th>
                                                        <td>{{ $invoices->due_date->format('Y-m-d') }}</td>
                                                        <th scope="row">القسم</th>
                                                        <td>{{ $invoices->section->title }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th scope="row">المنتج</th>
                                                        <td>{{ $invoices->product->name }}</td>
                                                        <th scope="row">مبلغ التحصيل</th>
                                                        <td>{{ number_format($invoices->amount_collection , 2) }}</td>
                                                        <th scope="row">مبلغ العمولة</th>
                                                        <td>{{ number_format( $invoices->amount_commission , 2) }}</td>
                                                        <th scope="row">الخصم</th>
                                                        <td>{{ $invoices->discount }}</td>
                                                    </tr>


                                                    <tr>
                                                        <th scope="row">نسبة الضريبة</th>
                                                        <td>{{ $invoices->rate_vat }}</td>
                                                        <th scope="row">قيمة الضريبة</th>
                                                        <td>{{ number_format( $invoices->value_vat , 2) }}</td>
                                                        <th scope="row">الاجمالي العمولة مع الضريبة</th>
                                                        <td>{{number_format( $invoices->total , 2) }}</td>

                                                        <th scope="row">الحالة الحالية</th>

                                                        @if ($invoices->status_id == 2)
                                                            <td><span

                                                            @foreach($statuses as $status)
                                                                @if($status->id == $invoices->status_id)
                                                                    <td><span
                                                                            class="badge badge-pill badge-success">مدفوعة</span>
                                                                        @endif
                                                                        @endforeach
                                                            </td>
                                                        @elseif($invoices->status_id == 1 || $invoices->status_id == 3 || $invoices->status_id == 5 )
                                                            <td><span
                                                                    @foreach($statuses as $status)
                                                                        @if($status->id == $invoices->status_id)
                                                                          <td><span
                                                                                  class="badge badge-pill badge-danger">{{ $status->name }}</span>
                                                                          @endif
                                                                    @endforeach
                                                            </td>
                                                        @else
                                                            <td><span
                                                                    @foreach($statuses as $status)
                                                                        @if($status->id == $invoices->status_id)
                                                                            <td><span
                                                                                    class="badge badge-pill badge-warning">{{ $status->name }}</span>
                                                                                @endif
                                                                                @endforeach
                                                            </td>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">الاجمالي  مع الضريبة</th>
                                                        <td>{{ number_format($invoices->total + $invoices->amount_collection, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">ملاحظات</th>
                                                        <td>{!! $invoices->note ?? '_' !!}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>

                                        <div class="tab-pane" id="tab5">
                                            <div class="table-responsive mt-15">
                                                <table class="table center-aligned-table mb-0 table-hover"
                                                       style="text-align:center">
                                                    <thead>
                                                    <tr class="text-dark">
                                                        <th>#</th>
                                                        <th>رقم الفاتورة</th>
                                                        <th>حالة الدفع</th>
                                                        <th>تاريخ الدفع </th>
                                                        <th>باقي المدفوعات</th>
                                                        <th>المدفوع</th>
                                                        <th>العمولة مع الضريبة</th>
                                                        <th>المبلغ الكلي</th>
                                                        <th>طريقه الدفع</th>
                                                        <th>ملاحظات</th>
                                                        <th>تاريخ الاضافة </th>
                                                        <th>المستخدم</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php $i= 1 @endphp
                                                    @foreach ($details as $x)

                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $x->invoice->invoice_number }}</td>

                                                            @if ($x->status_id == 2)
                                                                <td><span
                                                                        class="badge badge-pill badge-success">مدفوعة</span>
                                                                </td>
                                                            @elseif($x->status_id == 1 || $x->status_id == 3 || $x->status_id == 5 )
                                                                <td><span
                                                                        class="badge badge-pill badge-danger">غير مدفوعة</span>
                                                                </td>
                                                            @else
                                                                <td><span
                                                                        class="badge badge-pill badge-warning">مدفوعة جزئيا</span>
                                                                </td>
                                                            @endif
                                                            <td class="{{ $x->invoice->payment_date == null ? 'text-danger' : 'text-success' }}">{{ $x->invoice->payment_date == null ? 'لم يتم الدفع بعد' : $x->invoice->payment_date->format('Y-m-d') }}</td>
                                                            <td>{{number_format( $x->remaining_payment , 2)}}</td>
                                                            <td>{{number_format( $x->total_payment , 2) }}</td>
                                                            <td>{{number_format( $x->invoice->total ,2)  }}</td>
                                                            <td>{{ number_format($x->total_amount, 2) }}</td>
                                                            <td>{{ $x->payment_method ?? 'لم يتم دفع' }}</td>
                                                            <td>{!!  $x->payment_note ?? '_' !!}</td>
                                                            <td>{{ $x->created_at }}</td>
                                                            <td>{{ $x->user->name }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>


                                            </div>
                                        </div>


                                        <div class="tab-pane" id="tab6">
                                            <!--المرفقات-->
                                            <div class="card card-statistics">
                                                    @can('add-attachments-invoice')
                                                    <div class="card-body">
                                                        <p class="text-danger">* صيغة المرفق pdf, jpeg ,.jpg , png </p>
                                                        <h5 class="card-title">اضافة مرفقات</h5>
                                                        <form method="post" action="{{ route('attachment.store') }}"
                                                              enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" id="customFile"
                                                                       name="img" required>
                                                                <input type="hidden" id="customFile" name="invoice_number"
                                                                       value="{{ $invoices->invoice_number }}">
                                                                <input type="hidden" id="invoice_id" name="invoice_id"
                                                                       value="{{ $invoices->id }}">
                                                                <label class="custom-file-label" for="customFile">حدد
                                                                    المرفق</label>
                                                            </div><br><br>
                                                            <button type="submit" class="btn btn-primary btn-sm "
                                                                    name="uploadedFile">تاكيد</button>
                                                        </form>
                                                    </div>
                                                    @endcan
                                                <br>

                                                <div class="table-responsive mt-15">
                                                    <table class="table center-aligned-table mb-0 table table-hover"
                                                           style="text-align:center">
                                                        <thead>
                                                        <tr class="text-dark">
                                                            <th scope="col">م</th>
                                                            <th scope="col">اسم الملف</th>
                                                            <th scope="col">قام بالاضافة</th>
                                                            <th scope="col">تاريخ الاضافة</th>
                                                            <th scope="col">العمليات</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $i = 1 @endphp
                                                            @foreach ($attachments as $attachment)

                                                                <tr>
                                                                    <td>{{ $i++ }}</td>
                                                                    <td><img src="{{ asset( 'img/' . $attachment->attachment) }}" width="50" height="30" alt="" srcset=""></td>
                                                                    <td>{{ $attachment->user->name }}</td>
                                                                    <td>{{ $attachment->created_at }}</td>
                                                                    <td colspan="2">

                                                                        <a class="btn btn-outline-success btn-sm"
                                                                           href="{{ asset( 'img/' . $attachment->attachment) }}"
                                                                           role="button"><i class="fas fa-eye"></i>&nbsp;
                                                                            عرض</a>

                                                                        <a class="btn btn-outline-info btn-sm"
                                                                           href="{{ url('attachment/img', $attachment->attachment) }}"
                                                                           role="button">
                                                                            <i class="fas fa-download"></i>&nbsp; تحميل
                                                                        </a>

                                                                            @can('delete-attachments')
                                                                        <button class="btn btn-outline-danger btn-sm"
                                                                                    data-toggle="modal"
                                                                                    data-file_name="{{ $attachment->attachment }}"
                                                                                    data-invoice_number="{{ $attachment->invoice->invoice_number }}"
                                                                                    data-id_file="{{ $attachment->id }}"
                                                                                    data-target="#delete_file">حذف
                                                                            </button>
                                                                        @endcan

                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>

                                                    </table>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /div -->
        </div>

    </div>
    <!-- /row -->

    <!-- delete -->
    <div class="modal fade" id="delete_file" tabindex="-1" role="dialog" aria-labelledby="delete_fileLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="deleteForm" action="" method="post">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <p class="text-center">
                        <h6 style="color:red"> هل انت متاكد من عملية حذف المرفق ؟</h6>
                        </p>

                        <input type="hidden" name="id_file" id="id_file" value="">
                        <input type="hidden" name="file_name" id="file_name" value="">
                        <input type="hidden" name="invoice_number" id="invoice_number" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
                        <button type="submit" class="btn btn-danger">تاكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!--Internal  Datepicker js -->
    <script src="{{ URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js') }}"></script>
    <!-- Internal Select2 js-->
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <!-- Internal Jquery.mCustomScrollbar js-->
    <script src="{{ URL::asset('assets/plugins/custom-scroll/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <!-- Internal Input tags js-->
    <script src="{{ URL::asset('assets/plugins/inputtags/inputtags.js') }}"></script>
    <!--- Tabs JS-->
    <script src="{{ URL::asset('assets/plugins/tabs/jquery.multipurpose_tabcontent.js') }}"></script>
    <script src="{{ URL::asset('assets/js/tabs.js') }}"></script>
    <!--Internal  Clipboard js-->
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/clipboard/clipboard.js') }}"></script>
    <!-- Internal Prism js-->
    <script src="{{ URL::asset('assets/plugins/prism/prism.js') }}"></script>

    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>


    <script>
        $(document).ready(function() {
            $('#delete_file').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id_file = button.data('id_file'); // Extract info from data-* attributes
                var file_name = button.data('file_name');
                var invoice_number = button.data('invoice_number');
                var actionUrl = "{{ route('attachment.destroy', '') }}"; // Base route for deletion

                // Set the form action with the id_file
                $(this).find('#deleteForm').attr('action', actionUrl + '/' + id_file);

                // Update the hidden input values
                $(this).find('#id_file').val(id_file);
                $(this).find('#file_name').val(file_name);
                $(this).find('#invoice_number').val(invoice_number);
            });
        });
    </script>


    <script>
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

    </script>

@endsection
