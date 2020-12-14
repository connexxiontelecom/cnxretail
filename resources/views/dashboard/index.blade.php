@extends('layouts.master-layout')

@section('title')
    Dashboard
@endsection
@section('page-name')
    Dashboard
@endsection
@section('page-description')
    A summary of your transactions
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a>
</li>
@endsection

@section('page-heading')
    Dashboard
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card proj-progress-card">
            <div class="card-block">
                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <h6>Users</h6>
                        <h5 class="m-b-30 f-w-700">{{$users->count()}}</h5>
                        <div class="progress">
                            <div class="progress-bar bg-c-red" style="width:100%"></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <h6>Reminders</h6>
                        <h5 class="m-b-30 f-w-700">{{number_format($reminders->count())}}</h5>
                        <div class="progress">
                            <div class="progress-bar bg-c-blue" style="width:100%"></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <h6>Receipts</h6>
                        <h5 class="m-b-30 f-w-700">{{number_format($receipts->count())}}</h5>
                        <div class="progress">
                            <div class="progress-bar bg-c-green" style="width:100%"></div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <h6>Payments</h6>
                        <h5 class="m-b-30 f-w-700">{{number_format($payments->count())}}</h5>
                        <div class="progress">
                            <div class="progress-bar bg-c-yellow" style="width:100%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-12">
        <div class="card table-card">
            <div class="card-header">
                <h5>Reminders</h5>
                <div class="card-header-right">
                </div>
            </div>
            <div class="card-block">
                <div class="table-responsive">
                    <table class="table table-hover m-b-0 without-header">
                        <tbody>
                            @foreach ($reminders as $reminder)
                                <tr>
                                    <td>
                                        <div class="d-inline-block align-middle">
                                            <div class="d-inline-block">
                                                <h6>{{$reminder->getSetBy->full_name ?? ''}}</h6>
                                                <p class="text-muted m-b-0">{{$reminder->reminder_name ?? ''}}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <h6 class="f-w-700">{{date('d F, Y h:ia', strtotime($reminder->remind_at))}}<i class="ti-clock text-c-red m-l-10"></i></h6>
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="card text-center order-visitor-card">
                    <div class="card-block">
                        <h6 class="m-b-0">Revenue <small>(Inflow)</small> </h6>
                        <h6 class="m-t-15 m-b-15"><i class="fa fa-arrow-down m-r-15 text-c-green"></i>{{Auth::user()->tenant->currency->symbol ?? 'N'}}{{ number_format($receipts->sum('amount'),2) }}</h6>

                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center order-visitor-card">
                    <div class="card-block">
                        <h6 class="m-b-0">Expenses <small>(Outflow)</small></h6>
                        <h6 class="m-t-15 m-b-15"><i class="fa fa-arrow-up m-r-15 text-c-red"></i>{{Auth::user()->tenant->currency->symbol ?? 'N'}}{{ number_format($payments->sum('amount'),2) }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-c-red total-card">
                    <div class="card-block">
                        <div class="text-left">
                            <h6>{{Auth::user()->tenant->currency->symbol ?? 'N'}}{{ number_format($invoices->sum('total') - $invoices->sum('paid_amount'),2) }}</h6>
                            <p class="m-0">Unpaid Invoice</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-c-green total-card">
                    <div class="card-block">
                        <div class="text-left">
                            <h6>{{Auth::user()->tenant->currency->symbol ?? 'N'}}{{ number_format($bills->sum('bill_amount') - $bills->sum('paid_amount'),2) }}</h6>
                            <p class="m-0">Unpaid Bills</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

