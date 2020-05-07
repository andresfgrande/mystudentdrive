@extends('layouts.app')

@section('content')

{{--    <div class="container">--}}
{{--        <h2 class="page-title">Nombre del grado estudio</h2>--}}
{{--    </div>--}}

    <study
        v-bind:study_prop="{{json_encode($study)}}"
        v-bind:years_prop="{{json_encode($years)}}"
    >

    </study>

@endsection
