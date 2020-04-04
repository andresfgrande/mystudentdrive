<?php

namespace App\Http\Controllers\vuejs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class UploadPhotoController extends Controller
{
    public function uploadProfilePhoto(Request $request){
        $user = Auth::User();
        try{
            $this->validate($request, [
                'image'  => 'required|image|mimes:jpg,png,gif|max:10240'
            ]);
        } catch (\Exception $exception) {
            return Response::json(array('success'=>false,'result'=>"img_validation_file"));
        }
        //dd(public_path('images/profile')."/"."$user->photo");
        $image = $request->file('image');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/profile'), $new_name);

        if($user->photo != null){
            unlink(public_path('images/profile')."/"."$user->photo");
        }
        $user->photo = $new_name;
        $user->save();

        return Response::json(array('success'=>false,'result'=>'img_upload_ok','result_img'=>$new_name));
    }

    public function deleteProfilePhoto(Request $request){
        $user = Auth::User();
        if($user->photo != null){
            unlink(public_path('images/profile')."/"."$user->photo");
            $user->photo = null;
            $user->save();
        }else{
            return Response::json(array('success'=>true,'result'=>'no_photo_found'));
        }

        return Response::json(array('success'=>true,'result'=>'deleted_photo_ok','name'=>$user->name,'surnames'=>$user->surnames));
    }

}
