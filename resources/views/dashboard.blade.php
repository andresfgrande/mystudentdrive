@extends('layouts.app')

@section('content')
    <div class="container content dashboard">
        <div class="row">
            <div class="col-sm">
                One of four columns

            </div>
            <div class="col-sm">
                <planner-tag-dashboard
                    v-bind:study_prop="{{json_encode($study)}}"
                    route_add_event="{{route('add_event')}}"
                    route_get_events="{{route('get_events')}}"
                    route_get_subjects_by_user="{{route('get_subjects_by_user')}}"
                    route_edit_event="{{route('edit_event')}}"
                    route_delete_event="{{route('delete_event')}}"
                    route_update_old_events="{{route('update_old_events')}}"
                    route_get_events_by_study="{{route('get_events_by_study')}}"
                    v-bind:page_type="{{json_encode($page_type)}}"
                    route_get_events_by_subject="{{route('get_events_by_subject')}}"
                    v-bind:subject_prop="{{json_encode($subject)}}"
                >
                </planner-tag-dashboard>
            </div>
            <div class="col-sm">
                One of four columns

            </div>
            <div class="col-sm">
                One of four columns

            </div>
        </div>
    </div>
@endsection
