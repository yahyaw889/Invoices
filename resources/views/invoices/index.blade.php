@extends('layouts.master')
@section('title' , 'قائمة الفواتير')

@section('css')
{{--    <!-- Internal Data table css -->--}}
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
{{--    <!--Internal   Notify -->--}}
    <link href="{{ URL::asset('assets/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة
                    الفواتير</span>
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

    <!-- row -->
    <div class="row">
        <!--div-->
        <div class="col-xl-12">
            <div class="card mg-b-20">
                <div class="card-header pb-0">
                    @can('create-invoice')
                    <a href="invoices/create" class="modal-effect btn btn-sm btn-primary" style="color:white"><i
                            class="fas fa-plus"></i>&nbsp; اضافة فاتورة</a>
                    @endcan
                        @can('print-invoice')
                        <a class="modal-effect btn btn-sm btn-primary" href="{{ route('invoices.export') }}"
                           style="color:white"><i class="fas fa-file-download"></i>&nbsp;تصدير اكسيل</a>
                        @endcan


                    <div class="dropdown d-inline-block">
                        <button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-success btn-sm"
                                data-toggle="dropdown" type="button"> التصنيفات الحاله .<i class="fas fa-caret-down ml-1"></i></button>
                        <div class="dropdown-menu tx-13">
                            <a class="dropdown-item @if(request()->is('invoices')) active @endif" href="{{url('invoices')}}">الكل</a>
                        @foreach($statuses as $status)
                                <a class="dropdown-item @if(request()->is('invoices/' . $status->id . '/filter')) active @endif" href="{{ url('invoices/' . $status->id . '/filter') }}">
                                    {{ $status->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example1" class="table key-buttons text-md-nowrap" data-page-length='50'style="text-align: center">
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
                                <th class="border-bottom-0">العمليات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i = 1 @endphp
                            @foreach($invoices as $invoice)
                                {{--                                        onclick="window.location.href='{{route('invoices.show', $invoice->id)}}';" style="cursor:pointer;"--}}
                                <tr>

                                    <td >{{$i++}}</td>
                                    <td class="text-center">
                                        <a href="{{route('invoices.show', $invoice->id)}}"> {{$invoice->invoice_number}}</a></td>
                                    <td>{{$invoice->invoice_date->format('Y-m-d')}}</td>
                                    <td>{{$invoice->due_date->format('Y-m-d')}}</td>
                                    <td>{{$invoice->product->name}}</td>
                                    <td>{{$invoice->section->title}}</td>
                                    <td class="text-center" >{{$invoice->discount}}</td>
                                    <td class="text-center">{{$invoice->rate_vat}}</td>
                                    <td class="text-center" >{{$invoice->value_vat}}</td>
                                    <td class="text-center">{{$invoice->total}}</td>
                                    <td class="text-center">{{ number_format($invoice->total + $invoice->amount_collection, 2) }}</td>
                                    <td class="text-center "><a class="{{($invoice->status_id == 1 || $invoice->status_id == 3 || $invoice->status_id == 5 ? 'text-danger': ($invoice->status_id == 2 ? 'text-success' : 'text-warning'))  }}" href="{{route('invoices.show', $invoice->id)}}">
                                        @foreach($statuses as $status)
                                            @if($invoice->status_id == $status->id)
                                                {{ $status->name }}
                                            @endif
                                        @endforeach
                                        </a> </td>

                                    <td>
                                        <div class="dropdown">
                                            <button aria-expanded="false" aria-haspopup="true"
                                                    class="btn ripple btn-primary btn-sm" data-toggle="dropdown"
                                                    type="button">العمليات<i class="fas fa-caret-down ml-1"></i></button>
                                            <div class="dropdown-menu tx-13">
                                                @can('show-invoice')
                                                <a class="dropdown-item"
                                                   href=" {{ route('invoices.show' , $invoice->id)  }}">تفاصيل
                                                    الفاتورة</a>
                                                @endcan
                                                @can('edit-invoice')
                                                <a class="dropdown-item"
                                                   href=" {{ route('invoices.edit' , $invoice->id)  }}">تعديل
                                                    الفاتورة</a>
                                                @endcan
                                                    @can('delete-invoice')
                                                <a class="dropdown-item" href="#" data-invoice_id="{{ $invoice->id }}"
                                                   data-toggle="modal" data-target="#delete_invoice"><i
                                                        class="text-danger fas fa-trash-alt"></i>&nbsp;&nbsp;حذف
                                                    الفاتورة</a>
                                                    @endcan
                                                    @can('edit-status-invoice')
                                                <a class="dropdown-item"
                                                   href="{{route('invoices.status' , $invoice->id)}}"><i
                                                        class=" text-success fas
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    fa-money-bill"></i>&nbsp;&nbsp;تغير
                                                    حالة
                                                    الدفع</a>
                                                    @endcan


                                                    @can('print-invoice')
                                                <a class="dropdown-item" href="invoices/{{ $invoice->id }}/print"><i
                                                        class="text-success fas fa-print"></i>&nbsp;&nbsp;طباعة
                                                    الفاتورة
                                                </a>
                                                    @endcan
                                            </div>
                                        </div>

                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>

    <!-- حذف الفاتورة -->
    <div class="modal fade" id="delete_invoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف الفاتورة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <form action="{{ url('invoices/destroy') }}" method="post">
                        @method('delete')
                        @csrf

                        <div class="modal-body">
                            هل انت متاكد من عملية الحذف ؟
                            <input type="hidden" name="invoice_id" id="invoice_id" value="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-danger">تاكيد</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- ارشيف الفاتورة -->

    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <!--Internal  Datatable js -->
    <script src="{{ URL::asset('assets/js/table-data.js') }}"></script>
    <!--Internal  Notify js -->
    <script src="{{ URL::asset('assets/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        $('#delete_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var invoice_id = button.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })
        $('#delete_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var invoice_id = button.data('invoice_id');
            var modal = $(this);

            // Set the value for the hidden input field
            modal.find('.modal-body #invoice_id').val(invoice_id);

            // Update the form action URL using Laravel's route helper
            var form = modal.find('form'); // Select the form within the modal
            var newActionUrl = '{{ route('invoices.destroy', '') }}' + '/' + invoice_id; // Construct the new action URL
            form.attr('action', newActionUrl); // Set the form action to the new URL
        });


    </script>

    <script>
        $('#Transfer_invoice').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var invoice_id = button.data('invoice_id')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
        })

    </script>







@endsection
