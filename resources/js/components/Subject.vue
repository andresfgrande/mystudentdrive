<template>
    <div class="subject">
        <div class="subject-content">
            <div class="">
                <div class="card-header">
                        <a v-if="!editing"> {{subjectName}} - {{current_subject_vue.period_name}}</a>
                        <div v-if="!editing" class="edit-subject-div" @click="enableEditing"></div>
                    <div class="add-section-div" @click="addSectionModal(current_subject_vue.subject_ID)"></div>
                    <!---------------------------------------------------------->
                    <div class="" v-if="editing">
                        <input v-model="tmpSubjectName" class="input"/>
                        <small v-if="showNameExistsSubject"  class="text-danger">
                            Ya tienes una asignatura con este nombre.
                        </small>
                        <button @click="disableEditing"> Cancelar </button>
                        <button @click="saveEditSubject" :disabled='isDisabledEdit'> Guardar </button>
                    </div>
                    <!----------------------------------------------------------->
                </div>
            </div>

                <div v-for="section in subjectSections">
                    <div class="delete-section-div" @click="deleteSectionModal(current_subject_vue.subject_ID,section.id,section.name)"></div>
                    <SubjectSection :key="componentKey"
                                    v-bind:current_section="section"
                                    v-bind:route_get_files_by_section="route_get_files_by_section_vue"
                                    v-bind:route_edit_section="route_edit_section_vue"
                                    v-bind:route_upload_file="route_upload_file_vue"
                                    v-bind:route_base_images="route_base_images_vue"
                                    v-bind:route_delete_file="route_delete_file_vue"
                    ></SubjectSection>
                </div>

        </div>

        <!--/************************** addSectionModal ********************************************/-->
        <div class="modal fade" v-bind:id="getRefId(current_subject_vue.subject_ID)" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="editNameLabel">Nombre de la sección para {{current_subject_vue.subject_name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Nombre de la sección</label>
                                <input class="form-control" v-model="sectionToAdd.name" v-on:keyup="cleanMessage" id="name" type="text" name="name"
                                       placeholder="" required>
                                <small v-if="showNameExists"  class="text-danger">
                                    Ya tienes una sección con este nombre.
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" :disabled='isDisabled'  @click="addSection(current_subject_vue.subject_name)">Guardar</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!--/***********************************************************************************************-->

        <!--/*********************************delete section*********************************************/-->
        <div class="modal fade" v-bind:id="getDeleteRefId(current_subject_vue.subject_ID)" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="deleteFileLabel">¿Estas seguro que deseas eliminar esta sección y todos los archivos que contiene?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <h5>{{sectionToDeleteName}}</h5>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger"   @click="deleteSection">Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--/***********************************************************************************************-->




    </div>
</template>

<script>
    import SubjectSection from './SubjectSection';
    export default {
        name: "Subject",
        props:['current_subject','route_get_sections_by_subject','route_get_files_by_section','route_add_section',
        'route_edit_section','route_upload_file','route_base_images','route_delete_file','route_delete_section',
        'route_edit_subject'],
        components: {
            SubjectSection
        },
        created(){
            this.current_subject_vue = this.current_subject;
            this.route_get_sections_by_subject_vue = this.route_get_sections_by_subject;
            this.route_get_files_by_section_vue = this.route_get_files_by_section;
            this.route_add_section_vue = this.route_add_section;
            this.getSectionsBySubject();
            this.test_id = this.current_subject_vue.subject_ID;
            this.route_edit_section_vue = this.route_edit_section;
            this.route_upload_file_vue = this.route_upload_file;
            this.route_base_images_vue = this.route_base_images;
            this.route_delete_file_vue = this.route_delete_file;
            this.route_delete_section_vue = this.route_delete_section;
            this.route_edit_subject_vue = this.route_edit_subject;
        },
        data(){
            return{
                current_subject_vue: {},
                route_get_sections_by_subject_vue:'',
                subject_info:{
                    subject_id:'',
                },
                subjectSections:[],
                componentKey:0,
                route_get_files_by_section_vue:'',
                sectionToAdd:{
                    subject_id:'',
                    name:'',
                },
                showNameExists:false,
                route_add_section_vue:'',
                test_id:'',

                modalAbierto:'',
                route_edit_section_vue:'',
                route_upload_file_vue:'',
                route_base_images_vue:'',
                route_delete_file_vue:'',
                sectionToDelete:{
                    section_id:'',
                },
                route_delete_section_vue:'',
                modalDeleteAbierto:'',
                sectionToDeleteName:'',
                tmpSubjectName:'',
                editing:false,
                subjectToEdit:{
                    subject_id:'',
                    name:'',
                    period_id:'',
                },
                route_edit_subject_vue:'',
                showNameExistsSubject:false,
            }
        },
        methods:{
            getRefId(id){
                return "addSectionModal" + id;
            },
            getDeleteRefId(id){
                return "deleteSectionModal" + id;
            },
            enableEditing: function(){
                this.tmpSubjectName = this.current_subject_vue.subject_name;
                this.editing = true;
            },
            disableEditing: function(){
                this.tempSubjectName = null;
                this.editing = false;
            },
            saveEditSubject(){
                var url = this.route_edit_subject_vue;
                this.subjectToEdit.subject_id = this.current_subject_vue.subject_ID;
                this.subjectToEdit.period_id = this.current_subject_vue.period_id;
                this.subjectToEdit.name= this.tmpSubjectName;
                axios.put(url ,{subject:this.subjectToEdit}).then(response => {
                    console.log(response.data.result);
                     if(response.data.result === 'error_subject_exists'){
                        this.showNameExistsSubject = true;
                     }else{
                         this.current_subject_vue.subject_name =  this.tmpSubjectName;
                         this.disableEditing();
                     }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            getSectionsBySubject(){
               this.subject_info= this.current_subject_vue;
                var url = this.route_get_sections_by_subject_vue;
                axios.get(url,{params:this.subject_info}).then(response => {
                    console.log(response.data.result);
                    this.subjectSections = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            addSectionModal(id){
                var aux = '#addSectionModal'+id
                $(aux).modal('show');
                this.sectionToAdd.subject_id = '';
                this.sectionToAdd.name ='';
                this.modalAbierto = aux;

            },
            addSection(){

                var url = this.route_add_section_vue;
                this.sectionToAdd.subject_id = this.current_subject_vue.subject_ID
                axios.post(url ,{section:this.sectionToAdd}).then(response => {
                    console.log(response.data.result);
                    this.getSectionsBySubject();

                    if(response.data.result === 'error_section_exists'){
                        this.showNameExists = true;
                    }else{
                        $(this.modalAbierto).modal('hide');
                        this.studyToAdd = '';
                        this.showPhotoEmpty = false;
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            cleanMessage(){
                this.showNameExists = false;

            },
            deleteSection(){
                //this.sectionToDelete.section_id = id;
                var url = this.route_delete_section_vue;
                axios.delete(url,{params:this.sectionToDelete}).then(response => {
                    console.log(response.data.result)
                    $(this.modalDeleteAbierto).modal('hide');
                    this.componentKey += 1;
                    this.getSectionsBySubject();
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            deleteSectionModal(subject_id, section_id,section_name){
                var modalId = '#deleteSectionModal'+ subject_id
                $(modalId).modal('show');
                this.sectionToDelete.section_id = section_id;
                this.sectionToDeleteName = section_name;
                this.modalDeleteAbierto = modalId;
            },
        },
        computed:{
            isDisabled: function(){
                if(this.sectionToAdd.name === ''){
                    return true;
                }else{
                    return false;
                }
            },
            subjectName: function(){
                this.current_subject_vue = this.current_subject;
                return this.current_subject_vue.subject_name;
            },
            isDisabledEdit: function(){
                if(this.tmpSubjectName === ''){
                    return true;
                }else{
                    return false;
                }
            },
        }
    }
</script>

<style scoped>

</style>
