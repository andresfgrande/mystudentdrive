<?php

namespace App\Http\Controllers\vuejs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UploadPhotoController extends Controller
{
    public function uploadProfilePhoto(Request $request){


        try{
            $this->validate($request, [
                'image'  => 'required|image|mimes:jpg,png,gif|max:10240'
            ]);
        } catch (\Exception $exception) {
            //return Response::json(array('success'=>false,'result'=>"heyyyyyy"));
            return Response::json(array('success'=>false,'result'=>"img_validation_file"));
        }

        $image = $request->file('image');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/profile'), $new_name);



//        $response = json_encode(array('new_name'=>$new_name, 'msg'=>'img_upload_ok'));

        return Response::json(array('success'=>false,'result'=>'img_upload_ok','result_img'=>$new_name));
    }


}
