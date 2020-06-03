<template>
    <div class="container content studies schedules">
        <h4 class="page-title">Mis horarios</h4>
        <div class="row">
            <div class="col-4">

                <h3 style="text-align:center;">Cursos</h3>
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
                            <li class="years-list-item" v-for="year in auxArray[index]" v-if="year !== 'vacio'" @click="getPeriodsByYear(year.id)" >
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
<!--                <label for="schedule-period">Selecciona un periodo de este curso:</label>-->
                <select id="schedule-period" v-model="periodToSearch.period_id" @change="getSchedulesByPeriod" >
                    <option disabled selected value> Selecciona un periodo de este curso: </option>
                    <option v-for="period in periodsArray" v-bind:value="period.id">
                        {{period.name}}  ({{formatDateFull(period.start_date)}} - {{formatDateFull(period.end_date)}})
                    </option>
                </select>

                <select id="specific-schedule" v-model="classesToSearch.schedule_id" @change="getClassesByScheduleAndDay" >
                    <option selected value> Selecciona un periodo de este curso: </option>
                    <option v-for="schedule in schedulesArray" v-bind:value="schedule.id">
                        {{schedule.name}}
                    </option>
                </select>

                <div class="row" style="margin-top: 1em;">
                    <div class="col">
                        <p>Lunes</p>

                        <div class="custom-card-event" v-for="monday_class in classes.monday">
                            <div class="card w-75">
                                <div class="card-body" v-bind:style="{ borderWidth: 3+'px',borderStyle: 'solid', borderColor: monday_class.subject_color}">
                                    <h5 class="card-title" >{{monday_class.class_name}}</h5>
                                    <h5 class="card-title" >{{monday_class.subject_name}}</h5>
                                    <p class="card-text ">{{monday_class.class_classroom}}</p>
                                    <p class="card-text " > {{monday_class.class_start_time}}</p>
                                    <p class="card-text " > {{monday_class.class_end_time}}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col">
                        <p>Martes</p>
                        <div class="custom-card-event" v-for="tuesday_class in classes.tuesday">
                            <div class="card w-75">
                                <div class="card-body" v-bind:style="{ borderWidth: 3+'px',borderStyle: 'solid', borderColor: tuesday_class.subject_color}">
                                    <h5 class="card-title" >{{tuesday_class.class_name}}</h5>
                                    <h5 class="card-title" >{{tuesday_class.subject_name}}</h5>
                                    <p class="card-text ">{{tuesday_class.class_classroom}}</p>
                                    <p class="card-text " > {{tuesday_class.class_start_time}}</p>
                                    <p class="card-text " > {{tuesday_class.class_end_time}}</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col">
                        <p>Miercoles</p>
                        <div class="custom-card-event" v-for="wednesday_class in classes.wednesday">
                            <div class="card w-75">
                                <div class="card-body" v-bind:style="{ borderWidth: 3+'px',borderStyle: 'solid', borderColor: wednesday_class.subject_color}">
                                    <h5 class="card-title" >{{wednesday_class.class_name}}</h5>
                                    <h5 class="card-title" >{{wednesday_class.subject_name}}</h5>
                                    <p class="card-text ">{{wednesday_class.class_classroom}}</p>
                                    <p class="card-text " > {{wednesday_class.class_start_time}}</p>
                                    <p class="card-text " > {{wednesday_class.class_end_time}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <p>Jueves</p>
                        <div class="custom-card-event" v-for="thursday_class in classes.thursday">
                            <div class="card w-75">
                                <div class="card-body" v-bind:style="{ borderWidth: 3+'px',borderStyle: 'solid', borderColor: thursday_class.subject_color}">
                                    <h5 class="card-title" >{{thursday_class.class_name}}</h5>
                                    <h5 class="card-title" >{{thursday_class.subject_name}}</h5>
                                    <p class="card-text event-description">{{thursday_class.class_classroom}}</p>
                                    <p class="card-text " > {{thursday_class.class_start_time}}</p>
                                    <p class="card-text " > {{thursday_class.class_end_time}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <p>Viernes</p><div class="custom-card-event" v-for="friday_class in classes.friday">
                        <div class="card w-75">
                            <div class="card-body" v-bind:style="{ borderWidth: 3+'px',borderStyle: 'solid', borderColor: friday_class.subject_color}">
                                <h5 class="card-title" >{{friday_class.class_name}}</h5>
                                <h5 class="card-title" >{{friday_class.subject_name}}</h5>
                                <p class="card-text event-description">{{friday_class.class_classroom}}</p>
                                <p class="card-text " > {{friday_class.class_start_time}}</p>
                                <p class="card-text " > {{friday_class.class_end_time}}</p>
                            </div>
                        </div>
                    </div>

                    </div>
<!--                    <div class="col">-->
<!--                        <p>Sábado</p>-->
<!--                    </div>-->
<!--                    <div class="col">-->
<!--                        <p>Domingo</p>-->
<!--                    </div>-->
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Schedules",
        props:['estudios','route_get_years_by_study','route_get_subjects_by_year',
            'route_add_study','route_add_year','route_add_subject','route_get_studies','route_get_periods_by_year',
            'route_add_period','route_photo','route_photo_2','route_get_schedules_by_period',
            'route_get_classes_by_schedule_and_day'],
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
            this.getStudiesArray();
            this.getAcademicYears();

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
                classesToSearch:{
                    schedule_id:'',
                    // day:'',
                },
                schedulesArray:[],
                // classes: {
                //     monday:[],
                //     tuesday:[],
                //     wednesday:[],
                //     thursday:[],
                //     friday:[],
                // },
                classes:[],
            }
        },
        methods:{
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
                // this.classesToSearch.day = day;
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
            getPeriodsByYear(year_id){
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
        }
    }
</script>

<style scoped>

</style>
