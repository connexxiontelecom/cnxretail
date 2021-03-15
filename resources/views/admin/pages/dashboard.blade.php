@extends('layouts.admin-layout')

@section('title')
    Admin Dashboard
@endsection
@section('page-name')
    Admin Dashboard
@endsection
@section('page-description')
    A summary of your transactions
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="route('admin.dashboard')}}">Dashboard</a>
</li>
@endsection

@section('page-heading')
    Admin Dashboard
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card proj-progress-card">
            <div class="card-block">
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <h6>Users</h6>
                        <h5 class="m-b-30 f-w-700">{{number_format($users->count())}}</h5>
                        <div class="progress">
                            <div class="progress-bar bg-c-red" style="width:100%"></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <h6>Tenants</h6>
                        <h5 class="m-b-30 f-w-700">{{number_format($tenants->count())}}</h5>
                        <div class="progress">
                            <div class="progress-bar bg-c-blue" style="width:100%"></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <h6>Overall Revenue</h6>
                        <h5 class="m-b-30 f-w-700">₦{{number_format($tenants->count() * 5500,2)}}</h5>
                        <div class="progress">
                            <div class="progress-bar bg-c-green" style="width:100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-12">
        <div class="card table-card">
            <div class="card-header">
                <h5>Latest 5 Tenants</h5>
                <div class="card-header-right">
                </div>
            </div>
            <div class="card-block">
                <div class="table-responsive">
                    <table class="table table-hover m-b-0 without-header">
                        <tbody>
                            @foreach($tenants->take(5) as $tenant)
                                <tr>
                                    <td>
                                        <div class="d-inline-block align-middle">
                                            <div class="d-inline-block">
                                                <h6><a href="">{{$tenant->company_name ?? ''}}</a></h6>
                                                <p class="text-muted m-b-0">{{$tenant->email ?? ''}}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <h6 class="f-w-700">{{$tenant->phone ?? '-' }}<i class="ti-clock text-c-red m-l-10"></i></h6>
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
@endsection

