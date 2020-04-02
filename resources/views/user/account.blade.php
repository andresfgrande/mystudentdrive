@extends('layouts.app')

@section('content')

    <div class="container">
        <h2 class="page-title">Mi cuenta</h2>

    </div>


{{--aqui envio nombre de foto de la BD--}}
    <profile-photo route_profile_photo="{{url('/images/profile/bob.jpg')}}"
                   route_upload_photo="{{route('uploadphoto')}}"
                   route_base_photo="{{url('/images/profile/')}}">
    </profile-photo>

    <br>
    <my-account user_name="{{ Auth::user()->name}}"
                user_id="{{ Auth::user()->id}}"
                user_email="{{ Auth::user()->email}}"
                user_surnames="{{ Auth::user()->surnames}}"
                route_edit_user_account="{{route('account.update',['account' => Auth::user()->id ])}}"
    ></my-account>

    <br>

    <change-password route_update_password="{{route('updatePass')}}">

    </change-password>

{{--    <div class="container">--}}
{{--        <div class="row justify-content-center">--}}

{{--            <div class="col-md-10">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-header">Dashboard</div>--}}

{{--                    <div class="card-body">--}}
{{--                        @if (session('status'))--}}
{{--                            <div class="alert alert-success" role="alert">--}}
{{--                                {{ session('status') }}--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                       <p>Mi Perfil   {{ Auth::user()->name }}</p>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
