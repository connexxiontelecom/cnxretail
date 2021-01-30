<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\FileModel;
use App\Models\ActivityLog;

class driveController extends Controller
{
    //


    public function createFolder(Request $request){

        $this->validate($request,[
            'folder_name'=>'required',
            'parent'=>'required',
            'visibility'=>'required'
        ]);
        $check = Folder::where('name', $request->folder_name)->where('parent_id', $request->parent)->get();
        if (count($check) == 0)
        {
        $folder = new Folder;
        $folder->name = $request->folder_name;
        $folder->created_by = $request->id;
        $folder->tenant_id = $request->tenant;
        $folder->parent_id = $request->parent;
        $folder->permission = $request->visibility;
        $folder->slug = substr(sha1(time()),27,40);
        $folder->save();
        #log
        $log = new ActivityLog;
        $log->subject = "New folder";
        $log->tenant_id = $request->tenant;
        $log->log = $request->full_name." created a new folder name: ".$request->folder_name;
        $log->save();
        return response()->json(['response'=>'success']);
        }
        else{
            return response()->json(['response'=>'exists']);
        }

    }


    public function getFolders(Request $request){

        $folders = Folder::where('tenant_id', $request->tenant_id)->where('parent_id', 0)->get();
        return response()->json(["folders" => $folders], 200);
    }


    public function getFiles(Request $request){

        $files = FileModel::where('tenant_id', $request->tenant_id)->where('folder_id', 0)->get();
        $files =  $this->parseUrl($files, "filename");
        return response()->json(["files" => $files], 200);

    }


    public function getContents(Request $request)
    {
        $tenant = $request->tenant_id;
        $folder = $request->folder_id;
        $folders = Folder::where('tenant_id', $tenant)->where('parent_id', $folder)->get();
        $files = FileModel::where('tenant_id', $tenant)->where('folder_id', $folder)->get();
        $files = $this->parseUrl($files, "filename");
        return response()->json(["files" => $files, "folders" => $folders], 200);
    }


    public function parseUrl($collection, $key)
    {
        foreach ($collection as $item) {
            $item[$key] = url("/assets/uploads/cnxdrive/" . $item[$key]);
        }
        return $collection;
    }


    public function uploadFile(Request $request){
        $this->validate($request,[
            'file'=>'required',
            'file_name'=>'required'
        ]);
        if(!empty($request->file('file'))){
            $extension = $request->file('file');
            $extension = $request->file('file')->getClientOriginalExtension();
            $size = $request->file('file')->getSize();
            $dir = 'assets/uploads/cnxdrive/';
            $filename = uniqid().'_'.time().'_'.date('Ymd').'.'.$extension;
            $request->file('file')->move(public_path($dir), $filename);
        }else{
            $filename = '';
        }
        $file = new FileModel;
        $file->tenant_id = $request->tenant;
        $file->uploaded_by = $request->id;
        $file->filename = $filename;
        $file->name = $request->file_name;
        $file->folder_id = $request->folder;
        $file->slug = substr(sha1(time()),27,40);
        $file->save();

        #log
        $log = new ActivityLog;
        $log->subject = "File upload";
        $log->tenant_id = $request->tenant;
        $log->log = $request->full_name." uploaded ".$request->file_name." file.";
        $log->save();

        return response()->json(['response'=>'success']);
    }


}
