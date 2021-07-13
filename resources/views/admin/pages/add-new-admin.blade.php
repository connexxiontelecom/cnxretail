@extends('layouts.admin-layout')

@section('title')
    Add New Admin User
@endsection
@section('page-name')
Add New Admin User
@endsection
@section('page-description')
   Admin User
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Admin User</a>
</li>
@endsection

@section('page-heading')
    Admin User
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">

        <form class="form-material" id="addNewContactForm" method="post" action="{{route('admin.add.new.admin.user')}}">
            @csrf
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center mb-2 error-wrapper">
                    @if (session()->has('success'))
                        <div class="alert alert-success background-success">{!! session()->get('success') !!}</div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <div class="card-header mb-4">
                        <h5>Credentials</h5>
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="full_name" id="full_name" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Full Name</label>
                        @error('full_name')
                            <i class="text-danger">{{$message}}</i>
                        @enderror
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="text" name="email" id="email" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Email</label>
                        @error('email')
                            <i class="text-danger">{{$message}}</i>
                        @enderror
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="password" name="password" id="password" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Choose Password</label>
                        @error('password')
                            <i class="text-danger">{{$message}}</i>
                        @enderror
                    </div>
                    <div class="form-group form-primary form-static-label">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                        <span class="form-bar"></span>
                        <label class="float-label">Re-type Password</label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-6 col-lg-6 col-md-6">
                    <div class="btn-group d-flex justify-content-center">
                        <a href="" class="btn btn-danger btn-mini"> <i class="ti-close mr-2"></i> Cancel</a>
                        <button type="submit"  class="btn btn-primary btn-mini save-contact"> <i class="ti-check mr-2"></i> Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('extra-scripts')

@endsection
