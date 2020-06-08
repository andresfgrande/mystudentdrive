<template>
    <div class="container content schedules dashboard">
        <h4 class="title-schedules">Proximas clases</h4>
        <div class="classes-today">
            <h5 class="title-schedules-dashboard">Hoy</h5>

            <div class="custom-card-event" v-for="today_class in classes.today">
                <div class="card w-100">
                    <div class="card-body" v-bind:style="{ borderWidth: 3+'px',borderStyle: 'solid', borderColor: today_class.subject_color}">
                        <h5 class="card-title" >{{today_class.class_name}}</h5>
                        <h5 class="card-title subject-name" >{{today_class.subject_name}}</h5>
                        <p class="card-text classroom">Aula: {{today_class.class_classroom}}</p>
                        <div class="actions-schedules">
                            <div class="edit-classe-div" @click="editClasseModal(today_class)"></div>

                        </div>
                        <p class="card-text time" > {{formatTime(today_class.class_start_time)}} - {{formatTime(today_class.class_end_time)}}</p>
                    </div>
                </div>
            </div>

            <div v-if="showImgTodayEmpty">
                <img  class="empty-img-classes" :src="this.routeImgEmptyClasses"  alt="empty_studies_events"/>
                <p class="text-no-classes">¡No hay clases!</p>
            </div>

        </div>
        <div class="classes_tomorrow">
            <h5 class="title-schedules-dashboard">Mañana</h5>

            <div class="custom-card-event" v-for="tomorrow_class in classes.tomorrow">
                <div class="card w-100">
                    <div class="card-body" v-bind:style="{ borderWidth: 3+'px',borderStyle: 'solid', borderColor: tomorrow_class.subject_color}">
                        <h5 class="card-title" >{{tomorrow_class.class_name}}</h5>
                        <h5 class="card-title subject-name" >{{tomorrow_class.subject_name}}</h5>
                        <p class="card-text classroom">Aula: {{tomorrow_class.class_classroom}}</p>
                                 <div class="actions-schedules">
                                      <div class="edit-classe-div" @click="editClasseModal(tomorrow_class)"></div>
<!--                                      <div class="delete-classe-div" @click="deleteClasseModal(tomorrow_class)"></div>-->
                                 </div>
                        <p class="card-text time" > {{formatTime(tomorrow_class.class_start_time)}} - {{formatTime(tomorrow_class.class_end_time)}}</p>
                    </div>
                </div>
            </div>

            <div v-if="showImgTomorrowEmpty">
                <img  class="empty-img-classes" :src="this.routeImgEmptyClasses"  alt="empty_studies_events"/>
                <p class="text-no-classes">¡No hay clases!</p>
            </div>

        </div>

        <!--/*********************************EDIT CLASSE*********************************************/-->
        <div class="modal fade" id="editClasseModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="editNameLabel3">Edición de clase</h5>
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
                                       placeholder="" required v-on:keyup="cleanMessageClasseEdit" >
                                <small v-if="showNameExistsClasseEdit"  class="text-danger" >
                                    Ya tienes una clase con este nombre.
                                </small>
                            </div>
<!--                            <div class="form-group">-->
<!--                                <label for="class_subject_edit" style="display:block">Selecciona una asignatura para esta clase:</label>-->
<!--                                <select id="class_subject_edit" v-model="classeToEdit.subject_id"  >-->
<!--                                    <option selected value> Asignatura </option>-->
<!--                                    <option v-for="subject in subjectsArray" v-bind:value="subject.id">-->
<!--                                        {{subject.name}}-->
<!--                                    </option>-->
<!--                                </select>-->
<!--                            </div>-->
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
                                    <label class="form-check-label" for="inlineCheckbox13">Miercoles</label>
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
        name: "SchedulesTagDashboard",
        props:['route_get_years_by_study','route_get_subjects_by_year',
            'route_add_study','route_add_year','route_add_subject','route_get_studies','route_get_periods_by_year',
            'route_add_period','route_photo','route_photo_2','route_get_schedules_by_period',
            'route_get_classes_by_schedule_and_day','route_get_recent_schedule_by_user','route_add_schedule',
            'route_add_classe','route_get_subjects_by_period','route_edit_classe', 'route_delete_classe',
            'route_delete_schedule','route_get_classes_dashboard','route_base_images'],
        created(){
            this.route_get_recent_schedule_by_user_vue = this.route_get_recent_schedule_by_user;
            this.route_get_classes_dashboard_vue = this.route_get_classes_dashboard;
            this.route_edit_classe_vue = this.route_edit_classe;
            this.route_base_images_vue = this.route_base_images;
            this.routeImgEmptyClasses = this.route_base_images_vue +"/content/clip-log-out_v3.png";
            this.getRecentSchedule();
        },
        data(){
            return{
                route_get_recent_schedule_by_user_vue:'',
                route_get_classes_dashboard_vue:'',
                route_edit_classe_vue:'',
                route_base_images_vue:'',
                recentSchedule:'',
                classesToSearch:{
                    schedule_id:'',
                    today:'',
                    tomorrow:'',
                },
                classes:[],
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
                showNameExistsClasseEdit: false,
                routeImgEmptyClasses:'',
            }
        },
        methods:{
            editClasseModal(classe){
               // this.getSubjectsByPeriod();
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
                // console.log('hola');
                var url = this.route_edit_classe_vue;
                axios.put(url ,{params:this.classeToEdit}).then(response => {
                    console.log(response.data.result);
                    if(response.data.result === 'error_classe_exists'){
                        this.showNameExistsClasseEdit = true;
                    }
                    if(response.data.result === 'classe_edited'){
                        $('#editClasseModal').modal('hide');
                        this.getClassesDashboard();
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
                    this.classesToSearch.schedule_id = this.recentSchedule.schedule_id;
                    this.getClassesDashboard();
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            getClassesDashboard(){
                var options = {weekday: 'long'};
                var today = new Date();
                today = today.toLocaleDateString("es-ES",options);
                this.classesToSearch.today = today;

                var today_aux = new Date();
                var tomorrow = new Date(today_aux);
                tomorrow.setDate(tomorrow.getDate() + 1);
                tomorrow = tomorrow.toLocaleDateString("es-ES",options);
                this.classesToSearch.tomorrow = tomorrow;

                var url = this.route_get_classes_dashboard_vue;
                axios.get(url,{params:this.classesToSearch}).then(response => {
                    console.log(response.data.result);
                    this.classes = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            formatTime(time){
                var string = time;
                string = string.substring(0,5);
                return string;
            },
            cleanMessageClasseEdit(){
                this.showNameExistsClasseEdit = false;
            },
        },
        computed:{
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
            },
            showImgTodayEmpty(){
                if (typeof this.classes.today !== 'undefined'){
                    if(this.classes.today.length === 0){
                        return true;
                    }else{
                        return false
                    }
                }else{
                    return true;
                }

            },
            showImgTomorrowEmpty(){
                if (typeof this.classes.tomorrow !== 'undefined'){
                    if(this.classes.tomorrow.length === 0){
                        return true;
                    }else{
                        return false
                    }
                }else{
                    return true;
                }
            },
        },
    }
</script>

<style scoped>

</style>
