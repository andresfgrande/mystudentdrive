<template>
    <div class="container">

        <button @click="loadContent" >load 1</button>

        <div class="form-group">
            <input type="file" name="select_file" ref="fileInput" @change="onFileSelected"/>
        </div>
        <button @click="getS3FileTest" >download</button>


<!--        <div id="contenedor" v-html="rawHtml" ></div>-->
        <component :is="currentComponent"
        :datotest="this.datotest"
        :datotest2="this.datotest2"/>
<!--        <component :is="rawHtml"/>-->

<!--        <test2 :datotest="this.datotest"></test2>-->
<!---->
    </div>
</template>

<script>
    import Test2 from './Test2';
    export default {
        name: "Test",
        props:['route_test','route_upload','route_download'],
        components: {
            Test2
        },

        created(){
            this.route_test_vue = this.route_test;
            this.route_upload_vue = this.route_upload;
            this.route_download_vue = this.route_download;
        },

        data(){
            return{
                // rawHtml:'<p>'+ 'dddd'+ '</p>',
                datotest:'Hola soy dato de vuejs',
                datotest2: '',
                string:undefined,
                currentComponent: '',

                route_upload_vue:'',
                route_download_vue:'',
                selectedFile: null,

                testData:'nameTest',
            }
        },
        methods:{
            loadContent(){
                var url= this.route_test_vue;
               var aux = ['1','2','3'];
                axios.get(url, {params:aux}).then(response => {
                    console.log(response.data.dataTest);
                    this.datotest = response.data.dataTest;
                    this.currentComponent = "test2";
                    console.log(response.data.dataTest2[0]);
                    this.datotest2 = response.data.dataTest2;

                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            uploadPhoto(){
                const fd = new FormData();
                fd.append('image',this.selectedFile,this.selectedFile.name);
                var url= this.route_upload_vue;
                axios.post(url,fd).then(response => {
                    console.log(response.data);
                    if(response.data.result === 'img_validation_file'){
                        //mostrar aviso de fallo.
                        this.upload_fail = true;
                    }else{
                        this.current_photo_vue = response.data.result_img;
                        this.route_profile_photo_vue = this.route_base_photo + '/' + response.data.result_img;
                        // this.showPhoto = true;
                        // this.showAvatar = false;
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            onFileSelected(event){
                this.upload_fail = false;
                console.log(event);
                this.selectedFile = event.target.files[0];
               this.uploadPhoto();
                // this.getS3FileTest();
            },
            getS3File(){
//                 var url= this.route_download_vue;
//                 axios.get(url,{params:this.testData}).then(response => {
//                    // console.log(response.data);
//
// // append it to your page
//                     document.body.appendChild(outputImg);
//                 })
//                     .catch(errors => {
//                         console.log(errors);
//                     });
                window.location = 'http://mystudentdrive.localhost/downloadfile'
            },
            getS3FileTest(){
                aws.config.update({region: 'eu-west-2'});
                var BUCKET_NAME = 'test-bucket-mystudentdrive';
                var IAM_KEY = 'AKIAQ7XMBXK2EMTYCKD6';
                var IAM_SECRET = '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';

                let s3bucket = new aws.S3({
                    accessKeyId: IAM_KEY,
                    secretAccessKey: IAM_SECRET,
                    Bucket: BUCKET_NAME
                });
                // s3bucket.createBucket(function () {
                    var params = {
                        Bucket: BUCKET_NAME,
                        Key: 'test_example/test.png',
                    };
                //     s3bucket.upload(params, function (err, data) {
                //         if (err) {
                //             console.log('error in callback');
                //             console.log(err);
                //         }
                //         console.log('success');
                //         console.log(data);
                //     });
                // });
                s3bucket.getObject(params,function (err, data) {
                    if (err) {
                        console.log('error in callback');
                        console.log(err);
                    }
                    console.log('success');
                    console.log(data.Body);
                    const STRING_CHAR = String.fromCharCode.apply(null, data.Body);
                    let base64String = btoa(STRING_CHAR);

                    //
                    // var rawResponse = "�PNG...."; // truncated for example
                    //
                    // // convert to Base64
                    // var b64Response = btoa(rawResponse);
                    //
                    // // create an image
                    var outputImg = document.createElement('img');
                    outputImg.src = 'data:image/png;base64,'+ base64String;
                    //
                    // // append it to your page
                    document.body.appendChild(outputImg);

                });
            }
        }
    }
</script>

<style scoped>

</style>
