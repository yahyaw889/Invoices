@extends('layouts.master')
@section('css')
<!--Internal  Font Awesome -->
<link href="{{URL::asset('assets/plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
<!--Internal  treeview -->
<link href="{{URL::asset('assets/plugins/treeview/treeview-rtl.css')}}" rel="stylesheet" type="text/css" />
@section('title')
تعديل الصلاحيات - مورا سوفت للادارة القانونية
@stop
@endsection
@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">الصلاحيات</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل
                الصلاحيات</span>
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


<form action="{{route('roles.update' , $role->id)}}" method="post">
    @method('PATCH')
    @csrf

<!-- row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mg-b-20">
            <div class="card-body">
                <div class="main-content-label mg-b-5">
                    <div class="form-group">
                        <p>اسم الصلاحية :</p>
{{--                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}--}}
                        <form action="" method="post">
                            <input type="text" name="name" value="{{$role->name}}" placeholder="NAME" class="form-control">
                        </form>
                    </div>
                </div>
                <div class="row">
                    <!-- col -->
                    <div class="col-lg-12 my-2">
                        <ul id="treeview1">
                            <a href="#">الصلاحيات</a>
                            <ul class="my-3">
                                <li class="col-12">
                                    @foreach($permission as $value)
                                        <label class="col-4 d-inline-block">
                                            <input type="checkbox" name="permission[]" value="{{ $value->id }}"
                                                   class="name" {{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
                                            {{ $value->name }}
                                        </label>
                                    @endforeach
                                </li>
                            </ul>
                        </ul>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                        <button type="submit" class="btn btn-main-primary">تحديث</button>
                    </div>
                    <!-- /col -->
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
</form>
@endsection
@section('js')
<!-- Internal Treeview js -->
<script src="{{URL::asset('assets/plugins/treeview/treeview.js')}}"></script>
@endsection
