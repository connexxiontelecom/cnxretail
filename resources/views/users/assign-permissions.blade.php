@extends('layouts.master-layout')

@section('title')
    Assign Permission
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
Assign Permission
@endsection
@section('page-description')
    A list of all permissions
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">Assign Permission </a>
</li>
@endsection

@section('page-heading')
Assign Permissions to {{$role->name ?? ''}}
@endsection

@section('content')
<form action="{{route('assign-role-permissions')}}" method="post">
    @csrf
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
        <div class="row mb-3">
            <div class="col-md-12 col-sm-12">
                <button type="button" data-toggle="modal" data-target="#permissionModal" class="btn btn-primary btn-mini"><i class="ti-plus mr-2"></i>Add New Permission</button>
            </div>
        </div>
        <div class="row">
            @foreach ($permissions as $permission)
                <div class="col-md-2 col-sm-2">
                <div class="form-group">
                    <div class="form-check">
                      <input class="form-check-input" {{$role->hasPermissionTo($permission->id) ? 'checked' : ''}}   value="{{$permission->id}}" type="checkbox" name="permission[]">
                      <label class="form-check-label" for="gridCheck">
                        {{$permission->name ?? ''}}
                      </label>
                    </div>
                  </div>
                </div>
                @endforeach
                <input type="hidden" name="role" value="{{$role->id}}">
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="btn-group d-flex justify-content-center">
                    <button class="btn btn-danger btn-mini"><i class="ti-close mr-2"></i>Cancel</button>
                    <button class="btn btn-primary btn-mini" type="submit"><i class="ti-plus mr-2"></i>Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

@endsection

@section('extra-scripts')
<script src="/assets/js/datatable.min.js"></script>
<script>
    $(document).ready(function(){
        $('.simpletable').DataTable();
    });

    permissionForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/add-new-permission',new FormData(permissionForm))
                .then(response=>{
                    Toastify({
                        text: "Success! New permission saved.",
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
