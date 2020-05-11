<template>
    <div class="subject">

        <div class="subject-content">
            <div class="">
                <div class="card-header"> <a>{{current_subject_vue.subject_ID}} {{current_subject_vue.subject_name}} - {{current_subject_vue.period_name}}</a>
                    <button type="button" class="btn btn-primary" @click="addSectionModal(current_subject_vue.subject_ID)">crear una sección</button>
                </div>
            </div>
            <div v-for="section in subjectSections">
                <SubjectSection :key="componentKey"
                                v-bind:current_section="section"
                                v-bind:route_get_files_by_section="route_get_files_by_section_vue"
                ></SubjectSection>
            </div>
        </div>

        <!--/**************************addSectionModal*******ADD SECTION*********************************************/-->
        <div class="modal fade" v-bind:id="getRefId(current_subject_vue.subject_ID)" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="editNameLabel">Nombre de la sección {{current_subject_vue}}</h5>
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




    </div>
</template>

<script>
    import SubjectSection from './SubjectSection';
    export default {
        name: "Subject",
        props:['current_subject','route_get_sections_by_subject','route_get_files_by_section','route_add_section'],
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
        },
        data(){
            return{
                current_subject_vue: {},
                route_get_sections_by_subject_vue:'',
                subject_info:{
                    subject_id:'',
                },
                subjectSections:[],
                componentKey:'',
                route_get_files_by_section_vue:'',
                sectionToAdd:{
                    subject_id:'',
                    name:'',
                },
                showNameExists:false,
                route_add_section_vue:'',
                test_id:'',

                modalAbierto:'',
            }
        },
        methods:{
            getRefId(id){
                return "addSectionModal" + id;
            },
            // getRefId2(id){
            //     return "collapse-list" + id;
            // },
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
        },
        computed:{
            isDisabled: function(){
                if(this.sectionToAdd.name === ''){
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
