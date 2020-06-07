<template>
    <div id="test-add-class" class="container agenda-page" :class="useTheme && subject_page">
    <div class="container content planner">
        <div class="row">
            <div class="col">

<!--                <h3 class="planner-title">Agenda de {{study_prop_vue.name}}</h3>-->
                <h4 class="planner-title">Agenda de {{checkElementName}}</h4>
                <div class="checkbox-show-old">
                    <input type="checkbox" id="checkbox-planner-tag" @change="getEventsListByPageType" v-model="infoEvents.show_old">
                    <label for="checkbox-planner-tag">Mostrar eventos anteriores.</label>
                </div>

                <button type="button" class="btn btn-primary add-event" @click="addEventModal">Añadir un evento</button>
                <div class="custom-card-event" v-for="event in pageOfItems" :key="event.id">
                    <div class="card w-100">
                        <div class="card-body" v-bind:style="{ borderRightWidth: 20+'px',borderRightStyle: 'solid', borderRightColor: event.subject_color}">
                            <h5 class="card-title event-date">{{formatDateFull(event.date)}}</h5>
                            <h5 class="card-title event-subject-name" >{{event.subject_name}}</h5>
                            <h5 class="card-title event-name" >{{event.name}}</h5>
                            <p class="card-text event-description">{{event.description}}</p>
                            <p class="card-text event-time" > Hora: {{formatTime(event.time)}} - Aula: {{event.classroom}}</p>
                            <div class="actions-planner-events">
                                <div class="edit-event-div" @click="editEventModal(event)"></div>
                                <div class="delete-event-div" @click="deleteEventModal(event.id,event.name)"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="empty-event-photo" v-if="showImgEmptyAgenda">
                    <img  class="empty-img-events" :src="this.routeImgEvents"  alt="empty_studies_events"/>
                    <h5>Apunta tus examenes, entregas, presentaciones...</h5>
                </div>
                <div class="card-footer pb-0 pt-3 w-100">
                    <jw-pagination :items="planner_events_vue" :pageSize="3" @changePage="onChangePage"></jw-pagination>
                </div>
            </div>
        </div>

        <!--/*********************************ADD EVENT****************************************/-->
        <div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="editNameLabel">Nuevo evento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Nombre del evento:</label>
                                <input class="form-control" v-model="eventToAdd.name" v-on:keyup="cleanMessage" id="name" type="text" name="name"
                                       placeholder="" required>
                                <small v-if="showNameExists"  class="text-danger">
                                    Ya tienes un evento con este nombre en la asignatura escogida.
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="description">Descripción:</label>
                                <input class="form-control" v-model="eventToAdd.description" v-on:keyup="cleanMessage" id="description" type="text" name="description"
                                       placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="subject">Asignatura relacionada:</label>
                                <select id="subject" v-model="eventToAdd.subject_id">
                                    <option value="">Asignatura para este evento</option>
                                    <option v-for="subject in subjectsArray" v-bind:value="subject.subject_id">
                                        {{subject.subject_name}} - {{subject.period_name}} - {{subject.study_name}}
                                    </option>
                                </select><!--                                       placeholder="" required>-->
                            </div>
                            <div class="form-group">
                                <label for="classroom">Lugar:</label>
                                <input class="form-control" v-model="eventToAdd.classroom" v-on:keyup="cleanMessage" id="classroom" type="text" name="classroom"
                                       placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="time">Hora:</label>
                                <input class="form-control" v-model="eventToAdd.time" v-on:keyup="cleanMessage" id="time" type="time" name="time"
                                       placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="date">Fecha:</label>
                                <input class="form-control" v-model="eventToAdd.date" v-on:keyup="cleanMessage" id="date" type="date" name="date"
                                       placeholder="" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" :disabled='isDisabled'  @click="addEvent">Guardar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!--/***********************************************************************************************-->
        <!--/****************************************EDIT EVENT*********************************************/-->
        <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="editNameLabel2">Edición del evento</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name-edit">Nombre del evento:</label>
                                <input class="form-control" v-model="eventToEdit.name" v-on:keyup="cleanMessage" id="name-edit" type="text" name="name"
                                       placeholder="" required>
                                <small v-if="showNameExistsEdit"  class="text-danger">
                                    Ya tienes un evento con este nombre en la asignatura escogida.
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="description-edit">Descripción:</label>
                                <input class="form-control" v-model="eventToEdit.description" v-on:keyup="cleanMessage" id="description-edit" type="text" name="description"
                                       placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="subject-edit">Asignatura relacionada:</label>
                                <select id="subject-edit" v-model="eventToEdit.subject_id">
                                    <option  value="">Asignatura para este evento</option>
                                    <option v-for="subject in subjectsArray" v-bind:value="subject.subject_id">
                                        {{subject.subject_name}} - {{subject.period_name}} - {{subject.study_name}}
                                    </option>
                                </select><!--                                       placeholder="" required>-->
                            </div>
                            <div class="form-group">
                                <label for="classroom-edit">Lugar:</label>
                                <input class="form-control" v-model="eventToEdit.classroom" v-on:keyup="cleanMessage" id="classroom-edit" type="text" name="classroom"
                                       placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="time-edit">Hora:</label>
                                <input class="form-control" v-model="eventToEdit.time" v-on:keyup="cleanMessage" id="time-edit" type="time" name="time"
                                       placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="date-edit">Fecha</label>
                                <input class="form-control" v-model="eventToEdit.date" v-on:keyup="cleanMessage" id="date-edit" type="date" name="date"
                                       placeholder="" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" :disabled='isDisabledEdit'  @click="editEvent">Guardar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!--/***********************************************************************************************-->
        <!--/*********************************DELETE EVENT*********************************************/-->
        <div class="modal fade" id="deleteEventModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="editNameLabel3">¿Seguro que quieres borrar este evento?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <h5>{{eventToDeleteName}}</h5>
                            </div>
                            <small v-if="showDeleteFail"  class="text-danger">
                                No se ha podido borrar este evento, vuelve a intentarlo.
                            </small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger"  @click="deleteEvent">Eliminar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!--/***********************************************************************************************-->
    </div>
    </div>
</template>

<script>
    export default {
        name: "PlannerTag",
        props:['planner_events','route_add_event','route_get_events','route_get_subjects_by_user',
            'route_edit_event','route_delete_event','route_update_old_events','study_prop','route_get_events_by_study',
            'page_type','route_get_events_by_subject','subject_prop','route_base_images'],
        created(){
            //this.planner_events_vue = this.planner_events;
            this.route_add_event_vue = this.route_add_event;
            this.route_get_events_vue = this.route_get_events;
            this.route_get_subjects_by_user_vue = this.route_get_subjects_by_user;
            this.route_edit_event_vue = this.route_edit_event;
            this.route_delete_event_vue = this.route_delete_event;
            this.route_update_old_events_vue = this.route_update_old_events;
            this.route_get_events_by_study_vue = this.route_get_events_by_study;
            this.study_prop_vue = this.study_prop;
            this.subject_prop_vue = this.subject_prop;
            this.page_type_vue = this.page_type;
            this.route_get_events_by_subject_vue = this.route_get_events_by_subject;
            this.route_base_images_vue = this.route_base_images;
            this.routeImgEvents = this.route_base_images_vue +"/content/clip-bio-technologies_v3.png";
            this.getSubjects();
            //this.getEvents();
            this.updateOldEvents();
            // this.getEventsByStudy();
            this.getEventsListByPageType();
            this.exampleItems =  this.planner_events_vue;
            this.addClass();
        },
        data(){
            return{
                planner_events_vue:'',
                route_add_event_vue:'',
                eventToAdd:{
                    subject_id:'',
                    name:'',
                    description:'',
                    classroom:'',
                    time:'',
                    date:'',
                },
                eventToEdit:{
                    event_id:'',
                    subject_id:'',
                    name:'',
                    description:'',
                    classroom:'',
                    time:'',
                    date:'',
                },
                showNameExists:'',
                showNameExistsEdit:'',
                route_get_events_vue:'',
                route_get_subjects_by_user_vue:'',
                subjectsArray:[],
                route_edit_event_vue:'',
                eventToDelete:{
                    event_id:'',
                },
                eventToDeleteName:'',
                showDeleteFail:false,
                checked: {
                    show_old:false,
                },
                route_update_old_events_vue:'',
                /*********/
                exampleItems:'',
                pageOfItems: [],
                /*********/
                route_get_events_by_study_vue:'',
                infoEvents:{
                    study_id:'',
                    show_old:false,
                    subject_id:'',
                },
                study_prop_vue:'',
                page_type_vue:'',
                route_get_events_by_subject_vue:'',
                subject_prop_vue:'',
                useTheme: false,
                subject_page:'subject-page',
                routeImgEvents:'',
                route_base_images_vue:'',
            }
        },
        methods:{
            formatTime(time){
                var string = time;
                string = string.substring(0,5);
                return string;
            },
            addClass(){
                if(this.subject_prop_vue != null){
                    this.useTheme = true;
                }else{
                    this.useTheme = false;
                }
            },
            getEventsListByPageType(){
                if(this.page_type_vue === 'study'){
                    this.getEventsByStudy();
                }
                if(this.page_type_vue === 'dashboard'){
                    this.getEvents();
                }
                if(this.page_type_vue === 'subject'){
                    this.getEventsBySubject();
                }
            },
            onChangePage(pageOfItems) {
                // update page of items
                this.pageOfItems = pageOfItems;
            },
            cleanMessage(){
                this.showNameExists = false;
                this.showNameExistsEdit = false;
            },
            formatDateFull(date_to_format){
                var date = new Date(date_to_format);
                var options = {   weekday: 'short',year: 'numeric', month: 'short', day: 'numeric' };
                return date = date.toLocaleDateString("es-ES",options);
            },
            getEvents(){
                //console.log(this.checked)
                var url = this.route_get_events_vue;
                axios.get(url,{params:this.infoEvents}).then(response => {
                    console.log(response.data.result);
                    this.planner_events_vue = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            getEventsByStudy(){
                this.infoEvents.study_id = this.study_prop_vue.id;
                var url = this.route_get_events_by_study_vue;
                axios.get(url,{params:this.infoEvents}).then(response => {
                    console.log(response.data.result);
                    this.planner_events_vue = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            getEventsBySubject(){
                this.infoEvents.subject_id = this.subject_prop_vue.subject_ID;
                var url = this.route_get_events_by_subject_vue;
                axios.get(url,{params:this.infoEvents}).then(response => {
                    console.log(response.data.result);
                    this.planner_events_vue = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            addEvent(){
                var url = this.route_add_event_vue;
                axios.post(url ,{event_planner:this.eventToAdd}).then(response => {
                    console.log(response.data.result);
                    if(response.data.result === 'error_event_exists'){
                        this.showNameExists = true;
                    }
                    if(response.data.result === 'event_created'){
                        $('#addEventModal').modal('hide');
                        this.updateOldEvents();
                        // this.getEventsByStudy();
                        this.getEventsListByPageType();
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            editEvent(){
                var url = this.route_edit_event_vue;
                axios.put(url ,{event_planner:this.eventToEdit}).then(response => {
                    console.log(response.data.result);
                    if(response.data.result === 'event_edited'){
                        $('#editEventModal').modal('hide');
                        this.updateOldEvents();
                        // this.getEventsByStudy();
                        this.getEventsListByPageType();
                    }
                    if(response.data.result === 'error_event_exists'){
                        this.showNameExistsEdit = true;
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            deleteEvent(){
                var url = this.route_delete_event_vue;
                axios.delete(url,{params:this.eventToDelete}).then(response => {
                    console.log(response.data.result)
                    if(response.data.result === 'event_deleted'){
                        $('#deleteEventModal').modal('hide');
                        this.updateOldEvents();
                        // this.getEventsByStudy();
                        this.getEventsListByPageType();
                    }
                    if(response.data.result === 'error_delete_event'){
                        this.showDeleteFail = true;
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            addEventModal(){
                this.getSubjects();
                $('#addEventModal').modal('show');
                this.eventToAdd.name='';
                this.eventToAdd.description='';
                this.eventToAdd.date='';
                this.eventToAdd.classroom='';
                this.eventToAdd.subject_id= this.subject_prop_vue.subject_ID;
                this.eventToAdd.time='';

            },
            editEventModal(event){
                $('#editEventModal').modal('show');
                this.eventToEdit.event_id = event.id;
                this.eventToEdit.name = event.name;
                this.eventToEdit.description = event.description;
                this.eventToEdit.date = event.date;
                this.eventToEdit.classroom = event.classroom;
                this.eventToEdit.subject_id = event.subject_id;
                this.eventToEdit.time = event.time;

            },
            deleteEventModal(id,name){
                this.eventToDelete.event_id = id;
                this.eventToDeleteName = name;
                this.showDeleteFail = false;
                $('#deleteEventModal').modal('show');

            },
            getSubjects(){
                var url = this.route_get_subjects_by_user_vue;
                axios.get(url).then(response => {
                    console.log(response.data.result);
                    this.subjectsArray = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            updateOldEvents(){
                var url = this.route_update_old_events_vue;
                axios.put(url).then(response => {
                    console.log(response.data.result);
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            }
        },
        computed:{
            isDisabled: function(){
                if(this.eventToAdd.name === '' || this.eventToAdd.time === '' || this.eventToAdd.date === ''
                    || this.eventToAdd.subject_id === ''){
                    return true;
                }else{
                    return false;
                }
            },
            isDisabledEdit: function(){
                if(this.eventToEdit.name === '' || this.eventToEdit.time === '' || this.eventToEdit.date === ''
                    || this.eventToEdit.subject_id === ''){
                    return true;
                }else{
                    return false;
                }
            },
            checkElementName(){
               if(this.subject_prop_vue != null ){
                   return this.subject_prop_vue.subject_name;
               }
               if(this.study_prop_vue != null ){
                  return this.study_prop_vue.name;
               }
            },
            showImgEmptyAgenda(){
                if(this.planner_events_vue.length == 0){
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
