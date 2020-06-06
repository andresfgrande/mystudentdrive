<template>
    <div class="container content studies schedules">
        <h3 class="page-title">Mis horarios</h3>
        <div class="row">
            <div class="col-4">
                <h4 class="cursos-title">Cursos</h4>
<!--                <div class="button-add-study">-->
<!--                    <button type="button" class="btn btn-primary" @click="addStudyModal">Añadir estudios</button>-->
<!--                </div>-->
                <div class="courses" >

                    <div v-for="(item, index) in estudios_vue">
                        <div class="card-header" data-toggle="" role="button"
                             aria-controls="collapseExample" aria-expanded="false">
                            <a class="study-name-title"  >
                                {{ item.name }}
                            </a>
<!--                            <a class="button-add-course"  @click="addYearModal(item.id)">Añadir curso</a>-->
                        </div>
                        <ul class=" year-list"  >
                            <li class="years-list-item" v-for="year in auxArray[index]" v-if="year !== 'vacio'"
                                @click="getPeriodsByYear(year.id, item.name, year.start_date, year.end_date)" >
                                <p class="year-range">{{formatDateYear(year.start_date)}} - {{formatDateYear(year.end_date)}}</p>
                                <p class="date-range">{{formatDateFull(year.start_date)}} - {{formatDateFull(year.end_date)}}</p>
                            </li>
                        </ul>
                    </div>
                    <br>
<!--                    <p class="empty-text" v-if="showPhotoEmpty">Comienza añadiendo tus estudios y cursos...</p>-->
<!--                    <img class="empty-img" v-if="showPhotoEmpty" :src="this.route_photo_vue"  alt="profile_photo"/>-->
                </div>

            </div>
            <div class="col-8">
                <h4 class="study-name-title">{{chosenStudyName}}</h4>
                <h4 class="study-name-title year">{{formatDateYear(chosenYearStart)}} - {{formatDateYear(chosenYearEnd)}}</h4>
                <div class="select-container-period">
                    <label for="schedule-period" style="display:block">Selecciona un periodo de este curso:</label>
                    <select id="schedule-period" v-model="periodToSearch.period_id" @change="getSchedulesByPeriod" >
                        <option disabled selected value> Periodo </option>
                        <option v-for="period in periodsArray" v-bind:value="period.id">
                            {{period.name}}  ({{formatDateFull(period.start_date)}} - {{formatDateFull(period.end_date)}})
                        </option>
                    </select>
                </div>

                <div class="select-container-schedule">
                    <label for="specific-schedule" style="display:block">Selecciona un horario de este periodo:</label>
                    <select id="specific-schedule" v-model="classesToSearch.schedule_id" @change="getClassesByScheduleAndDay" >
                        <option disabled selected value> Horario </option>
                        <option v-for="schedule in schedulesArray" v-bind:value="schedule.id">
                            {{schedule.name}}
                        </option>
                    </select>
                </div>

                <div class="container-buttons">
                    <button @click="addScheduleModal" class="btn btn-primary">Añadir horario</button>
                    <button @click="addClasseModal" class="btn btn-primary">Añadir Clase</button>
                </div>


                <div class="row custom">
                    <div class="col">
                        <p>Lunes</p>

                        <div class="custom-card-event" v-for="monday_class in classes.monday">
                            <div class="card w-100">
                                <div class="card-body" v-bind:style="{ borderWidth: 3+'px',borderStyle: 'solid', borderColor: monday_class.subject_color}">
                                    <div class="actions-schedules">
                                        <div class="edit-classe-div" @click="editClasseModal(monday_class)"></div>
                                        <div class="delete-classe-div"></div>
                                    </div>
                                    <h5 class="card-title" >{{monday_class.class_name}}</h5>
                                    <h5 class="card-title subject-name" >{{monday_class.subject_name}}</h5>
                                    <p class="card-text classroom">Aula: {{monday_class.class_classroom}}</p>
                                    <p class="card-text time" > {{formatTime(monday_class.class_start_time)}} - {{formatTime(monday_class.class_end_time)}}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col">
                        <p>Martes</p>
                        <div class="custom-card-event" v-for="tuesday_class in classes.tuesday">
                            <div class="card w-100">
                                <div class="card-body" v-bind:style="{ borderWidth: 3+'px',borderStyle: 'solid', borderColor: tuesday_class.subject_color}">
                                    <div class="actions-schedules">
                                        <div class="edit-classe-div" @click="editClasseModal(tuesday_class)"></div>
                                        <div class="delete-classe-div"></div>
                                    </div>
                                    <h5 class="card-title" >{{tuesday_class.class_name}}</h5>
                                    <h5 class="card-title subject-name" >{{tuesday_class.subject_name}}</h5>
                                    <p class="card-text classroom">Aula: {{tuesday_class.class_classroom}}</p>
                                    <p class="card-text time" > {{formatTime(tuesday_class.class_start_time)}} - {{formatTime(tuesday_class.class_end_time)}} </p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col">
                        <p>Miercoles</p>
                        <div class="custom-card-event" v-for="wednesday_class in classes.wednesday">
                            <div class="card w-100">
                                <div class="card-body" v-bind:style="{ borderWidth: 3+'px',borderStyle: 'solid', borderColor: wednesday_class.subject_color}">
                                    <div class="actions-schedules">
                                        <div class="edit-classe-div" @click="editClasseModal(wednesday_class)"></div>
                                        <div class="delete-classe-div"></div>
                                    </div>
                                    <h5 class="card-title" >{{wednesday_class.class_name}}</h5>
                                    <h5 class="card-title subject-name" >{{wednesday_class.subject_name}}</h5>
                                    <p class="card-text classroom">Aula: {{wednesday_class.class_classroom}}</p>
                                    <p class="card-text time" > {{formatTime(wednesday_class.class_start_time)}} - {{formatTime(wednesday_class.class_end_time)}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <p>Jueves</p>
                        <div class="custom-card-event" v-for="thursday_class in classes.thursday">
                            <div class="card w-100">
                                <div class="card-body" v-bind:style="{ borderWidth: 3+'px',borderStyle: 'solid', borderColor: thursday_class.subject_color}">
                                    <div class="actions-schedules">
                                        <div class="edit-classe-div" @click="editClasseModal(thursday_class)"></div>
                                        <div class="delete-classe-div"></div>
                                    </div>
                                    <h5 class="card-title" >{{thursday_class.class_name}}</h5>
                                    <h5 class="card-title subject-name" >{{thursday_class.subject_name}}</h5>
                                    <p class="card-text classroom">Aula: {{thursday_class.class_classroom}}</p>
                                    <p class="card-text time" > {{formatTime(thursday_class.class_start_time)}} - {{formatTime(thursday_class.class_end_time)}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <p>Viernes</p>
                        <div class="custom-card-event" v-for="friday_class in classes.friday">
                            <div class="card w-100">
                                <div class="card-body" v-bind:style="{ borderWidth: 3+'px',borderStyle: 'solid', borderColor: friday_class.subject_color}">
                                    <div class="actions-schedules">
                                        <div class="edit-classe-div" @click="editClasseModal(friday_class)"></div>
                                        <div class="delete-classe-div"></div>
                                    </div>
                                    <h5 class="card-title" >{{friday_class.class_name}}</h5>
                                    <h5 class="card-title subject-name" >{{friday_class.subject_name}}</h5>
                                    <p class="card-text classroom">Aula: {{friday_class.class_classroom}}</p>
                                    <p class="card-text time" > {{formatTime(friday_class.class_start_time)}} - {{formatTime(friday_class.class_end_time)}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!--/*********************************ADD SCHEDULES*********************************************/-->
        <div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="editNameLabel">Nuevo Horario de {{chosenStudyName}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Nombre del horario</label>
                                <input class="form-control" v-model="scheduleToAdd.name" v-on:keyup="cleanMessageSchedule" id="name" type="text" name="name"
                                       placeholder="" required>
                                <small v-if="showNameExistsSchedule"  class="text-danger">
                                    Ya tienes un horario con este nombre.
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="schedule-period-input" style="display:block">Selecciona un periodo para este horario:</label>
                                <select id="schedule-period-input" v-model="scheduleToAdd.period_id">
                                    <option disabled selected value> Periodo </option>
                                    <option v-for="period in periodsArray" v-bind:value="period.id">
                                        {{period.name}}  ({{formatDateFull(period.start_date)}} - {{formatDateFull(period.end_date)}})
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" :disabled='isDisabled'  @click="addSchedule">Guardar</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!--/***********************************************************************************************-->
        <!--/*********************************ADD CLASSE*********************************************/-->
        <div class="modal fade" id="addClasseModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="editNameLabel2">Nueva clase de {{chosenStudyName}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name_classe">Nombre de la clase:</label>
                                <input class="form-control" v-model="classeToAdd.name"
                                       id="name_classe" type="text" name="name_classe"
                                       placeholder="" required v-on:keyup="cleanMessageClasse">
                                <small v-if="showNameExistsClasse"  class="text-danger">
                                    Ya tienes una clase con este nombre.
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="class_subject" style="display:block">Selecciona una asignatura para esta clase:</label>
                                <select id="class_subject" v-model="classeToAdd.subject_id"  >
                                    <option selected value> Asignatura </option>
                                    <option v-for="subject in subjectsArray" v-bind:value="subject.id">
                                        {{subject.name}}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="classroom">Lugar:</label>
                                <input class="form-control" v-model="classeToAdd.classroom"
                                       id="classroom" type="text" name="classroom"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="start_time">Hora inicio:</label>
                                <input class="form-control" v-model="classeToAdd.start_time"
                                       id="start_time" type="time" name="start_time"
                                        required>
                            </div>
                            <div class="form-group">
                                <label for="end_time">Hora final:</label>
                                <input class="form-control" v-model="classeToAdd.end_time"
                                       id="end_time" type="time" name="end_time"
                                       required>
                            </div>
                            <div class="form-group">
                                <p>Dias:</p>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" v-model="classeToAdd.monday">
                                    <label class="form-check-label" for="inlineCheckbox1">Lunes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" v-model="classeToAdd.tuesday">
                                    <label class="form-check-label" for="inlineCheckbox2">Martes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" v-model="classeToAdd.wednesday">
                                    <label class="form-check-label" for="inlineCheckbox3">Miercoles</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox4" v-model="classeToAdd.thursday">
                                    <label class="form-check-label" for="inlineCheckbox4">Jueves</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox5" v-model="classeToAdd.friday">
                                    <label class="form-check-label" for="inlineCheckbox5">Viernes</label>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" :disabled="isDisabledSaveClasse" @click="addClasse">Guardar</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!--/***********************************************************************************************-->
        <!--/*********************************EDIT CLASSE*********************************************/-->
        <div class="modal fade" id="editClasseModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="editNameLabel3">Edición de clase de {{chosenStudyName}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name_classe_edit">Nombre de la clase:</label>
                                <input class="form-control" v-model="classeToEdit.name"
                                       id="name_classe_edit" type="text" name="name_classe"
                                       placeholder="" required v-on:keyup="cleanMessageClasseEdit">
                                <small v-if="showNameExistsClasseEdit"  class="text-danger">
                                    Ya tienes una clase con este nombre.
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="class_subject_edit" style="display:block">Selecciona una asignatura para esta clase:</label>
                                <select id="class_subject_edit" v-model="classeToEdit.subject_id"  >
                                    <option selected value> Asignatura </option>
                                    <option v-for="subject in subjectsArray" v-bind:value="subject.id">
                                        {{subject.name}}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="classroom_edit">Lugar:</label>
                                <input class="form-control" v-model="classeToEdit.classroom"
                                       id="classroom_edit" type="text" name="classroom"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="start_time_edit">Hora inicio:</label>
                                <input class="form-control" v-model="classeToEdit.start_time"
                                       id="start_time_edit" type="time" name="start_time"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="end_time_edit">Hora final:</label>
                                <input class="form-control" v-model="classeToEdit.end_time"
                                       id="end_time_edit" type="time" name="end_time"
                                       required>
                            </div>
                            <div class="form-group">
                                <p>Dias:</p>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox10" v-model="classeToEdit.monday">
                                    <label class="form-check-label" for="inlineCheckbox10">Lunes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox12" v-model="classeToEdit.tuesday">
                                    <label class="form-check-label" for="inlineCheckbox12">Martes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox13" v-model="classeToEdit.wednesday">
                                    <label class="form-check-label" for="inlineCheckbox3">Miercoles</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox14" v-model="classeToEdit.thursday">
                                    <label class="form-check-label" for="inlineCheckbox14">Jueves</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox15" v-model="classeToEdit.friday">
                                    <label class="form-check-label" for="inlineCheckbox15">Viernes</label>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" :disabled="isDisabledEditClasse" @click="editClasse">Guardar</button>
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
        name: "Schedules",
        props:['estudios','route_get_years_by_study','route_get_subjects_by_year',
            'route_add_study','route_add_year','route_add_subject','route_get_studies','route_get_periods_by_year',
            'route_add_period','route_photo','route_photo_2','route_get_schedules_by_period',
            'route_get_classes_by_schedule_and_day','route_get_recent_schedule_by_user','route_add_schedule',
            'route_add_classe','route_get_subjects_by_period','route_edit_classe'],
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
            this.route_get_schedules_by_period_vue = this.route_get_schedules_by_period;
            this.route_get_classes_by_schedule_and_day_vue = this.route_get_classes_by_schedule_and_day;
            this.route_get_recent_schedule_by_user_vue = this.route_get_recent_schedule_by_user;
            this.route_add_schedule_vue = this.route_add_schedule;
            this.route_add_classe_vue = this.route_add_classe;
            this.route_get_subjects_by_period_vue = this.route_get_subjects_by_period;
            this.route_edit_classe_vue = this.route_edit_classe;
            this.getStudiesArray();
            this.getAcademicYears();
            this.getRecentSchedule();
        },
        data(){
            return{
                estudios_vue:'',
                auxArray:[],
                studiesArray:{
                    studies:[],
                },
                route_get_periods_by_year_vue:'',
                periodsArray:'',
                periodToSearch:{
                    period_id:'',
                },
                yearIdChosen: {
                    year_id:''
                },
                route_get_schedules_by_period_vue:'',
                mondayClasses:[],
                route_get_classes_by_schedule_and_day_vue:'',
                route_get_recent_schedule_by_user_vue:'',
                classesToSearch:{
                    schedule_id:'',
                },
                schedulesArray:[],
                classes:[],
                recentSchedule:'',
                classesToSearchRecent:{
                    schedule_id:'',
                },
                chosenStudyName:'',
                route_add_schedule_vue:'',
                scheduleToAdd:{
                    period_id:'',
                    name:'',
                },
                showNameExistsSchedule: false,
                showNameExistsClasse: false,
                route_add_classe_vue:'',
                classeToAdd:{
                    subject_id:'',
                    schedule_id:'',
                    name:'',
                    start_time:'',
                    end_time:'',
                    classroom:'',
                    monday:false,
                    tuesday:false,
                    wednesday:false,
                    thursday:false,
                    friday:false,
                },
                route_get_subjects_by_period_vue:'',
                subjectsArray:[],
                chosenYearStart:'',
                chosenYearEnd:'',
                classeToEdit:{
                    classe_id:'',
                    subject_id:'',
                    schedule_id:'',
                    name:'',
                    start_time:'',
                    end_time:'',
                    classroom:'',
                    monday:false,
                    tuesday:false,
                    wednesday:false,
                    thursday:false,
                    friday:false,
                },
                route_edit_classe_vue:'',
                showNameExistsClasseEdit: false,
            }
        },
        methods:{
            formatTime(time){
                var string = time;
                string = string.substring(0,5);
                return string;
            },
            addClasseModal(){
                this.getSubjectsByPeriod();
                $('#addClasseModal').modal('show');
                this.classeToAdd.schedule_id = this.classesToSearch.schedule_id;
                this.classeToAdd.name='';
                this.classeToAdd.start_time='';
                this.classeToAdd.end_time='';
                this.classeToAdd.classroom='';
                this.classeToAdd.monday=false;
                this.classeToAdd.tuesday=false;
                this.classeToAdd.wednesday=false;
                this.classeToAdd.thursday=false;
                this.classeToAdd.friday=false;
            },
            editClasseModal(classe){
                this.getSubjectsByPeriod();
                $('#editClasseModal').modal('show');
                this.classeToEdit.classe_id = classe.class_id;
                this.classeToEdit.subject_id = classe.class_subject_id;
                this.classeToEdit.schedule_id = classe.class_schedule_id;
                this.classeToEdit.name = classe.class_name;
                this.classeToEdit.start_time = classe.class_start_time;
                this.classeToEdit.end_time = classe.class_end_time;
                this.classeToEdit.classroom = classe.class_classroom;
                this.classeToEdit.monday = classe.mon;
                this.classeToEdit.tuesday = classe.tue;
                this.classeToEdit.wednesday = classe.wed;
                this.classeToEdit.thursday = classe.thu;
                this.classeToEdit.friday = classe.fri;
            },
            editClasse(){
                var url = this.route_edit_classe_vue;
                axios.put(url ,{params:this.classeToEdit}).then(response => {
                    console.log(response.data.result);
                    if(response.data.result === 'error_classe_exists'){
                        this.showNameExistsClasseEdit = true;
                    }
                    if(response.data.result === 'classe_edited'){
                        $('#editClasseModal').modal('hide');
                        this.getClassesByScheduleAndDay();
                    }

                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            addClasse(){
                var url = this.route_add_classe_vue;
                axios.put(url,{params:this.classeToAdd}).then(response => {
                    console.log(response.data.result);
                    // if(response.data.result === 'classe_created'){
                    //     $('#addClasseModal').modal('hide');
                    //     this.getClassesByScheduleAndDay()
                    // }
                    if(response.data.result === 'error_classe_exists'){
                        this.showNameExistsClasse = true;
                    }
                    if(response.data.result === 'classe_created'){
                            $('#addClasseModal').modal('hide');
                            this.getClassesByScheduleAndDay();
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            addScheduleModal(){
                $('#addScheduleModal').modal('show');
                this.scheduleToAdd.name = '';
                this.scheduleToAdd.period_id = this.periodToSearch.period_id;
            },
            addSchedule(){
                var url = this.route_add_schedule_vue;
                axios.put(url,{params:this.scheduleToAdd}).then(response => {
                    console.log(response.data.result);
                    if(response.data.result === 'error_schedule_exists'){
                        this.showNameExistsSchedule = true;
                    }else{
                        $('#addScheduleModal').modal('hide');
                        this.scheduleToAdd.name = '';
                        this.getSchedulesByPeriod();
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            getRecentSchedule(){
                var url = this.route_get_recent_schedule_by_user_vue;
                axios.get(url).then(response => {
                    console.log(response.data.result);
                    this.recentSchedule = response.data.result[0];
                    this.classesToSearchRecent.schedule_id = this.recentSchedule.schedule_id;
                    this.getRecentClasses();
                    this.getPeriodsByYear(this.recentSchedule.year_id, this.recentSchedule.study_name,
                        this.recentSchedule.year_start, this.recentSchedule.year_end);
                    this.periodToSearch.period_id = this.recentSchedule.schedule_period_id;
                    this.getSubjectsByPeriod();
                    this.getSchedulesByPeriod();
                    this.classesToSearch.schedule_id = this.classesToSearchRecent.schedule_id;

                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            getRecentClasses(){
                var url = this.route_get_classes_by_schedule_and_day_vue;
                axios.get(url,{params:this.classesToSearchRecent}).then(response => {
                    console.log(response.data.result);
                    this.classes = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            getSchedulesByPeriod(){
                var url = this.route_get_schedules_by_period_vue;
                axios.get(url,{params:this.periodToSearch}).then(response => {
                    console.log('/////////////////////');
                    console.log(response.data.result);
                    this.schedulesArray = response.data.result;

                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },

            getClassesByScheduleAndDay(){
                var url = this.route_get_classes_by_schedule_and_day_vue;
                axios.get(url,{params:this.classesToSearch}).then(response => {
                    console.log('////////Classes to seacrh/////////////');
                    console.log(response.data.result);
                    this.classes = response.data.result;

                })
                    .catch(errors => {
                        console.log(errors);
                    });
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
            getPeriodsByYear(year_id, study_name, chosen_year_start, chosen_year_end){
                this.schedulesArray= [];
                this.periodToSearch.period_id = '';
                this.classesToSearch.schedule_id = '';
                /*********************************/
                this.chosenYearStart = chosen_year_start;
                this.chosenYearEnd = chosen_year_end;
                /**********************************/
                this.chosenStudyName = study_name;
                this.yearIdChosen.year_id = year_id;
                var url = this.route_get_periods_by_year_vue;
                axios.get(url,{params:this.yearIdChosen}).then(response => {
                    this.periodsArray = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            formatDateYear(date_to_format){
                var date = new Date(date_to_format);
                return date = date.getFullYear();
            },
            formatDateFull(date_to_format){
                var date = new Date(date_to_format);
                return date = date.toLocaleDateString();
            },
            cleanMessageSchedule(){
                this.showNameExistsSchedule = false;
            },
            cleanMessageClasse(){
                this.showNameExistsClasse = false;
            },
            cleanMessageClasseEdit(){
                this.showNameExistsClasseEdit = false;
            },
            getSubjectsByPeriod(){
                var url = this.route_get_subjects_by_period_vue;
                axios.get(url,{params:this.periodToSearch}).then(response => {
                    console.log(response.data.result);
                    this.subjectsArray = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            }
        },
        computed:{
            isDisabled: function(){
                if(this.scheduleToAdd.name === '' || this.scheduleToAdd.period_id === ''){
                    return true;
                }else{
                    return false;
                }
            },
            isDisabledSaveClasse: function(){

                if(this.classeToAdd.monday === false && this.classeToAdd.tuesday === false && this.classeToAdd.wednesday === false
                        && this.classeToAdd.thursday === false && this.classeToAdd.friday === false){

                    var aux = false;
                }else{
                    var aux = true;
                }

                if(this.classeToAdd.name === '' || this.classeToAdd.subject_id === '' || this.classeToAdd.start_time === ''
                    || this.classeToAdd.end_time === '' || !aux){
                    return true;
                }else{
                    return false;
                }
            },
            isDisabledEditClasse: function(){
                if(this.classeToEdit.monday === false && this.classeToEdit.tuesday === false && this.classeToEdit.wednesday === false
                    && this.classeToEdit.thursday === false && this.classeToEdit.friday === false){

                    var aux = false;
                }else{
                    var aux = true;
                }

                if(this.classeToEdit.name === '' || this.classeToEdit.subject_id === '' || this.classeToEdit.start_time === ''
                    || this.classeToEdit.end_time === '' || !aux){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }
</script>

<style scoped>

</style>
