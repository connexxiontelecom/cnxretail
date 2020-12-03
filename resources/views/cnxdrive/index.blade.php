@extends('layouts.master-layout')

@section('title')
    CNXDrive
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="/assets/css/datatable.min.css">
@endsection
@section('page-name')
CNXDrive
@endsection
@section('page-description')
    A list of all your directories
@endsection

@section('page-link')
<li class="breadcrumb-item"><a href="{{url()->current()}}">CNXDrive</a>
</li>
@endsection

@section('page-heading')
CNXDrive
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
                <div class="btn-group">
                    <button class="btn btn-mini btn-warning"  data-toggle="modal" data-target="#fileModal" type="button"><i class="ti-cloud-up mr-2"></i> Upload File</button>
                    <button class="btn btn-mini btn-primary" data-toggle="modal" data-target="#folderModal" type="button"><i class="ti-plus mr-2"></i>New Folder</button>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            @foreach ($folders->where('parent_id', 0) as $folder)
                <div class="col-sm-1 col-md-1 col-lg-1 col-xl-1">
                    <a href="{{route('open-folder', $folder->slug)}}" style="cursor: pointer;">
                        <img src="/assets/images/formats/folder.png" height="64" width="64" alt="">
                        {{$folder->name ?? ''}}
                    </a>
                </div>
            @endforeach
            @foreach ($files->where('folder_id', 0) as $file)
                @switch(pathinfo($file->filename, PATHINFO_EXTENSION))
                    @case('pptx')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}" style="cursor: pointer;">
                            <img src="/assets/images/formats/ppt.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>

                        @break
                    @case('pdf')
                    <div class="col-md-1 mb-4">
                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}" style="cursor: pointer;">
                            <img src="/assets/images/formats/pdf.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"> <br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break

                    @case('csv')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/csv.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('xls')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/xls.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('xlsx')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/xls.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('doc')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/doc.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                @if (file_exists(public_path("/assets/uploads/cnxdrive/".$file->filename)))
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                @endif
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('docx')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/doc.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('jpeg')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/jpeg.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('jpg')
                        <div class="col-md-1">
                            <a href="javascript:void(0);" style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                <img src="/assets/images/formats/jpg.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                            </a>
                            <div class="dropdown-secondary dropdown float-right">
                                <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                    <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                </div>
                            </div>
                        </div>
                    @break
                    @case('png')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/png.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('gif')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/gif.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('ppt')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/ppt.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('txt')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/txt.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('css')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/css.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"> <br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('mp3')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/mp3.png" height="64" width="64" alt=""><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('mp4')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/mp4.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('svg')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/svg.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('xml')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/xml.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                    @case('zip')
                    <div class="col-md-1">
                        <a href="javascript:void(0);" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                            <img src="/assets/images/formats/zip.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                        </a>
                        <div class="dropdown-secondary dropdown float-right">
                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                <a class="dropdown-item waves-light waves-effect" href="/assets/uploads/cnxdrive/{{$file->filename}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                <a class="dropdown-item waves-light waves-effect shareFile" data-toggle="modal" data-target="#shareFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-sharethis text-warning mr-2"></i> Share</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item waves-light waves-effect deleteFile" data-toggle="modal" data-target="#deleteFileModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    @break
                @endswitch
            @endforeach
        </div>
    </div>
</div>
<div class="modal fade" id="folderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create New Folder</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="createFolderForm" class="form-material">
            <div class="form-group form-primary form-static-label">
                <input type="text" name="folder_name" class="form-control">
                <span class="form-bar"></span>
                <label class="float-label">Folder Name</label>
            </div>
            <div class="form-group form-primary form-static-label">
                <select id="parent_folder" name="parent_folder" class="form-control form-control-inverse fill">
                    <option selected disabled>Select parent folder</option>
                    <option value="0">None</option>
                    @foreach ($folders as $folder)
                         <option value="{{$folder->id}}">{{$folder->name ?? ''}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group form-primary form-static-label">
                <select id="visibility" name="visibility" class="form-control form-control-inverse fill">
                    <option selected disabled>Select visibility</option>
                    <option value="1">Private</option>
                    <option value="2">Public</option>
                </select>
            </div>
            <div class="form-group d-flex justify-content-center">
                <button type="button" class="btn btn-secondary btn-mini" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-mini">Create Folder</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Upload File</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="uploadFileForm" class="form-material">
            <div class="form-group form-primary form-static-label">
                <input type="text" name="file_name" class="form-control">
                <span class="form-bar"></span>
                <label class="float-label">File Name</label>
            </div>
            <div class="form-group form-primary form-static-label">
                <input type="file" name="file" class="form-control">
                <span class="form-bar"></span>
            </div>
            <input type="hidden" name="folder" value="0" class="form-control">
            <div class="form-group d-flex justify-content-center">
                <button type="button" class="btn btn-secondary btn-mini" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-mini">Upload file</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('extra-scripts')
<script>
    $(document).ready(function(){
        $('.error-wrapper').hide();
            createFolderForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/cnxdrive/create-folder',new FormData(createFolderForm))
                .then(response=>{
                    Toastify({
                        text: "Success! New folder created.",
                        duration: 3000,
                        newWindow: true,
                        close: true,
                        gravity: "top",
                        position: 'right',
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        stopOnFocus: true,
                        onClick: function(){}
                    }).showToast();
                    window.location.replace(response.data.route);
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
            uploadFileForm.onsubmit = async (e) => {
                e.preventDefault();
                axios.post('/cnxdrive/upload-file',new FormData(uploadFileForm))
                .then(response=>{
                    Toastify({
                        text: "Upload success",
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
    });
</script>
@endsection
