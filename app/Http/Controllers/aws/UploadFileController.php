<?php

namespace App\Http\Controllers\aws;

use App\Http\Controllers\Controller;
use Aws\Crypto\KmsMaterialsProvider;
use Aws\Exception\AwsException;
use Aws\Exception\MultipartUploadException;
use Aws\S3\Crypto\S3EncryptionClient;
use Aws\S3\Crypto\S3EncryptionMultipartUploader;
use Aws\S3\MultipartUploader;
use Aws\S3\ObjectUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

use Aws\Kms\KmsClient;

class UploadFileController extends Controller
{
    public function uploadFile(Request $request){


        $inipath = php_ini_loaded_file();
        if ($inipath) {
            echo 'Loaded php.ini: ' . $inipath;
        } else {
            echo 'A php.ini file is not loaded';
        }


        $image = $request->file('image');
        //$new_name = rand() . '.' . $image->getClientOriginalExtension();
        $new_name = $image->getClientOriginalName();
//        $image->move(public_path('images/profile'), $new_name);

//        if($user->photo != null){
//            unlink(public_path('images/profile')."/"."$user->photo");
//        }
//        $user->photo = $new_name;
//        $user->save();

        //require 'vendor/autoload.php';



        // AWS Info
        $bucketName = 'test-bucket-mystudentdrive';
        $IAM_KEY = 'AKIAQ7XMBXK2EMTYCKD6';
        $IAM_SECRET = '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';

        // Connect to AWS
        try {
            // You may need to change the region. It will say in the URL when the bucket is open
            // and on creation.
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

            $s3EncryptionClient = new S3EncryptionClient($s3,1);

            /**************************/
//            $s3 = S3Client::factory(
//                array(
//                    'credentials' => array(
//                        'key' => $IAM_KEY,
//                        'secret' => $IAM_SECRET
//                    ),
//                    'version' => 'latest',
//                    'region'  => 'eu-west-2'
//                )
//            );
        } catch (Exception $e) {
            // We use a die, so if this fails. It stops here. Typically this is a REST call so this would
            // return a json object.
            die("Error: " . $e->getMessage());
        }


        // For this, I would generate a unqiue random string for the key name. But you can do whatever.
        $keyName = 'test_example/' . basename($new_name); /*basename($_FILES["fileToUpload"]['tmp_name']);*/
        $pathInS3 = 'https://s3.eu-west-2.amazonaws.com/' . $bucketName . '/' . $keyName;

        // Add it to S3
        try {
            // Uploaded:
            $file = $image;//$_FILES["fileToUpload"]['tmp_name'];

//            $s3->putObject(
//                array(
//                    'Bucket'=>$bucketName,
//                    'Key' =>  $keyName,
//                    'SourceFile' => $file,
//                    'StorageClass' => 'STANDARD',
//                    '@http'=>[
//                        'progress' => function ($downloadTotalSize, $downloadSizeSoFar, $uploadTotalSize, $uploadSizeSoFar) {
//                            printf(
//                                "%s of %s downloaded, %s of %s uploaded.\n",
//                                $downloadSizeSoFar,
//                                $downloadTotalSize,
//                                $uploadSizeSoFar,
//                                $uploadTotalSize
//                            );
//                        }
//                    ],
//                )
//            );


            /***********************OK********************/
            // Using stream instead of file path
//            $source = fopen('/home/andres/Escritorio/ubuntu-18.04.2-desktop-amd64.iso', 'rb');
//
//            $uploader = new ObjectUploader(
//                $s3,
//                $bucketName,
//                'test.jpg',
//                $source
//            );
//
//            do {
//                try {
//                    $result = $uploader->upload();
//                    if ($result["@metadata"]["statusCode"] == '200') {
//                        print('<p>File successfully uploaded to ' . $result["ObjectURL"] . '.</p>');
//                    }
//                    print($result);
//                } catch (MultipartUploadException $e) {
//                    rewind($source);
//                    $uploader = new MultipartUploader($s3, $source, [
//                        'state' => $e->getState(),
//                    ]);
//                }
//            } while (!isset($result));
/************************ *******************/
//// Use multipart upload
            $source = '/home/andres/Escritorio/test_folder.zip';
            $source = $file->getRealPath();

//            //ORIGINAL
//            $uploader = new MultipartUploader($s3, $source, [
//                'bucket' => $bucketName,
//                'key' => $file->getClientOriginalName(),
//            ]);

//            try { NO SE USA
//                $result = $uploader->upload();
//                echo "Upload complete: {$result['ObjectURL']}\n";
//            } catch (MultipartUploadException $e) {
//                echo $e->getMessage() . "\n";
//            }
            /************************************************/
            $kms = new KmsClient(array(
                'region'  => 'eu-west-2',
                'version' => 'latest',
                'credentials' => array(
                    'key' => $IAM_KEY,
                    'secret' => $IAM_SECRET
                ),


            ));

            /*$result_key = $kms->createKey(array(
                'BypassPolicyLockoutSafetyCheck' =>false,
                'CustomerMasterKeySpec' => 'SYMMETRIC_DEFAULT',
                'Description' => 'test_key',
                'KeyUsage' => 'ENCRYPT_DECRYPT',
                'Origin' => 'AWS_KMS',
            ));*/

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
            $multipartUploader = new S3EncryptionMultipartUploader(
                $s3,
                $source,
                [
                    '@MaterialsProvider' => $materialsProvider,
                    '@CipherOptions' => $cipherOptions,
                    'bucket' => $bucketName,
                    'Key' => $file->getClientOriginalName(),
                ]
            );
            $promise = $multipartUploader->promise();
            try {
                $result = $promise->wait();
                echo "Upload complete: {$result['ObjectURL']}\n";
            } catch (AwsException $e) {
                echo $e->getMessage() . "\n";
            }
            /************************************************/
//              ORIGINAL
//            $promise = $uploader->promise();
//            try {
//                $result = $promise->wait();
//                echo "Upload complete: {$result['ObjectURL']}\n";
//            } catch (AwsException $e) {
//                echo $e->getMessage() . "\n";
//            }

        } catch (S3Exception $e) {
            die('Error:' . $e->getMessage());
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }

        echo 'Done promises';
        return Response::json(array('success'=>true,'result'=>'file_upload_ok','result_img'=>$new_name));
    }

    public function downloadFile(Request $request){

        $BUCKET_NAME = 'test-bucket-mystudentdrive';
        $IAM_KEY = 'AKIAQ7XMBXK2EMTYCKD6';
        $IAM_SECRET = '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';


        // Get the access code
//        $accessCode = $_GET['c'];
//        $accessCode = strtoupper($accessCode);
//        $accessCode = trim($accessCode);
//        $accessCode = addslashes($accessCode);
//        $accessCode = htmlspecialchars($accessCode);

        // Connect to database
//        $con = ...

  // Verify valid access code
//  $result = mysqli_query($con, "SELECT * FROM s3Files WHERE code='$accessCode'") or die("Error: Invalid request");
//  if (mysqli_num_rows($result) != 1) {
//      die("Error: Invalid access code");
//  }

  // Get path from db
//  $keyPath = '';
//  while($row = mysqli_fetch_array($result)) {
//      $keyPath = $row['s3FilePath'];
//  }
        $keyPath = 'test_example/test.png';

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

      //    ORIGINAL
//      $result = $s3->getObject(array(
//          'Bucket' => $BUCKET_NAME,
//          'Key'    => '172353_10150129042268147_3664705_o.jpg'
//      ));


      /**********************/
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
          'Key' => '4.tar.xz',

      ]);


      /**********************/

      // Display it in the browser
      header("Content-Type: {$result['ContentType']}");
      header('Content-Disposition: filename="' . basename('4.tar.xz') . '"');
      echo $result['Body'];


  } catch (Exception $e) {
      die("Error: " . $e->getMessage());
  }
    }


}
