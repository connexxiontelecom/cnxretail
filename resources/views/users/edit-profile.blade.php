@extends('layouts.master-layout')

@section('title')
    Edit {{Auth::user()->full_name ?? ''}}'s Profile
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Edit  {{Auth::user()->full_name ?? ''}}'s Profile
@endsection
@section('page-description')
Edit {{Auth::user()->full_name ?? ''}}'s Profile
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Edit {{Auth::user()->full_name ?? ''}}'s Profile</a>
</li>
@endsection

@section('page-heading')
Edit {{Auth::user()->full_name ?? ''}}'s Profile
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12 col-md-12">
        @if (session()->has('error'))
            <div class="alert alert-warning background-warning">
                {!! session()->get('error') !!}
            </div>
        @endif

    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <form action="{{route('edit-profile')}}" method="post">
            @csrf
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
                                                         <td>
                                                             <input type="text" name="full_name" class="form-control" placeholder="Full Name" value="{{old('full_name',Auth::user()->full_name)}}">
                                                             @error('full_name')
                                                                 <i class="text-danger mt-2">{{$message}}</i>
                                                             @enderror
                                                         </td>
                                                     </tr>
                                                     <tr>
                                                         <th scope="row">Gender</th>
                                                         <td>
                                                             <select name="gender" id="gender" class="form-control">
                                                                 <option selected disabled>--Select gender--</option>
                                                                 <option value="1">Male</option>
                                                                 <option value="2">Female</option>
                                                             </select>
                                                             @error('gender')
                                                                 <i class="text-danger mt-2">{{$message}}</i>
                                                             @enderror
                                                         </td>
                                                     </tr>
                                                     <tr>
                                                         <th scope="row">Marital Status</th>
                                                         <td>
                                                             <select name="marital_status" id="marital_status" class="form-control">
                                                                 <option disabled selected>--Select marital status</option>
                                                                 <option value="1">Single</option>
                                                                 <option value="2">Married</option>
                                                                 <option value="3">Divorce</option>
                                                                 <option value="4">Separated</option>
                                                             </select>
                                                             @error('marital_status')
                                                                 <i class="text-danger mt-2">{{$message}}</i>
                                                             @enderror
                                                         </td>
                                                     </tr>
                                                     <tr>
                                                         <th scope="row">Address</th>
                                                         <td>
                                                             <textarea name="address" id="address" placeholder="Address" class="form-control">{{old('address', Auth::user()->address)}}</textarea>
                                                            @error('address')
                                                                 <i class="text-danger mt-2">{{$message}}</i>
                                                             @enderror
                                                        </td>
                                                     </tr>
                                                 </tbody>
                                             </table>
                                         </div>
                                     </div>
                                     <div class="col-lg-12 col-xl-6">
                                         <div class="table-responsive">
                                             <table class="table">
                                                 <tbody>
                                                     <tr>
                                                         <th scope="row">Email</th>
                                                         <td>
                                                             <input class="form-control" name="email" type="text" class="form-control" placeholder="Email " value="{{old('email', Auth::user()->email)}}">
                                                             @error('email')
                                                                 <i class="text-danger mt-2">{{$message}}</i>
                                                             @enderror
                                                            </td>
                                                     </tr>
                                                     <tr>
                                                         <th scope="row">Mobile Number</th>
                                                         <td>
                                                             <input type="text" placeholder="Mobile Number" name="mobile_no" id="mobile_no" value="{{old('mobile_no', Auth::user()->mobile_no)}}" class="form-control">
                                                            @error('mobile_no')
                                                                 <i class="text-danger mt-2">{{$message}}</i>
                                                             @enderror
                                                            </td>
                                                     </tr>
                                                 </tbody>
                                             </table>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <hr>
                 <div class="row">
                     <div class="col-md-12 col-sm-12 d-flex justify-content-center">
                         <div class="btn btn-group">
                             <button type="submit" class="btn btn-mini btn-primary"><i class="ti-check mr-2"></i> Save changes</button>
                         </div>
                     </div>
                 </div>
         </div>

        </form>
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
