<?php

namespace App\Http\Controllers\vuejs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class UpdatePasswordController extends Controller
{
    public function updatePass(Request $request){

        $currentPassword = $request->params['current_password'];
        $newPassword = $request->params['new_password'];
        $confirmPassword = $request->params['confirm_password'];

        $user = Auth::User();
        $hashedPassword = $user->getAuthPassword();

        if (Hash::check($currentPassword, $hashedPassword)) { //contraseña correcta

            if(Hash::check($newPassword, $hashedPassword)){
                return Response::json(array('success'=>false,'result'=>'new_current_equals_fail'));
            }else{
                if( $newPassword == $confirmPassword){ //contraseñs coinciden
                    $user->password = bcrypt($newPassword);
                    $user->save();
                    return Response::json(array('success'=>true,'result'=>'uptade_pass_ok'));
                }else{
                    return Response::json(array('success'=>false,'result'=>'pass_not_equals'));
                }
            }
        }else{
            return Response::json(array('success'=>false,'result'=>'pass_fail'));
        }

    }
}
