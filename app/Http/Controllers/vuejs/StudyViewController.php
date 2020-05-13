<?php

namespace App\Http\Controllers\vuejs;

use App\AcademicYear;
use App\Http\Controllers\Controller;
use App\Section;
use App\Study;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class StudyViewController extends Controller
{
    public function getYearsByOneStudy(Request $request){

        $study_id = $request->get('study_id');

        $var_years = DB::table('academic_years')
            ->where('study_id', $study_id)
            ->orderBy('start_date', 'desc')
            ->get();
        $years = $var_years->toArray();

        return Response::json(array('success'=>true,'result'=>$years));
    }

    public function getSectionsBySubject(Request $request){
        $subject_id = $request->get('subject_ID');
        $var_sections = DB::table('sections')
            ->where('subject_id', $subject_id)
            ->orderBy('id', 'asc')
            ->get();
        $sections = $var_sections->toArray();
        return Response::json(array('success'=>true,'result'=>$sections));
    }
    public function getFilesBySection(Request $request){
        $section_id = $request->get('section_id');
        $var_files = DB::table('files')
            ->where('section_id', $section_id)
            ->orderBy('id', 'asc')
            ->get();
        $files = $var_files->toArray();
        return Response::json(array('success'=>true,'result'=>$files));

    }

    public function addSection(Request $request){

        $subject_id = $request->section['subject_id'];
        $name = $request->section['name'];

        $request->request->add(['subject_id' => $request->section['subject_id']]);
        $request->request->add(['name' => $request->section['name']]);

        try {
            $this->validate($request, [
                'subject_id' => 'required',
                'name' => 'required',

            ]);
        } catch (ValidationException $e) {
            return Response::json(array('success'=>false,'result'=>'error_dection_data_required'));
        }

        $result =  DB::table('sections')
            ->where('subject_id', $subject_id)
            ->where('name',$name)
            ->get('id');


        if(empty($result->toArray())){
            try {
                $section = new Section();
                $section->subject_id = $subject_id;
                $section->name = $name;
                $section->save();
            } catch (\Throwable $e) {
                return Response::json(array('success'=>false,'result'=>'error_create_section'));
            }
        }else{
            return Response::json(array('success'=>false,'result'=>'error_section_exists'));
        }
        return Response::json(array('success'=>true,'result'=>'section_created_ok'));
    }

    public function editSection(Request $request){

        $section_id = $request->section['section_id'];
        $subject_id = $request->section['subject_id'];
        $name = $request->section['name'];

        $request->request->add(['subject_id' => $request->section['subject_id']]);
        $request->request->add(['name' => $request->section['name']]);

        try {
            $this->validate($request, [
                'subject_id' => 'required',
                'name' => 'required',

            ]);
        } catch (ValidationException $e) {
            return Response::json(array('success'=>false,'result'=>'error_section_data_required'));
        }

        $result =  DB::table('sections')
            ->where('subject_id', $subject_id)
            ->where('name',$name)
            ->where('id','!=',$section_id)
            ->get('id');

        if(empty($result->toArray())){
            try {
                $section = Section::find($section_id);
                $section->subject_id = $subject_id;
                $section->name = $name;
                $section->save();
            } catch (\Throwable $e) {
                return Response::json(array('success'=>false,'result'=>'error_edit_section'));
            }
        }else{
            return Response::json(array('success'=>false,'result'=>'error_section_exists'));
        }
        return Response::json(array('success'=>true,'result'=>'section_edited_ok'));
    }
}
