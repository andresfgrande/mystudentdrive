@extends('layouts.app')

@section('content')

    <div class="container">
        <h2 class="page-title">Mis studios</h2>
    </div>

    <studies
        v-bind:estudios="{{json_encode($estudios)}}"
        route_get_years_by_study="{{route('get_years_by_study')}}"
        route_get_subjects_by_period="{{route('get_subjects_by_period')}}"
    ></studies>


@endsection
