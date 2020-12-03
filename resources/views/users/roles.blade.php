@extends('layouts.master-layout')

@section('title')
    Roles
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Roles
@endsection
@section('page-description')
    A list of all roles
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">All Roles</a>
</li>
@endsection

@section('page-heading')
    All Roles
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
            <div class="col-md-12 col-sm-12">
                <button type="button" data-toggle="modal" data-target="#roleModal" class="btn btn-primary btn-mini"><i class="ti-plus mr-2"></i>Add New Role</button>
            </div>
        </div>
        <div class="dt-responsive table-responsive">
            <table  class="table table-striped table-bordered nowrap simpletable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Role Name</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                @php
                    $n = 1;
                @endphp
                @foreach ($roles as $role)
                    <tr>
                        <td>{{$n++}}</td>
                        <td>{{$role->name ?? ''}}</td>
                        <td>{{date('d F, Y', strtotime($role->created_at))}}</td>
                        <td>
                            <a href="{{route('assign-permissions', $role->id)}}" class="btn btn-secondary btn-mini"> <i class="ti-lock mr-2"></i> Assign Permission</a>
                        </td>
                    </tr>
                @endforeach
                <tbody>
                </tfoot>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="roleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="exampleModalLabel">Add New Role</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="roleForm" class="form-material">
            <div class="form-group form-primary form-static-label">
                <input type="text" name="role_name" placeholder="Role Name" id="role_name" class="form-control">
                <span class="form-bar"></span>
                <label class="float-label">Role Name</label>
            </div>
              <hr>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="btn-group d-flex justify-content-center">
                        <button class="btn btn-danger btn-mini" data-dismiss="modal"><i class="ti-close mr-2"></i> Close</button>
                        <button class="btn btn-primary btn-mini" type="submit" id="confirmSelection"><i class="ti-check mr-2"></i> Submit</button>
                    </div>
                </div>
            </div>
          </form>
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
    });

    roleForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/add-new-role',new FormData(roleForm))
                .then(response=>{
                    Toastify({
                        text: "Success! New role saved.",
                        duration: 3000,
                        newWindow: true,
                        close: true,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        stopOnFocus: true,
                        onClick: function(){}
                    }).showToast();
                    location.reload();
                })
                .catch(error=>{
                        $('#validation-errors').html('');
                        $.each(error.response.data.errors, function(key, value){
                            Toastify({
                            text: value,
                            duration: 3000,
                            newWindow: true,
                            close: true,
                            gravity: "top",
                            position: 'right',
                            backgroundColor: "linear-gradient(to right, #FF0000, #FE0000)",
                            stopOnFocus: true,
                            onClick: function(){}
                        }).showToast();
                        $('#validation-errors').append("<li><i class='ti-hand-point-right text-danger mr-2'></i><small class='text-danger'>"+value+"</small></li>");
                    });
                });
            };
</script>
@endsection
