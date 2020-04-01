<?php

namespace App\Http\Controllers\vuejs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UploadPhoto extends Controller
{
    public function uploadProfilePhoto(Request $request){


        try{
            $this->validate($request, [
                'image'  => 'required|image|mimes:jpg,png,gif|max:2048'
            ]);
        } catch (\Exception $exception) {
            return Response::json(array('success'=>false,'result'=>"heyyyyyy"));
            return Response::json(array('success'=>false,'result'=>"$exception"));
        }

        $image = $request->file('image');
        $new_name = rand() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/profile'), $new_name);

        return Response::json(array('success'=>false,'result'=>"$new_name"));

    }


}
