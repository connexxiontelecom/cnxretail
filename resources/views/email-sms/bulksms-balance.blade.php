@extends('layouts.master-layout')

@section('title')
    Balance
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
    Balance
@endsection
@section('page-description')
    Balance
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Balance</a>
</li>
@endsection

@section('page-heading')
Balance
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
        @if (session()->has('error'))
            <div class="alert alert-warning background-warning">
                {!! session()->get('error') !!}
            </div>
        @endif
        <div class="row mb-3">
            <div class="col-md-12 col-sm-12 ">
                <a  href="{{route('buy-units')}}" class="btn btn-mini btn-primary"><i class="ti-plus mr-2"></i>Buy Units</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <h5 class="sub-title">Account Balance</h5>
                <table class="table table-stripped">
                    <tr>
                        <td><strong style="font-weight: 700;">Account Balance: </strong></td>
                        <td>{{number_format($transactions->sum('credit') - $transactions->sum('debit') ) }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-8 col-sm-8">
                <h5 class="sub-title">Transactions</h5>
                <div class="dt-responsive table-responsive">
                    <table  class="table table-striped table-bordered nowrap simpletable">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Amount</th>
                            <th>Units</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        @php
                            $i = 1;
                        @endphp
                        <tbody>
                        @foreach ($transactions as $trans)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{ number_format($trans->amount,2) }}</td>
                                <td>{{ number_format($trans->credit) }}</td>
                                <td>{{date('d F, Y', strtotime($trans->created_at))}}</td>
                            </tr>
                        @endforeach

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-scripts')
<script src="/assets/js/datatable.min.js"></script>
<script>
    $(document).ready(function(){
        $('.simpletable').DataTable();

        $(document).on('click', '.balance', function(e){
            e.preventDefault();
            axios.post('/sms/balance')
            .then(response=>{
                console.log(response.data);
            })
            .catch(error=>{
                console.log(error.response.errors);
            });
        });
    });
</script>
@endsection
