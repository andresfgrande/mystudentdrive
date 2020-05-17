<?php

namespace App\Http\Controllers\vuejs;

use App\AcademicYear;
use App\File;
use App\Http\Controllers\Controller;
use App\Section;
use App\Study;
use App\Subject;
use Aws\S3\S3Client;
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

    public function editSubject(Request $request){

        $subject_id = $request->subject['subject_id'];
        $name = $request->subject['name'];
        $period_id = $request->subject['period_id'];

        $request->request->add(['name' => $request->subject['name']]);

        try {
            $this->validate($request, [
                'name' => 'required',
            ]);
        } catch (ValidationException $e) {
            return Response::json(array('success'=>false,'result'=>'error_subject_required'));
        }

        $result =  DB::table('subjects')
            ->where('period_id', $period_id)
            ->where('name',$name)
            ->where('id','!=',$subject_id)
            ->get('id');
        if(empty($result->toArray())){
            try {
                $subject = Subject::find($subject_id);
                $subject->name = $name;
                $subject->save();
            } catch (\Throwable $e) {
                return Response::json(array('success'=>false,'result'=>'error_edit_subject'));
            }
        }else{
            return Response::json(array('success'=>false,'result'=>'error_subject_exists'));
        }
        return Response::json(array('success'=>true,'result'=>'subject_edited_ok'));

    }
    public function checkSubjectExistInPeriod($newName, $period_id){
        $data = DB::table('subjects')
            ->where('name', $newName)
            ->where('period_id', $period_id)
            ->get('id');
        if(empty($data->toArray())){
            return false;  // correcto, no existe.
        }
        return true;
    }

    public function deleteSection(Request $request){
        $section_id = $request->get('section_id');

        $result =  DB::table('files')
            ->where('section_id', $section_id)
            ->get();
        $filesInSection = $result->toArray();

        if(empty($filesInSection)){
            try {
                $section = Section::find($section_id);
                $section->forceDelete();
            } catch (\Throwable $e) {
                return Response::json(array('success'=>false,'result'=>$e));
            }
            return Response::json(array('success'=>true,'result'=>'section_deleted'));
        }else{
            try {
                $section = Section::find($section_id);
                $section->forceDelete();
            } catch (\Throwable $e) {
                return Response::json(array('success'=>false,'result'=>$e));
            }
            /*****************************************************/
            $bucketName = 'test-bucket-mystudentdrive';
            $IAM_KEY = 'AKIAQ7XMBXK2P5QGZZXM';
            $IAM_SECRET = '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';

            foreach($filesInSection as $file){
                try {
                    $s3 = new S3Client(
                        array(
                            'credentials' => array(
                                'key' => $IAM_KEY,
                                'secret' => $IAM_SECRET
                            ),
                            'version' => 'latest',
                            'region'  => 'eu-west-2'
                        )
                    );
                    $s3->deleteObject([
                        'Bucket' => $bucketName,
                        'Key'    => $file->file_path
                    ]);
                } catch (Exception $e) {
                    die("Error: " . $e->getMessage());
                }
            }
            return Response::json(array('success'=>true,'result'=>'file_deleted'));

        }
    }
    public function deleteSubject(){
        //TODO
    }
    public function deleteYear(){
        //TODO
    }
    public function deleteStudy(){
        //TODO
    }
}
