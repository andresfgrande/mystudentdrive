@extends('layouts.app')

@section('content')

<schedules
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
    route_photo_2="{{url("/images/content/clip-2_v2.png")}}"
    route_get_schedules_by_period="{{route("get_schedules_by_period")}}"
    route_get_classes_by_schedule_and_day="{{route("get_classes_by_schedule_and_day")}}"
    route_get_recent_schedule_by_user="{{route("get_recent_schedule_by_user")}}"
    route_add_schedule="{{route("add_schedule")}}"
    route_add_classe="{{route("add_classe")}}"
    route_get_subjects_by_period="{{route("get_subjects_by_period")}}"
    route_edit_classe="{{route("edit_classe")}}"
    route_delete_classe="{{route("delete_classe")}}"
></schedules>

@endsection
