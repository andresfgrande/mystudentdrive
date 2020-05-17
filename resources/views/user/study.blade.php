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
    >
    </study>

        <div style="margin-left:65%;top:100px;" class="container-agenda">
        <h3 style="position:absolute;top:100px;">test texto 1</h3>
{{--            @if(session()->has('danger'))--}}
{{--                <div class="alert alert-danger alert-dismissible fade show" role="alert">--}}
{{--                    {{ session()->get('danger') }}--}}
{{--                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--                @if(session()->has('warning'))--}}
{{--                    <div class="alert alert-warning alert-dismissible fade show" role="alert">--}}
{{--                        {{ session()->get('warning') }}--}}
{{--                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
{{--                            <span aria-hidden="true">&times;</span>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                @endif--}}
        </div>

@endsection
