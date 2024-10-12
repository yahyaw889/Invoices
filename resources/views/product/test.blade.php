@extends('layouts.master')
@section('css')
    {{--     Internal Data table css--}}
    <link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    {{--    Internal Owl Carousel css--}}
    <link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet">
    {{--    <!---Internal  Multislider css-->--}}
    <link href="{{URL::asset('assets/plugins/multislider/multislider.css')}}" rel="stylesheet">
    {{--   Select2 css --}}
    <link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    {{-- Internal Sweet-Alert css--}}
    <link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet">
@endsection
@section('page-header')
    @if(session()->has('Add'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('Add') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ session()->get('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الإعدادات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/المنتجات</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <div class="col-1">
                            <a class="modal-effect btn btn-outline-primary btn-block" data-effect="effect-newspaper" data-toggle="modal" href="#modaldemo8">إضافة  +</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example1" data-page-length="50">
                            <thead>
                            <tr>
                                <th class="wd-15p p-0 border-bottom-0 small-column">#</th>
                                <th class="wd-15p border-bottom-0">اسم المنتج</th>
                                <th class="wd-20p border-bottom-0">الوصف</th>
                                <th class="wd-15p border-bottom-0">اسم القسم</th>
                                <th class="wd-15p border-bottom-0">تاريخ الانشاء</th>
                                <th class="wd-15p border-bottom-0">التعديلات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i = 1 @endphp
                            @foreach($products as $product)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->description }}</td>
                                    <td>{{ $product->section->title }}</td>
                                    <td>{{ $product->created_at }}</td>
                                    <td>
                                        <a class="modal-effect" data-effect="effect-rotate-left" data-toggle="modal"
                                           href="#modaldemo6" data-id="{{$product->id}}" data-name="{{$product->name}}" data-body="{{$product->description}}">
                                            <button type="button" class="btn btn-warning">تعديل</button>
                                        </a>
                                        <form action="{{route('section.destroy' , $product->id )}}" id="delete-form" method="post" style="display:inline">
                                            @csrf
                                            @method('delete')
                                            <div class="btn ripple">
                                                <button type="submit" class="btn btn-danger" id='swal-warning' >حذف</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach


                            </tbody>
                        </table>
                        {{ $products->links() }}
                    </div>
                    العدد الكلي للأقسام    {{ $total }}
                </div>
            </div>
        </div>

    </div>
    <!-- row closed -->
    </div>
    <!-- Container closed -->
    </div>
    <div class="modal edit-modal" id="modaldemo8">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">انشاء قسم جديد</h6>
                    <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="myForm" action="{{route('section.store')}}" method="POST">
                        @csrf
                        <input type="hidden" id="id" name="id"> <!-- Hidden ID field -->

                        <div class="row mb-3">
                            <label for="title" class="col-sm-2 col-form-label">عنوان القسم:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="body" class="col-sm-2 col-form-label">الوصف:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="body" name="body" rows="4" required></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn ripple btn-primary" form="myForm" id="submitBtn" type="submit">Save changes</button>
                    <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="modaldemo6">
        <form id="myForm" action="" method="POST"> <!-- Leave action empty for now -->
            @method('PUT')
            @csrf
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">تعديل القسم</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="id" name="id"> <!-- Hidden ID field -->

                        <div class="row mb-3">
                            <label for="title" class="col-sm-2 col-form-label">عنوان القسم:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="body" class="col-sm-2 col-form-label">الوصف:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="body" name="body" rows="4" required></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn ripple btn-primary" id="submitBtn" type="submit" >Save changes</button>
                        <button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
    <!-- Internal Data tables -->
    <script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
    <!--Internal  Datatable js -->
    <script src="{{URL::asset('assets/js/table-data.js')}}"></script>
    <!--Internal  Datepicker js -->
    <script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
    <!-- Internal Select2 js-->
    <script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <!-- Internal Modal js-->
    <script src="{{URL::asset('assets/js/modal.js')}}"></script>

    <!--Internal  Datepicker js -->
    <script src="{{URL::asset('assets/plugins/jquery-ui/ui/widgets/datepicker.js')}}"></script>
    <!-- Internal Select2 js-->
    <script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/rating/ratings.js')}}"></script>
    <!--Internal  Sweet-Alert js-->
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-alert.js')}}"></script>
    <!-- Sweet-alert js  -->
    <script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{URL::asset('assets/js/sweet-alert.js')}}"></script>

    <script>
        $('#modaldemo6').on('show.bs.modal', function(event) {
            let button = $(event.relatedTarget); // Button that triggered the modal
            let id = button.data('id'); // Extract data-id
            let name = button.data('name'); // Extract data-name
            let body = button.data('body'); // Extract data-body

            let modal = $(this);
            modal.find('.modal-body #id').val(id); // Set the hidden ID field
            modal.find('.modal-body #name').val(name); // Set the title field
            modal.find('.modal-body #body').val(body); // Set the body field (textarea)

            // Set the action URL for the form dynamically
            modal.find('form').attr('action', '{{ route("section.update", "") }}' + '/' + id);
        });
    </script>

