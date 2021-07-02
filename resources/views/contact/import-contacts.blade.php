@extends('layouts.master-layout')

@section('title')
    Import Contacts
@endsection
@section('page-name')
    Import Contacts
@endsection
@section('page-description')
    Do bulk upload of your contacts
@endsection

@section('page-link')
    <li class="breadcrumb-item"><a href="{{url()->current()}}">Import Contacts</a>
    </li>
@endsection

@section('page-heading')
    Import Contacts
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12">

            <form class="form-material" action="{{route('import-contacts')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center mb-2 error-wrapper">
                        <ul id="validation-errors">
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="">Choose File</label>
                                    <input type="file" name="attachment" id="attachment" class="form-control-file">
                                    @error('attachment')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="">Download Sample File</label>
                                    <a href="/assets/sample.xlsx" target="_blank" class="btn btn-danger btn-sm"> <i class="ti-import mr-2"></i> Download Sample File</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-12 col-lg-12 col-md-12">
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
