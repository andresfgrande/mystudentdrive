@extends('layouts.app')

@section('content')

    <div class="container">
        <h2>Mi cuenta</h2>

    </div>

    <my-account user_name="{{ Auth::user()->name}}"
                user_id="{{ Auth::user()->id}}"
                user_email="{{ Auth::user()->email}}"
                user_surnames="{{ Auth::user()->surnames}}"
                route_edit_user_account="{{route('account.update',['account' => Auth::user()->id ])}}"
    ></my-account>

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
