<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(){
//        $servicios =  DB::table('servicios')->get();
//        $pagina_actual = 'servicios';
        if (Auth::check()) {
            // The user is logged in...
            return view('user.account');
        }
        return view('auth.login');

    }


    public function show(){
        return 'show';
    }
    public function store(){
        return 'store';
    }

    public function create(){
        return 'create';
    }
    public function update(Request $request, $accountId){

        $user = Auth::User();

        if($request->params['is_email'] !== true){ //se edita nombres

            $request->request->add(['name' => $request->params['name']]);
            $request->request->add(['surnames' => $request->params['surnames']]);
            try {
                $this->validate($request, [
                    'name' => 'required|string',
                    'surnames' => 'required|string',
                ]);
            } catch (ValidationException $e) {
                return Response::json(array('success'=>false,'result'=>'validation_error_name'));
            }

            try {

                $user->name = $request->params['name'];
                $user->surnames = $request->params['surnames'];
                $user->save();

            } catch (\Exception $exception) {
                return Response::json(array('success'=>false,'result'=>'no se ha podido editar el nombre'));
            }
            return Response::json(array('success'=>true,'result'=>'nombre editado ok'));

        }else{ //se edita email

            $request->request->add(['email' => $request->params['email']]);

            $hashedPassword = $user->getAuthPassword();
            if (Hash::check($request->params['password'], $hashedPassword)) { //contraseña correcta

                if( $request->params['email'] != $user->email){ //mail es diferente al actual
                    try {
                        $this->validate($request, [
                            'email' => 'required|email|unique:users',
                        ]);
                    } catch (ValidationException $e) {
                        return Response::json(array('success'=>false,'result'=>'mail_exists_fail'));
                    }
                    try {
                        $user = Auth::User();
                        $user->email = $request->params['email'];
                        $user->save();

                    } catch (\Exception $exception) {
                        return Response::json(array('success'=>true,'result'=>'edit_mail_fail'));
                    }
                    return Response::json(array('success'=>true,'result'=>'edit_mail_ok'));
                }else{ //mail es el mismo al actual
                    return Response::json(array('success'=>true,'result'=>'mantenido_mail_ok'));
                }

            }else{ //contraseña incorrecta
                return Response::json(array('success'=>false,'result'=>'pass_fail'));
            }
        }


    }
    public function destroy(){
        return 'destroy';
    }
    public function delete(){
        return 'delete';
    }
}
