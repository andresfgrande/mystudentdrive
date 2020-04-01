<template>
<div class="container">


    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="">
                <img class="card-img" style="width:50%;"  :src="this.route_profile_photo_vue"  alt="profile_photo"/>

                <div class="form-group">
                    <table class="table">
                        <tr>
                            <td width="40%" align="right"><label>Select File for Upload</label></td>
                            <td width="30"><input type="file" name="select_file" @change="onFileSelected"/></td>
                            <td width="30%" align="left"><input type="button" name="upload" @click="uploadPhoto" class="btn btn-primary" value="Upload"></td>
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
        props:['route_profile_photo', 'route_upload_photo'],

        created(){
            this.route_profile_photo_vue = this.route_profile_photo;
            this.route_upload_photo_vue = this.route_upload_photo;
        },

        data(){
            return{
                route_profile_photo_vue:'',
                selectedFile: null,
                route_upload_photo_vue:'',
            }
        },
        methods:{
            uploadPhoto(){
                const fd = new FormData();
                fd.append('image',this.selectedFile,this.selectedFile.name)
                var url= this.route_upload_photo_vue;
                axios.post(url,fd).then(response => {
                    console.log(response.data.result);
                    console.log(this.route_profile_photo_vue);
                    this.route_profile_photo_vue = 'http://mystudentdrive.localhost/images/profile/' + response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },

            onFileSelected(event){
                console.log(event);
                this.selectedFile = event.target.files[0];
            },

        }

    }
</script>

<style scoped>

</style>
