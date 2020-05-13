<?php

namespace App\Http\Controllers\aws;

use App\File;
use App\Http\Controllers\Controller;
use App\Section;
use Aws\Crypto\KmsMaterialsProvider;
use Aws\Exception\AwsException;
use Aws\Kms\KmsClient;
use Aws\S3\Crypto\S3EncryptionClient;
use Aws\S3\Crypto\S3EncryptionMultipartUploader;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Aws\S3\ObjectUploader;


class UploadFileController extends Controller
{
    public function uploadFile(Request $request){
        $inipath = php_ini_loaded_file();
//        if ($inipath) {
//            echo 'Loaded php.ini: ' . $inipath;
//        } else {
//            echo 'A php.ini file is not loaded';
//        }
        $new_file = $request->file('new_file');
        $new_name = $new_file->getClientOriginalName();
        $bucketName = 'test-bucket-mystudentdrive';
        $IAM_KEY = 'AKIAQ7XMBXK2P5QGZZXM';
        $IAM_SECRET = '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';

        try {
            /***************************/
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

            //$s3EncryptionClient = new S3EncryptionClient($s3,1);

        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }

        //$keyName = 'test_example/' . basename($new_name); /*basename($_FILES["fileToUpload"]['tmp_name']);*/
        //$pathInS3 = 'https://s3.eu-west-2.amazonaws.com/' . $bucketName . '/' . $keyName;

        // Add it to S3
        try {
            // Uploaded:
            $file = $new_file;//$_FILES["fileToUpload"]['tmp_name'];
            // Use multipart upload
            $source = $file->getRealPath();

            /************************************************/
            $kms = new KmsClient(array(
                'region'  => 'eu-west-2',
                'version' => 'latest',
                'credentials' => array(
                    'key' => $IAM_KEY,
                    'secret' => $IAM_SECRET
                ),
            ));

            $kmsKeyArn = 'arn:aws:kms:eu-west-2:068140776116:key/a6c703a9-1284-4119-b383-f79dfaf063a8';
            // This materials provider handles generating a cipher key and
            // initialization vector, as well as encrypting your cipher key via AWS KMS
            $materialsProvider = new KmsMaterialsProvider(
                $kms,
                $kmsKeyArn
            );

            $cipherOptions = [
                'Cipher' => 'gcm',
                'KeySize' => 256,
                // Additional configuration options
            ];
            /**********DATOS PARA GUARADRA EN BBDD*********/
            $section_id = $request->get('section_id');
            $file_name = $file->getClientOriginalName();
            $result =  DB::table('sections')
                ->where('id', $section_id)
                ->get('subject_id');
            $auxArray = $result->toArray();
            $subject_id = $auxArray[0]->subject_id;
            $user = Auth::User();
            $user_id = $user->getAuthIdentifier();
            /**********************************************/
            $multipartUploader = new S3EncryptionMultipartUploader(
                $s3,
                $source,
                [
                    '@MaterialsProvider' => $materialsProvider,
                    '@CipherOptions' => $cipherOptions,
                    'bucket' => $bucketName,
                    'Key' => "$user_id"."/"."$subject_id"."/"."$section_id"."/"."$file_name" ,
                ]
            );
            $promise = $multipartUploader->promise();
            try {
                $result = $promise->wait();
                //echo "Upload complete: {$result['ObjectURL']}\n";
            } catch (AwsException $e) {
                //echo $e->getMessage() . "\n";
                $e->getMessage();
            }

            /***************GUARDAR EN BBDD*******************/

            //Comprovación fichero existe
            $result2 =  DB::table('files')
                ->where('name', $file_name)
                ->where('section_id', $section_id)
                ->get(array('id','name'));
            $auxArray2 = $result2->toArray();
            if(!empty($auxArray2)){
                try {
                    $file = File::find($auxArray2[0]->id);
                    $file->section_id = $section_id;
                    $file->name = $file_name;
                    $file->file_path = "$user_id"."/"."$subject_id"."/"."$section_id"."/"."$file_name";
                    $file->access_code = $user->getAuthPassword();
                    $file->save();
                } catch (\Throwable $e) {
                    return Response::json(array('success'=>false,'result'=>'file_upload_error'));
                }
            }else{
                try {
                    $file = new File();
                    $file->section_id = $section_id;
                    $file->name = $file_name;
                    $file->file_path = "$user_id"."/"."$subject_id"."/"."$section_id"."/"."$file_name";
                    $file->access_code = $user->getAuthPassword();
                    $file->save();
                } catch (\Throwable $e) {
                    return Response::json(array('success'=>false,'result'=>'file_upload_error'));
                }
            }
            /*************************************************/

        } catch (S3Exception $e) {
           //die('Error:' . $e->getMessage());
            return Response::json(array('success'=>false,'result'=>'file_upload_error'));
        } catch (Exception $e) {
            //die('Error:' . $e->getMessage());
            return Response::json(array('success'=>false,'result'=>'file_upload_error'));

        }

        //echo 'Done promises';
        return Response::json(array('success'=>true,'result'=>'file_upload_ok','result_file'=>$new_name));





    }

    public function downloadFile(Request $request)
    {
        $BUCKET_NAME = 'test-bucket-mystudentdrive';
        $IAM_KEY = 'AKIAQ7XMBXK2P5QGZZXM';
        $IAM_SECRET = '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';

        $keyPath = '1prod.png';

        // Get file
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
            $s3EncryptionClient = new S3EncryptionClient($s3);

            $kms = new KmsClient(array(
                'region'  => 'eu-west-2',
                'version' => 'latest',
                'credentials' => array(
                    'key' => $IAM_KEY,
                    'secret' => $IAM_SECRET
                ),
            ));

            $kmsKeyArn = 'arn:aws:kms:eu-west-2:068140776116:key/a6c703a9-1284-4119-b383-f79dfaf063a8';
            // This materials provider handles generating a cipher key and
            // initialization vector, as well as encrypting your cipher key via AWS KMS
            $materialsProvider = new KmsMaterialsProvider(
                $kms,
                $kmsKeyArn
            );

            $cipherOptions = [
                'Cipher' => 'gcm',
                'KeySize' => 256,
                // Additional configuration options
            ];

            $result = $s3EncryptionClient->getObject([
                '@MaterialsProvider' => $materialsProvider,
                '@CipherOptions' => [],
                'Bucket' => $BUCKET_NAME,
                'Key' => '1prod.png',
            ]);

            // Display it in the browser
            header("Content-Type: {$result['ContentType']}");
            header('Content-Disposition: filename="' . basename('1prod.png') . '"');
            echo $result['Body'];

        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
}
