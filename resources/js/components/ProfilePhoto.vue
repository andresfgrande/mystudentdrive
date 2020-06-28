<template>
<div class="container content photo-component">


    <div class="row justify-content-center">
        <div class="col-md-5">
            <div>
                <div class="image-profile-container">
                    <a class="image-container" v-if="showPhoto" @click="$refs.fileInput.click()">
                        <img class="card-img"  :src="this.route_profile_photo_vue"  alt="profile_photo"/>
                    </a>
                </div>

                <div class="image-profile-container ">
                    <button  type="button" name="delete" @click="deletePhoto" v-if="showPhoto" class="btn btn-danger delete-photo action-photo" >Quitar foto</button>
                </div>

                <div class="image-profile-container" v-if="showAvatar" >
                    <div class="avatar-circle">
                        <span class="initials">{{this.initials}}</span>
                    </div>
                </div>

                <div class="image-profile-container action-photo">
                    <button type="button" name="upload" @click="$refs.fileInput.click()" v-if="showAvatar" class="btn btn-primary delete-photo action-photo" >Añadir foto</button>
                </div>

                <div class="alert alert-danger alert-dismissible fade show" v-if="upload_fail" role="alert">
                    <strong>La foto escogida no tiene una extensión válida (jpeg, jpg, png, gif).</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="form-group">
                    <input style="display:none" type="file" name="select_file" ref="fileInput" @change="onFileSelected"/>
                </div>
            </div>
        </div>
    </div>


</div>
</template>

<script>
    export default {
        name: "ProfilePhoto",
        props:['route_profile_photo', 'route_upload_photo', 'route_base_photo','current_photo','route_delete_photo',
                'user_name','user_surnames'],

        created(){

            this.route_upload_photo_vue = this.route_upload_photo;
            this.route_base_photo_vue = this.route_base_photo;
            this.current_photo_vue = this.current_photo;
            this.route_profile_photo_vue = this.route_base_photo_vue + '/' + this.current_photo_vue;
            this.route_delete_photo_vue = this.route_delete_photo;
            this.user_name_vue = this.user_name;
            this.user_surnames_vue = this.user_surnames;
            this.getInitials();
        },

        data(){
            return{
                route_profile_photo_vue:'',
                selectedFile: null,
                route_upload_photo_vue:'',
                route_base_photo_vue:'',
                upload_fail:false,
                current_photo_vue: '',
                initials: '',
                user_name_vue: '',
                user_surnames_vue: '',
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
            },
            deletePhoto(){
                var url= this.route_delete_photo_vue;
                axios.delete(url).then(response => {
                    console.log(response.data);
                    this.user_name_vue = response.data.name;
                    this.user_surnames_vue = response.data.surnames;
                    this.getInitials();
                    this.route_profile_photo_vue = '';
                    this.current_photo_vue = '';
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            getInitials(){
                var name = this.user_name_vue.charAt(0);
                var surname = this.user_surnames_vue.charAt(0);
                this.initials = name.toUpperCase() + surname.toUpperCase();
            }

        },
        computed:{
            showPhoto: function(){
               if(this.current_photo_vue !== ''){
                   return true;
               }else{
                   return false;
               }
            },
            showAvatar: function(){
                if(this.current_photo_vue !== ''){
                    return false;
                }else{
                    return true;
                }

            }
        }

    }
</script>

<style scoped>

</style>
