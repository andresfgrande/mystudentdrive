@extends('layouts.app')

@section('content')

    <div class="container">
        <h2 class="page-title">Mis studios</h2>
    </div>

    <studies
        v-bind:estudios="{{json_encode($estudios)}}"
        route_get_years_by_study="{{route('get_years_by_study')}}"
        route_get_subjects_by_year="{{route('get_subjects_by_year')}}"
        route_add_study="{{route('add_study')}}"
        route_add_year="{{route('add_year')}}"
        route_add_subject="{{route('add_subject')}}"
        route_add_period="{{route('add_period')}}"
        route_get_studies="{{route('get_studies_ajax')}}"
        route_get_periods_by_year="{{route('get_periods_by_year')}}"
        route_photo="{{url("/images/content/clip-list-is-empty.png")}}"
        route_photo_2="{{url("/images/content/clip-2.png")}}"
    ></studies>


@endsection
