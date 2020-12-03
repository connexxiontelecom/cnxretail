<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FileModel;
use App\Models\Folder;
use App\Models\ActivityLog;
use Auth;

class CNXDriveController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $folders = Folder::where('tenant_id', Auth::user()->tenant_id)->get();
        $files = FileModel::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('cnxdrive.index',['folders'=>$folders, 'files'=>$files]);
    }


    public function createFolder(Request $request){
        $this->validate($request,[
            'folder_name'=>'required',
            'parent_folder'=>'required',
            'visibility'=>'required'
        ]);
        $folder = new Folder;
        $folder->name = $request->folder_name;
        $folder->created_by = Auth::user()->id;
        $folder->tenant_id = Auth::user()->tenant_id;
        $folder->parent_id = $request->parent_folder;
        $folder->permission = $request->visibility;
        $folder->slug = substr(sha1(time()),27,40);
        $folder->save();
        #log
        $log = new ActivityLog;
        $log->subject = "New folder";
        $log->tenant_id = Auth::user()->tenant_id;
        $log->log = Auth::user()->full_name." created a new folder name: ".$request->folder_name;
        $log->save();
        return response()->json(['route'=>'cnxdrive']);
    }

    public function openFolder($slug){

        $directory = Folder::where('tenant_id', Auth::user()->tenant_id)->where('slug', $slug)->first();
        if(!empty($directory)){
            $folders = Folder::where('tenant_id', Auth::user()->tenant_id)->get();
            $files = FileModel::where('tenant_id', Auth::user()->tenant_id)->where('folder_id', $directory->id)->get();
            return view('cnxdrive.view',['folders'=>$folders,'directory'=>$directory, 'files'=>$files]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> ");
            return back();
        }
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
        $file->tenant_id = Auth::user()->tenant_id;
        $file->uploaded_by = Auth::user()->id;
        $file->filename = $filename;
        $file->name = $request->file_name;
        $file->folder_id = $request->folder;
        $file->slug = substr(sha1(time()),27,40);
        $file->save();

        #log
        $log = new ActivityLog;
        $log->subject = "File upload";
        $log->tenant_id = Auth::user()->tenant_id;
        $log->log = Auth::user()->full_name." uploaded ".$request->file_name." file.";
        $log->save();
    }



}
