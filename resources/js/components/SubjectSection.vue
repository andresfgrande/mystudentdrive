<template>
<div class="subject-section">
    <div class="section-title" @click="enableEditing" v-if="!editing">
       <p>{{current_section_vue.name}} </p>
    </div>
    <div class="section-title edit" v-if="editing">
        <input v-model="tmpSectionName" class="input"/>
        <small v-if="showNameExists"  class="text-danger">
            Ya tienes una sección con este nombre.
        </small>
        <button @click="disableEditing"> Cancelar </button>
        <button @click="saveEditSection"> Guardar </button>
    </div>

    <div class="section-item">
        <ul>
            <li v-for="file in sectionFiles">
               {{file.name}}
            </li>
        </ul>
<!--        <button type="button" @click="uploadFile">test llamada upload file</button>-->
        <button type="button" class="btn btn-primary" @click="uploadFileModal(current_section_vue.id)">Añadir archivo</button>
<!--        <button type="button" class="btn btn-primary" @click="testDownloadFile()">test download file</button>-->
    </div>
<!--    v-if="successMsg"-->
    <div class="alert alert-succes alert-dismissible fade show" v-if="successMsg" role="alert">
        <strong>¡Archivo subido satisfactoriamente!</strong>
        <button type="button" class="close" @click="hideMsg" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<!--    v-if="failMsg"-->
    <div class="alert alert-danger alert-dismissible fade show" v-if="failMsg" role="alert">
        <strong>No se ha podido guardar tu archivo, vuelve a intentarlo.</strong>
        <button type="button" class="close"  @click="hideMsg" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="alert alert-primary uploading" v-if="uploadingMsg"  role="alert">
        <strong>Subiendo archivo...</strong>
        <img class="loading-gif" :src="this.gifUrl"  alt="profile_photo"/>
        <button type="button" class="close"  @click="hideMsg" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <!--/*****************id="uploadFileModal"****************UPLOAD FILE MODAL*********************************************/-->
    <div class="modal fade"  v-bind:id="getRefId(current_section_vue.id)" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"  id="editNameLabel">Nuevo archivo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
<!--                        <div class="form-group">-->
<!--                            <label for="name">Nombre del estudio</label>-->
<!--                            <input class="form-control" v-model="studyToAdd" v-on:keyup="cleanMessage" id="name" type="text" name="name"-->
<!--                                   placeholder="" required>-->
<!--                            <small v-if="showNameExists"  class="text-danger">-->
<!--                                Ya tienes unos estudios con este nombre.-->
<!--                            </small>-->
<!--                        </div>-->
                        <div class="form-group">
                            <input type="file" name="select_file" id="selected_file_s3" ref="fileInput" @change="onFileSelected"/>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary"  @click="uploadFile">Guardar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <!--/***********************************************************************************************-->

</div>
</template>

<script>
    export default {
        name: "Section",
        props:['current_section','route_get_files_by_section','route_edit_section','route_upload_file',
        'route_base_images'],
        created(){
            this.current_section_vue = this.current_section;
            this.route_get_files_by_section_vue = this.route_get_files_by_section;
            this.getFilesBySection();
            this.route_edit_section_vue = this.route_edit_section;
            this.route_upload_file_vue = this.route_upload_file;
            this.route_base_images_vue = this.route_base_images;
            this.gifUrl = this.route_base_images_vue + "/gif/" + "loading-gif.gif";
        },
        data(){
            return{
                current_section_vue: {},
                componentKey:'',
                route_get_files_by_section_vue:'',
                section_info:{
                    section_id:''
                },
                sectionFiles:'',
                editing: false,
                tmpSectionName:null,
                sectionToEdit:{
                    section_id:'',
                    subject_id:'',
                    name:'',
                },
                showNameExists:false,
                route_edit_section_vue:'',
                route_upload_file_vue:'',
                fileToAdd:{
                    section_id:null,
                },
                selectedFile: null,
                modalAbierto:'',
                successMsg: false,
                failMsg: false,
                uploadingMsg: false,
                route_base_images_vue:'',
                gifUrl:'',
            }
        },
        methods:{
            getRefId(id){
                return "uploadFileModal" + id;
            },


            getFilesBySection(){
                this.section_info.section_id= this.current_section_vue.id;
                var url = this.route_get_files_by_section_vue;
                console.log(this.route_get_files_by_section_vue)
                axios.get(url,{params:this.section_info}).then(response => {
                    console.log(response.data.result);
                    this.sectionFiles = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            enableEditing: function(){
                this.tmpSectionName = this.current_section_vue.name;
                this.editing = true;
            },
            disableEditing: function(){
                this.tempSectionName = null;
                this.editing = false;
            },
            saveEditSection: function(){
                var url = this.route_edit_section_vue;
                this.sectionToEdit.section_id = this.current_section_vue.id;
                this.sectionToEdit.subject_id = this.current_section_vue.subject_id;
                this.sectionToEdit.name= this.tmpSectionName;
                axios.put(url ,{section:this.sectionToEdit}).then(response => {
                    console.log(response.data.result);
                    if(response.data.result === 'error_section_exists'){
                        this.showNameExists = true;
                    }else{
                        this.current_section_vue.name =  this.tmpSectionName;
                        this.disableEditing();
                        this.sectionToAdd = '';
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            uploadFileModal(id){
                this.successMsg = false;
                this.failMsg = false;
                var modalId = '#uploadFileModal'+id
                $(modalId).modal('show');
                //$('#uploadFileModal').modal('show');
                // this.fileToAdd;
                this.modalAbierto = modalId;
            },
            uploadFile(){
                const fd = new FormData();
                fd.append('new_file',this.selectedFile,this.selectedFile.name);
                this.fileToAdd.section_id = this.current_section_vue.id;
                $(this.modalAbierto).modal('hide');
                this.uploadingMsg = true;
                var url = this.route_upload_file_vue;
                axios.post(url,fd,{params:this.fileToAdd}).then(response => {
                    console.log(response.data);
                    $('#selected_file_s3').val('');
                    //$(this.modalAbierto).modal('hide');
                    this.selectedFile = null;
                    this.getFilesBySection();
                    this.uploadingMsg = false;
                    if(response.data.result === 'file_upload_ok'){
                        this.successMsg = true;
                    }else{
                        this.failMsg = true;
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            onFileSelected(event){
                //this.upload_fail = false;
                console.log(event);
                this.selectedFile = event.target.files[0];
                // this.getS3FileTest();
            },
            cleanMessage(){
                this.showNameExists = false;
            },
            testDownloadFile(){
                // window.location = "/download_file";
                // var url = this.route_upload_file_vue;
                axios.get('/download_file').then(response => {
                    console.log(response.data.result);
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            hideMsg(){
                this.successMsg = false;
                this.failMsg = false;
                this.uploadingMsg = false;
            }


        }
    }
</script>

<style scoped>

</style>
