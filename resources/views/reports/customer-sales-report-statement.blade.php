@extends('layouts.master-layout')

@section('title')
    Customer Sales Report Statement
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Customer Sales Report Statement
@endsection
@section('page-description')
Customer Sales Report Statement
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Customer Sales Report Statement</a>
</li>
@endsection

@section('page-heading')
Customer Sales Report Statement
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
            <div class="col-xl-12">

            </div> <!-- Section filter-->
            <div class="col-md-12 col-sm-12">
                <div class="card-header mb-3">
                    <h5 class="text-uppercase">Filter
                    </h5>
                    <div class="card-header-right">
                    </div>
                </div>
               <form  class="form-inline" >
                   @csrf
                <div class="form-group">
                    <label for="">Contact</label>
                    <select name="contact" id="contact" class="form-control">
                        <option selected disabled>Select contact</option>
                        @foreach ($contacts as $contact)
                            <option value="{{$contact->id}}">{{$contact->company_name ?? ''}}</option>
                        @endforeach
                    </select>
                    @error('contact')
                        <i class="text-danger">{{$message}}</i>
                    @enderror
                </div>
                <div class="form-group">
                    <button class="btn-primary btn " type="submit">Filter</button>
                </div>
               </form>
            </div>
        </div>
        <div class="card-header mb-3">
            <h5 class="text-uppercase">Customer Sales Report Statement
            </h5>
            <div class="card-header-right">
            </div>
        </div>
        <div class="dt-responsive table-responsive" id="statement">

        </div>
    </div>
</div>
@endsection

@section('extra-scripts')
<script src="/assets/js/datatable.min.js"></script>
<script>
    $(document).ready(function(){
        $('.simpletable').DataTable();

        $(document).on('change', '#contact', function(e){
            e.preventDefault();

            axios.post('/customer-sales-report-statement',{contact:$(this).val()})
            .then(response=>{
                $('#statement').html(response.data);
            });
        });
    });
</script>
@endsection
