@extends('layouts.app')

@section('content')

{{--    <div class="container">--}}
{{--        <h2 class="page-title">Nombre del grado estudio</h2>--}}
{{--    </div>--}}


    <study
        v-bind:study_prop="{{json_encode($study)}}"
        v-bind:years_prop="{{json_encode($years)}}"
        v-bind:index_chosen_year="{{$indexChosenYear}}"
        route_add_year="{{route('add_year')}}"
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
    >
    </study>

{{--    <agenda></agenda>--}}

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

        <div style="margin-left:65%;top:100px;" class="container-avisos">
            @if(session()->has('danger'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session()->get('danger') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
                @if(session()->has('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        {{ session()->get('warning') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
        </div>
@endsection
