@extends('layouts.app')

@section('content')

{{--    <subject-page--}}
{{--        v-bind:current_subject="{{json_encode($subject)}}"--}}
{{--    >--}}
{{--    </subject-page>--}}


    <subject-page
            v-bind:study_prop="{{json_encode($study)}}"
             v-bind:years_prop="{{json_encode($years)}}"
             v-bind:current_subject="{{json_encode($subject)}}"
             route_get_sections_by_subject="{{route('get_sections_by_subject')}}"
             route_get_files_by_section="{{route('get_files_by_section')}}"
             route_add_section="{{route('add_section')}}"
             route_edit_section="{{route('edit_section')}}"
             route_upload_file="{{route('upload_file')}}"
             route_base_images="{{url('/images/')}}"
             route_delete_file="{{route('delete_file')}}"
             route_delete_section="{{route('delete_section')}}"
             route_edit_subject="{{route('edit_subject')}}"
    ></subject-page>

    <planner-tag
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
        route_base_images="{{url('/images/')}}"
    >
    </planner-tag>
<div  class="container agenda-page subject-page tag-todo-list">
        <todo-list-tag
            route_add_task="{{route('add_task')}}"
            route_get_subjects_by_user="{{route('get_subjects_by_user')}}"
            route_get_tasks_by_user="{{route('get_tasks_by_user')}}"
            route_delete_task="{{route('delete_task')}}"
            route_task_done="{{route('task_done')}}"
            route_edit_task="{{route('edit_task')}}"
            route_get_tasks_by_subject="{{route('get_tasks_by_subject')}}"
            v-bind:subject_prop="{{json_encode($subject)}}"
            v-bind:page_type="{{json_encode($page_type)}}"
            route_base_images="{{url('/images/')}}"
        ></todo-list-tag>
</div>


@endsection

