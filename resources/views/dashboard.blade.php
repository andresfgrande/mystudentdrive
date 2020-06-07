@extends('layouts.app')

@section('content')
    <div class="container content dashboard">
        <div class="row">
            <div class="col-sm">

                <estudios-tag-dashboard
                    v-bind:chosed_year = "{{json_encode($recentYear)}}"
                    v-bind:index_chosen_year="{{$hey}}"
                    route_get_years_by_one_study="{{route('get_years_by_one_study')}}"
                    route_get_subjects_by_year="{{route('get_subjects_by_year')}}"
                    route_add_subject="{{route('add_subject')}}"
                    route_add_period="{{route('add_period')}}"
                    route_get_periods_by_year="{{route('get_periods_by_year')}}"
                    route_get_sections_by_subject="{{route('get_sections_by_subject')}}"
                    route_get_files_by_section="{{route('get_files_by_section')}}"
                    route_add_section="{{route('add_section')}}"
                    route_edit_section="{{route('edit_section')}}"
                    route_upload_file="{{route('upload_file')}}"
                    route_base_images="{{url('/images/')}}"
                    route_delete_file="{{route('delete_file')}}"
                    route_delete_section="{{route('delete_section')}}"
                    route_edit_subject="{{route('edit_subject')}}"
                    route_delete_subject="{{route('delete_subject')}}"
                ></estudios-tag-dashboard>


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
                    route_base_images="{{url('/images/')}}"
                >
                </planner-tag-dashboard>

            </div>
            <div class="col-2">

                <schedules-tag-dashboard
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
                    route_delete_schedule="{{route("delete_schedule")}}"
                    route_get_classes_dashboard="{{route("get_classes_dashboard")}}"
                    route_base_images="{{url('/images/')}}"
                ></schedules-tag-dashboard>


            </div>
            <div class="col-sm">
                <todo-list-tag
                    route_add_task="{{route('add_task')}}"
                    route_get_subjects_by_user="{{route('get_subjects_by_user')}}"
                    route_get_tasks_by_user="{{route('get_tasks_by_user')}}"
                    route_delete_task="{{route('delete_task')}}"
                    route_task_done="{{route('task_done')}}"
                    route_edit_task="{{route('edit_task')}}"
                    route_get_tasks_by_subject="{{route('get_tasks_by_subject')}}"
                    v-bind:page_type="{{json_encode($page_type)}}"
                    route_base_images="{{url('/images/')}}"
                ></todo-list-tag>
            </div>
        </div>
    </div>
@endsection
