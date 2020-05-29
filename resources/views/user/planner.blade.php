@extends('layouts.app')

@section('content')

<planner
    v-bind:planner_events="{{json_encode($planner_events)}}"
    route_add_event="{{route('add_event')}}"
    route_get_events="{{route('get_events')}}"
    route_get_subjects_by_user="{{route('get_subjects_by_user')}}"
    route_edit_event="{{route('edit_event')}}"
    route_delete_event="{{route('delete_event')}}"
    route_update_old_events="{{route('update_old_events')}}"
    route_get_events_by_date="{{route('get_events_by_date')}}"
    route_base_images="{{url('/images/')}}"
></planner>

@endsection
