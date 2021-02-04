@extends('layouts.master-layout')

@section('title')
    {{Auth::user()->full_name ?? ''}}'s Profile
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
{{Auth::user()->full_name ?? ''}}'s Profile
@endsection
@section('page-description')
{{Auth::user()->full_name ?? ''}}'s Profile
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">{{Auth::user()->full_name ?? ''}}'s Profile</a>
</li>
@endsection

@section('page-heading')
{{Auth::user()->full_name ?? ''}}'s Profile
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
        @if (session()->has('error'))
            <div class="alert alert-warning background-warning">
                {!! session()->get('error') !!}
            </div>
        @endif
        @if (session()->has('success'))
            <div class="alert alert-success background-success">
                {!! session()->get('success') !!}
            </div>
        @endif

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
       <div class="card-block">
            <div class="view-info">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="general-info">
                            <div class="row">
                                <div class="col-lg-12 col-xl-6">
                                    <div class="table-responsive">
                                        <table class="table m-0">
                                            <tbody>
                                                <tr>
                                                    <td scope="row"><b>Full Name</b></td>
                                                    <td>{{Auth::user()->full_name ?? ''}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Gender</th>
                                                    <td>{{Auth::user()->gender == 1 ? 'Male' : 'Female'}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Member Since</th>
                                                    <td>{{!is_null(Auth::user()->created_at) ? date('d-m-Y', strtotime(Auth::user()->created_at)) : '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Marital Status</th>
                                                    <td>{{Auth::user()->marital_status == 1 ? 'Single' : 'Married'}}</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Address</th>
                                                    <td>{{Auth::user()->address ?? '-'}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- end of table col-lg-6 -->
                                <div class="col-lg-12 col-xl-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">Email</th>
                                                    <td><a href="#!">{{Auth::user()->email ?? '-'}}</a></td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Mobile Number</th>
                                                    <td>{{Auth::user()->mobile_no ?? '-'}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- end of table col-lg-6 -->
                            </div>
                            <!-- end of row -->
                        </div>
                        <!-- end of general info -->
                    </div>
                    <!-- end of col-lg-12 -->
                </div>
                <!-- end of row -->
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12 col-sm-12 d-flex justify-content-center">
                    <div class="btn btn-group">
                        <a href="{{route('edit-profile')}}" class="btn btn-mini btn-warning"><i class="ti-pencil mr-2"></i> Edit Profile</a>
                    </div>
                </div>
            </div>
    </div>
    </div>
</div>
@endsection

@section('extra-scripts')

<script>
    $(document).ready(function(){
        //$('.simpletable').DataTable();
    });
</script>
@endsection
