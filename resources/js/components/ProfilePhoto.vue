<template>
<div class="container">


    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="">
                <a @click="$refs.fileInput.click()">
                    <img class="card-img" style="width:50%; cursor:pointer"  :src="this.route_profile_photo_vue"  alt="profile_photo"/>
                </a>
                <div class="alert alert-danger alert-dismissible fade show" v-if="upload_fail" role="alert">
                    <strong>La foto escogida no tiene una extensión valida o es mayor de 10 MB.</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="form-group">
                    <table class="table">
                        <tr>
                            <td width="40%" align="right"><label>Select File for Upload</label></td>
                            <td width="30">
                                <input style="display:none" type="file" name="select_file" ref="fileInput" @change="onFileSelected"/>
                            </td>
<!--                            <td width="30%" align="left"><input type="button" name="upload" @click="uploadPhoto" class="btn btn-primary" value="Upload"></td>-->
                        </tr>
                        <tr>
                            <td width="40%" align="right"></td>
                            <td width="30"><span class="text-muted">jpg, png, gif</span></td>
                            <td width="30%" align="left"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>
</template>

<script>
    export default {
        name: "ProfilePhoto",
        props:['route_profile_photo', 'route_upload_photo', 'route_base_photo'],

        created(){
            this.route_profile_photo_vue = this.route_profile_photo;
            this.route_upload_photo_vue = this.route_upload_photo;
            this.route_base_photo_vue = this.route_base_photo;
        },

        data(){
            return{
                route_profile_photo_vue:'',
                selectedFile: null,
                route_upload_photo_vue:'',
                route_base_photo_vue:'',
                upload_fail:false,
            }
        },
        methods:{
            uploadPhoto(){
                const fd = new FormData();
                fd.append('image',this.selectedFile,this.selectedFile.name);
                var url= this.route_upload_photo_vue;
                axios.post(url,fd).then(response => {
                    console.log(response.data);
                    if(response.data.result === 'img_validation_file'){
                        //mostrar aviso de fallo.
                        this.upload_fail = true;
                    }else{
                        this.route_profile_photo_vue = this.route_base_photo + '/' +response.data.result_img;
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
            },

        }

    }
</script>

<style scoped>

</style>
