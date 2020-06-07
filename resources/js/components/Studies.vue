<template>
    <div class="container content studies">
        <div class="row">
            <div class="col ">
                <h3 style="text-align:center;">Cursos</h3>
                <div class="button-add-study">
                    <button type="button" class="btn btn-primary" @click="addStudyModal">Añadir estudios</button>
                </div>
                <div class="courses" >

                    <div v-for="(item, index) in estudios_vue">
                        <div class="card-header" data-toggle="" role="button"
                             aria-controls="collapseExample" aria-expanded="false">
                            <a class="study-name-title"  v-bind:href="studyLinkSublime(item.id)">
                                {{ item.name }}
                            </a>
                            <a class="button-add-course"  @click="addYearModal(item.id)">Añadir curso</a>
                        </div>
                        <ul class=" year-list" v-bind:id="getRefId2(item.id)" >
                            <li class="years-list-item" v-for="hey in auxArray[index]" v-if="hey !== 'vacio'" @click="getSubjectsByYear(hey.id, item.name,hey.start_date,hey.end_date,item.id)">
                                <p class="year-range">{{formatDateYear(hey.start_date)}} - {{formatDateYear(hey.end_date)}}</p>
                                <p class="date-range">{{formatDateFull(hey.start_date)}} - {{formatDateFull(hey.end_date)}}</p>
                            </li>
                        </ul>
                    </div>
                    <br>
                    <p class="empty-text" v-if="showPhotoEmpty">Comienza añadiendo tus estudios y cursos...</p>
                    <img class="empty-img" v-if="showPhotoEmpty" :src="this.route_photo_vue"  alt="profile_photo"/>
                </div>

            </div>

            <div class="col">
                <div id="section-subjects">
                    <h3 style="text-align:center;">Asignaturas</h3>
                    <div class="button-add-subject">
                        <button type="button" v-if="showSubjectsHeader" class="btn btn-primary" @click="addSubjectModal">
                            Añadir asignatura
                        </button>
                    </div>
                    <div class="subjects">
                        <div class="card-header" v-if="showSubjectsHeader">
                            <div class="study-year-title">
                                <a v-bind:href="studyLink()">  {{this.chosenStudy}}  {{formatDateYear(this.yearStartChosen)}} - {{formatDateYear(this.yearEndChosen)}}</a>
                            </div>
                        </div>
                        <div class="subjects-section" v-if="!showPhotoEmptySubjects">
                            <ul class="subjects-list">
                                <li class="subjects-list-item" @click="subjectLink(subject.subject_ID)" v-bind:style="{ borderLeftWidth: 23+'px',borderLeftStyle: 'solid', borderLeftColor: subject.subject_color}"
                                    v-for="subject in subjectsArray">
                                    <p class="item name" >{{subject.subject_name}}</p>
                                    <p class="item period"> {{subject.period_name}}</p>
                                </li>
                            </ul>
                        </div>
                        <p class="empty-text-subjects" v-if="showPhotoEmptySubjects">Elige un curso para ver tus asignaturas...</p>
                        <img class="empty-img-subjects" v-if="showPhotoEmptySubjects" :src="this.route_photo_vue_2"  alt="profile_photo"/>
                    </div>
                    <br>

                </div>
            </div>
        </div>

        <!--/*********************************ADD STUDIES*********************************************/-->
        <div class="modal fade" id="addStudyModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="editNameLabel">Nombre de tu nuevo estudio</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Nombre del estudio</label>
                                <input class="form-control" v-model="studyToAdd" v-on:keyup="cleanMessage" id="name" type="text" name="name"
                                       placeholder="" required>
                                <small v-if="showNameExists"  class="text-danger">
                                    Ya tienes unos estudios con este nombre.
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" :disabled='isDisabled'  @click="addStudy">Guardar</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!--/***********************************************************************************************-->
        <!--/*****************************************ADD YEAR**********************************************/-->
        <div class="modal fade" id="addYearModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="addYearLabel">Nuevo año academico</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="year_academico_start">Fecha de inicio</label>
                                <input class="form-control" v-model="yearToAdd.year_start"
                                       id="year_academico_start" type="date" name="year_academico_star"
                                       placeholder="" v-on:change="cleanMessageDates" required>
                            </div>
                            <div class="form-group">
                                <label for="year_academico_end">Fecha de finalización</label>
                                <input class="form-control" v-model="yearToAdd.year_end"
                                       id="year_academico_end" type="date" name="year_academico_end"
                                       placeholder="" v-on:change="cleanMessageDates" required>
                            </div>
                            <div class="alert alert-danger alert-dismissible fade show" v-if="showYearExists" role="alert">
                                <strong>  Las fechas seleccionadas coinciden con las de otro año academico.</strong>
                            </div>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="showYearGreater">
                                <strong>La fecha de inicio es posterior a la fecha de finalización.</strong>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" :disabled='isDisabledSaveYear' @click="addYear">Guardar</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!--/************************************************************************************************-->
        <!--/***********************************ADD SUBJECT*************************************************/-->
        <div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="addSubjectLabel">Nueva asignatura de <strong>{{this.chosenStudy}} {{formatDateYear(this.yearStartChosen)}}-{{formatDateYear(this.yearEndChosen)}}</strong></h5>
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
                                <label for="subject_period">Selecciona un periodo de este curso <strong> {{formatDateFull(this.yearStartChosen)}}-{{formatDateFull(this.yearEndChosen)}}</strong></label>
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
                                               placeholder="Por ejemplo: 1er trimestre" v-on:keyup="cleanMessagePeriodName" required>
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

    </div>
</template>

<script>
    export default {
        name: "Estudios",
        props:['estudios','route_get_years_by_study','route_get_subjects_by_year',
                'route_add_study','route_add_year','route_add_subject','route_get_studies','route_get_periods_by_year',
                    'route_add_period','route_photo','route_photo_2'],
        created(){
            this.estudios_vue = this.estudios;
            this.route_get_years_by_study_vue = this.route_get_years_by_study ;
            this.route_get_subjects_by_year_vue = this.route_get_subjects_by_year ;
            this.route_add_study_vue = this.route_add_study;
            this.route_add_year_vue = this.route_add_year;
            this.route_add_subject_vue = this.route_add_subject;
            this.route_add_period_vue = this.route_add_period;
            this.route_get_studies_vue = this.route_get_studies;
            this.route_get_periods_by_year_vue = this.route_get_periods_by_year;
            this.route_photo_vue = this.route_photo;
            this.route_photo_vue_2 = this.route_photo_2;
            this.getStudiesArray();
            this.getAcademicYears();
        },
        data(){
            return{
                estudios_vue:'',
                route_get_years_by_study_vue: '',
                yearsArray:{
                    type: Object,
                },
                auxArray:[],
                data:{
                    year:'',
                },
                studiesArray:{
                    studies:[],
                },
                indexArray:0,
                route_get_subjects_by_year_vue:'',
                chosenStudy: '',
                yearStartChosen:'',
                yearEndChosen:'',
                yearIdChosen: {
                    year_id:''
                },
                subjectsArray:'',
                route_add_study_vue:'',
                route_add_year_vue:'',
                route_add_subject_vue:'',
                route_add_period_vue:'',
                route_get_studies_vue:'',
                route_get_periods_by_year_vue:'',
                studyToAdd:'',
                showNameExists:false,
                yearToAdd:{
                    study_id:'',
                    year_start:'',
                    year_end:'',
                },
                showYearExists:false,
                showYearGreater:false,
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
                periodsArray:'',
                show_exists: false,
                showPeriodDatesExist:false,
                showPeriodGreater:false,
                showPeriodNameExist:false,
                showPeriodDatesOutYear: false,
                showPhotoEmpty: false,
                route_photo_vue:'',
                route_photo_vue_2:'',
               // showPhotoEmptySubjects: false,
                chosenStudy_id:'',
            }
        },

        methods:{
            // getRefId(id){
            //     return "#collapse-list" + id;
            // },
            getRefId2(id){
                return "collapse-list" + id;
            },
            getAcademicYears(){
                var url = this.route_get_years_by_study_vue;
                axios.get(url ,{params:this.studiesArray}).then(response => {
                    console.log(response.data.result);
                    if(response.data.result === 'no_studies'){
                        this.showPhotoEmpty = true;
                        console.log('No hay estudios');
                    }else{
                        this.showPhotoEmpty = false;
                        var aux2 = [];
                        response.data.result.forEach(function(valor, indice){
                            aux2.push(valor);
                            console.log('/////////////////');
                            console.log(valor);
                        });
                        this.auxArray = aux2;
                        console.log(this.auxArray);
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                   });
            },
            getStudiesArray(){
                var aux = [];
                this.estudios_vue.forEach(function(valor, indice){
                    aux.push(valor.id);
                });
               this.studiesArray.studies = aux;
            },
            getSubjectsByYear(year_id, study_name, start_date,end_date,study_id){
                this.data.year = year_id;
                var url = this.route_get_subjects_by_year_vue;
                axios.get(url ,{params:this.data}).then(response => {
                    console.log(response.data.result);
                    this.chosenStudy_id = study_id;
                    this.chosenStudy = study_name;
                    this.yearStartChosen = start_date;
                    this.yearEndChosen = end_date;
                    this.yearIdChosen.year_id = year_id;
                    console.log('----------');
                    console.log(this.yearIdChosen);
                    this.subjectsArray = response.data.result;
                    console.log(this.subjectsArray);
                    var elmnt = document.getElementById("section-subjects");
                    elmnt.scrollIntoView();

                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            addStudy(){ //OK
                var url = this.route_add_study_vue;
                axios.post(url ,{study:this.studyToAdd}).then(response => {
                    console.log(response.data.result);
                    this.getStudiesAjax();
                    this.getStudiesArray();
                    this.getAcademicYears();

                    if(response.data.result === 'error_study_exists'){
                        this.showNameExists = true;
                    }else{
                        $('#addStudyModal').modal('hide');
                        this.studyToAdd = '';
                        this.showPhotoEmpty = false;
                    }

                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            addYear(){ //OK
                var url = this.route_add_year_vue;
                axios.post(url ,{params:this.yearToAdd}).then(response => {
                    console.log(response.data.result);
                    this.getStudiesAjax();
                    this.getStudiesArray();
                    this.getAcademicYears();
                    if(response.data.result === 'year_created_ok'){
                        $('#addYearModal').modal('hide');
                    }
                    if(response.data.result === 'solapa_dates'){
                        this.showYearExists = true;
                    }
                    if(response.data.result === 'error_start_date_greater'){
                        this.showYearGreater= true;
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            addSubject(){
                var url = this.route_add_subject_vue;
                axios.post(url ,{params:this.subjectToAdd}).then(response => {
                    console.log(response.data.result);
                    this.getSubjectsByYear( this.data.year,this.chosenStudy,this.yearStartChosen, this.yearEndChosen,this.chosenStudy_id);
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
            addPeriod(){ //TODO AÑADIR PERIODO, AÑADIR TODAS LAS COMPROBACIONES EN PERIOD Y DEJAR LISTO EL MODAL
                var url = this.route_add_period_vue;
                this.periodToAdd.year_id = this.data.year;
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
            getStudiesAjax(){
                var url = this.route_get_studies_vue;
                axios.get(url).then(response => {
                    console.log(response.data.result);
                    this.estudios_vue = response.data.result;
                    this.showPhotoEmpty = false;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            addStudyModal(){
                $('#addStudyModal').modal('show');
                this.studyToAdd = '';
            },
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
            cleanMessage(){
                this.showNameExists = false;
                this.show_exists = false;
            },
            cleanMessageDates(){
                this.showYearExists = false;
                this.showGrater = false;
            },
            addYearModal(study_id){
                this.showYearExists = false;
                this.showGrater = false;
                this.yearToAdd.year_start = '';
                this.yearToAdd.year_end = '';
                this.yearToAdd.study_id = study_id;
                $('#addYearModal').modal('show');
            },
            getPeriodsByYear(){
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
            formatDateYear(date_to_format){
                var date = new Date(date_to_format);
                return date = date.getFullYear();
            },
            formatDateFull(date_to_format){
                var date = new Date(date_to_format);
                return date = date.toLocaleDateString();
            },
            studyLink(){
                return "/study/"+this.chosenStudy_id+'/?year='+this.yearIdChosen.year_id;
            },
            studyLinkSublime(id){
                console.log('olsgsdgfsfg');
                return "/study/" + id;
            },
            subjectLink(subject_ID){
                //return "/study/"+this.chosenStudy_id+'/subject/'+subject_ID;
                window.location ="/study/"+this.chosenStudy_id+'/subject/'+subject_ID;
            }
        },
        computed:{
            showSubjectsHeader: function(){
                return this.chosenStudy !== '';
            },
            isDisabled: function(){
                if(this.studyToAdd === ''){
                    return true;
                }else{
                    return false;
                }
            },
            isDisabledSaveYear: function(){
                if(this.yearToAdd.year_start === '' || this.yearToAdd.year_end === ''){
                    return true;
                }else{
                    return false;
                }
            },
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
            showPhotoEmptySubjects: function(){
                if(this.subjectsArray.length=== 0){
                    return true;
                }
                return false;
            },
            // formatDate: function(fecha){
            //
            //     return fecha;
            // }
        }

    }
</script>

<style scoped>
</style>
