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

@endsection

