<template>

    <div class="container content year">
        <h3>Curso {{formatDateYear(chosed_year_vue.start_date)}} - {{formatDateYear(chosed_year_vue.end_date)}}</h3>
        <button type="button"  class="btn btn-primary btn-add-subject" @click="addSubjectModal">
            Añadir asignatura
        </button>
            <div v-for="subject in subjectsArray">
                <div class="delete-subject-div" @click="deleteSubjectModal(chosed_year_vue.id, subject.subject_ID, subject.subject_name)">
                </div>
                <Subject :key="componentKey"
                         v-bind:current_subject="subject"
                         v-bind:route_get_sections_by_subject="route_get_sections_by_subject_vue"
                         v-bind:route_get_files_by_section="route_get_files_by_section_vue"
                         v-bind:route_add_section="route_add_section_vue"
                         v-bind:route_edit_section="route_edit_section_vue"
                         v-bind:route_upload_file="route_upload_file_vue"
                         v-bind:route_base_images="route_base_images_vue"
                         v-bind:route_delete_file="route_delete_file_vue"
                         v-bind:route_delete_section="route_delete_section_vue"
                         v-bind:route_edit_subject="route_edit_subject_vue"
                ></Subject>
            </div>


        <!--/***********************************ADD SUBJECT*************************************************/-->
        <div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="addSubjectLabel">Nueva asignatura de <strong>{{chosed_study_vue}} {{formatDateYear(chosed_year_vue.start_date)}}-{{formatDateYear(chosed_year_vue.end_date)}}</strong></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="subject_name">Nombre</label>
                                <input class="form-control" v-model="subjectToAdd.name"
                                       id="subject_name" type="text" name="subject_name"
                                       placeholder="" v-on:keyup="cleanMessage" required>
                                <small v-if="show_exists" id="passwordHelp" class="text-danger">
                                    Ya existe una asignatura con este nombre en el periodo escogido.
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="subject_color">Color</label>
                                <input type="color" id="subject_color" name="subject_color" v-model="subjectToAdd.color" required>
                            </div>
                            <div class="form-group">
                                <label for="subject_period">Selecciona un periodo de este curso <strong> {{formatDateFull(this.chosed_year_vue.start_date)}}-{{formatDateFull(this.chosed_year_vue.end_date)}}</strong></label>
                                <div v-if="periodsArray.length === 0">
                                    <p>Aun no tienes periodos...</p>
                                    <a id="toggle-period2" data-toggle="collapse" href="#collapse-new-period"  aria-expanded="false"
                                       role="button"  aria-controls="collapseExample">
                                        Crea un periodo para tu asignatura
                                    </a>

                                </div>
                                <div v-else>
                                    <select id="subject_period" v-model="subjectToAdd.period_id">
                                        <option disabled value="">Selecciona un periodo</option>
                                        <option v-for="period in periodsArray" v-bind:value="period.id">
                                            {{period.name}}
                                        </option>
                                    </select>
                                    <a id="toggle-period" data-toggle="collapse" href="#collapse-new-period"  aria-expanded="false"
                                       role="button"  aria-controls="collapseExample">
                                        Añadir nuevo periodo
                                    </a>
                                </div>


                                <div class="collapse" id="collapse-new-period">
                                    <div class="form-group">
                                        <label for="period_name">Nombre del periodo</label>
                                        <input class="form-control" v-model="periodToAdd.name"
                                               id="period_name" type="text" name="period_name"
                                               placeholder="" v-on:keyup="cleanMessagePeriodName" required>
                                    </div>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="showPeriodNameExist">
                                        <strong>Ya tienes un periodo con este nombre.</strong>
                                    </div>
                                    <div class="form-group">
                                        <label for="period_start">Fecha de inicio</label>
                                        <input class="form-control" v-model="periodToAdd.start_date"
                                               id="period_start" type="date" name="period_start"
                                               placeholder="" v-on:change="cleanMessagePeriodDates" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="period_end">Fecha de finalización</label>
                                        <input class="form-control" v-model="periodToAdd.end_date"
                                               id="period_end" type="date" name="period_end"
                                               placeholder="" v-on:change="cleanMessagePeriodDates" required>
                                    </div>
                                    <div class="alert alert-danger alert-dismissible fade show" v-if="showPeriodDatesExist" role="alert">
                                        <strong>  Las fechas seleccionadas coinciden con las de otro periodo.</strong>
                                    </div>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="showPeriodGreater">
                                        <strong>La fecha de inicio es posterior a la fecha de finalización.</strong>
                                    </div>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="showPeriodDatesOutYear">
                                        <strong>Las fechas de este periodo estan fuera el año actual.</strong>
                                    </div>
                                    <button type="button" class="btn btn-secondary" :disabled='isDisabledSavePeriod' @click="addPeriod" >Guardar periodo</button>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" :disabled='isDisabledSaveSubject' @click="addSubject">Guardar asignatura</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!--/*********************************************************************************+******************-->

        <!--/*********************************DELETE SUBJECT*********************************************/-->
        <div class="modal fade" v-bind:id="getDeleteRefId(chosed_year_vue.id)" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="deleteFileLabel">¿Seguro que deseas eliminar esta asignatura y su contenido?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <h5>{{subjectToDeleteName}}</h5>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger"   @click="deleteSubject">Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--/***********************************************************************************************-->


    </div>
</template>

<script>
    import Subject from './Subject';
    export default {
        name: "Year",
        props:['chosed_year','route_get_subjects_by_year','route_get_subjects_by_year','chosed_study',
        'route_add_subject','route_add_period','route_get_periods_by_year','route_get_sections_by_subject',
        'route_get_files_by_section','route_add_section','route_edit_section','route_upload_file','route_base_images',
            'route_delete_file','route_delete_section','route_edit_subject','route_delete_subject'],
        components: {
            Subject
        },
        created(){
           this.chosed_year_vue = this.chosed_year;
           this.chosed_study_vue = this.chosed_study;
           this.route_get_subjects_by_year_vue =  this.route_get_subjects_by_year;
           this.getSubjectsByYear(this.chosed_year_vue.id);
           this.route_add_subject_vue = this.route_add_subject;
           this.route_add_period_vue = this.route_add_period;
           this.route_get_periods_by_year_vue = this.route_get_periods_by_year;
           this.route_get_sections_by_subject_vue = this.route_get_sections_by_subject;
           this.route_get_files_by_section_vue = this.route_get_files_by_section;
           this.route_add_section_vue = this.route_add_section;
           this.route_edit_section_vue = this.route_edit_section;
           this.route_upload_file_vue = this.route_upload_file;
           this.route_base_images_vue = this.route_base_images;
           this.route_delete_file_vue = this.route_delete_file;
           this.route_delete_section_vue = this.route_delete_section;
           this.route_edit_subject_vue = this.route_edit_subject;
           this.route_delete_subject_vue = this.route_delete_subject;
        },
        data(){
            return{
                chosed_year_vue:'',
                test_vue:'',
                route_get_subjects_by_year_vue:'',
                year_info:{
                    year:'',
                },
                subjectsArray:[],
                componentKey:0,
                chosed_study_vue:'',
                route_add_subject_vue:'',
                route_add_period_vue:'',
                subjectToAdd:{
                    period_id:'',
                    name:'',
                    color:'#000000',
                },
                periodToAdd:{
                    year_id:'',
                    name:'',
                    start_date:'',
                    end_date:'',
                },
                show_exists: false,
                periodsArray:'',
                route_get_periods_by_year_vue:'',
                showPeriodDatesExist:false,
                showPeriodGreater:false,
                showPeriodDatesOutYear: false,
                showPeriodNameExist:false,
                yearIdChosen: {
                    year_id:''
                },
                route_get_sections_by_subject_vue:'',
                route_get_files_by_section_vue:'',
                route_add_section_vue:'',
                route_edit_section_vue:'',
                route_upload_file_vue:'',
                route_base_images_vue:'',
                route_delete_file_vue:'',
                route_delete_section_vue:'',
                route_edit_subject_vue:'',
                route_delete_subject_vue:'',
                subjectToDelete:{
                    subject_id:'',
                },
                subjectToDeleteName:'',
                modalDeleteAbierto:'',
            }
        },
        methods:{
            getDeleteRefId(id){
                return "deleteSubjectModal" + id;
            },
            formatDateYear(date_to_format){
                var date = new Date(date_to_format);
                return date = date.getFullYear();
            },
            formatDateFull(date_to_format){
                var date = new Date(date_to_format);
                return date = date.toLocaleDateString();
            },
            /******************************DOING**************************************/
            getSubjectsByYear(year_id/*, study_name, start_date,end_date,study_id*/){
                this.year_info.year = year_id;
                var url = this.route_get_subjects_by_year_vue;
                axios.get(url ,{params:this.year_info}).then(response => {
                    console.log(response.data.result);
                    this.subjectsArray = response.data.result;
                    console.log(this.subjectsArray);
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            /*******************************************************************************/
            addSubjectModal(){
                this.getPeriodsByYear();
                this.subjectToAdd.name='';
                this.subjectToAdd.period_id='';
                this.subjectToAdd.color='#000000';
                this.periodToAdd.name='';
                this.periodToAdd.start_date ='';
                this.periodToAdd.end_date ='';
                $('#addSubjectModal').modal('show');
            },
            addSubject(){
                var url = this.route_add_subject_vue;
                axios.post(url ,{params:this.subjectToAdd}).then(response => {
                    console.log(response.data.result);
                    this.getSubjectsByYear( this.chosed_year_vue.id/*,this.chosenStudy,this.yearStartChosen, this.yearEndChosen*/);
                    if(response.data.result === 'subject_created_ok'){
                        $('#addSubjectModal').modal('hide');
                    }
                    if(response.data.result === 'name_exists'){
                        this.show_exists = true;
                    }
                })
                    .catch(errors => {this.
                    console.log(errors);
                    });
            },
            cleanMessage(){
                this.showNameExists = false;
                this.show_exists = false;
            },
            getPeriodsByYear(){
                this.yearIdChosen.year_id=this.chosed_year_vue.id;
                var url = this.route_get_periods_by_year_vue;
                axios.get(url,{params:this.yearIdChosen}).then(response => {
                    this.periodsArray = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            cleanMessagePeriodName(){
                this.showPeriodNameExist = false;
            },
            cleanMessagePeriodDates(){
                this.showPeriodGreater = false;
                this.showPeriodDatesExist = false;
                this.showPeriodDatesOutYear = false;
            },
            addPeriod(){
                var url = this.route_add_period_vue;
                this.periodToAdd.year_id = this.chosed_year_vue.id;
                axios.post(url ,{params:this.periodToAdd}).then(response => {
                    console.log(response.data.result);
                    if(response.data.result === 'period_created_ok'){
                        this.getPeriodsByYear();
                        this.subjectToAdd.period_id = response.data.period[0].id;
                        $('#toggle-period').click();
                        $('#toggle-period2').click();
                        this.periodToAdd.start_date='';
                        this.periodToAdd.end_date='';
                        this.periodToAdd.name='';
                    }
                    if(response.data.result === 'period_name_exist'){
                        this.showPeriodNameExist = true;
                    }
                    if(response.data.result === 'solapa_dates'){
                        this.showPeriodDatesExist = true;
                    }
                    if(response.data.result === 'error_start_date_greater'){
                        this.showPeriodGreater = true;
                    }
                    if(response.data.result === 'period_out_of_year'){
                        this.showPeriodDatesOutYear = true;
                    }
                })
                    .catch(errors => {this.
                    console.log(errors);
                    });
            },
            deleteSubject(){
                var url = this.route_delete_subject_vue;
                axios.delete(url,{params:this.subjectToDelete}).then(response => {
                    console.log(response.data.result)
                    $(this.modalDeleteAbierto).modal('hide');
                    this.componentKey += 1;
                    this.getSubjectsByYear(this.chosed_year_vue.id)

                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            deleteSubjectModal(year_id, subject_id ,subject_name){
                var modalId = '#deleteSubjectModal'+ year_id
                $(modalId).modal('show');
                this.subjectToDelete.subject_id = subject_id;
                this.subjectToDeleteName = subject_name;
                this.modalDeleteAbierto = modalId;
            },
        },
        computed:{
            isDisabledSaveSubject: function(){
                if(this.subjectToAdd.period_id === '' || this.subjectToAdd.name === ''){
                    return true;
                }else{
                    return false;
                }
            },
            isDisabledSavePeriod: function(){
                if(this.periodToAdd.name === '' || this.periodToAdd.start_date === '' || this.periodToAdd.end_date === ''){
                    return true;
                }else{
                    return false;
                }
            },
            actualizar: function(){
                this.chosed_year_vue = this.chosed_year;
                this.getSubjectsByYear(this.chosed_year_vue.id);
                return this.subjectsArray;
            }
        }
    }
</script>

<style scoped>

</style>
