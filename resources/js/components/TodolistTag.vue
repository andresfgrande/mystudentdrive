<template>
    <div class="container content todo-list dashboard">
        <h4 class="title-todo-list" >Mis tareas</h4>
        <button type="button" class="btn btn-primary" v-if="!adding" style="margin-bottom: 1em;" @click="enableAdding">Añadir tarea</button>

        <div class="form-group form-add-task" v-if="adding">
            <label for="input-description">Descripción de la tarea:</label>
            <input type="text" class="form-control input-description" id="input-description" v-model="taskToAdd.description"
                   aria-describedby="emailHelp" placeholder="Por ejemplo: Comprar libro de biología.">
            <input type="checkbox" id="checkbox-tarea-tag" v-model="taskToAdd.is_urgent" >
            <label for="checkbox-tarea-tag">Marcar como tarea urgente.</label>
            <label for="date" class="label-for-date-event">Fecha de la tarea:</label>
            <input type="date" class="form-control input-date" id="date" v-model="taskToAdd.date" >
            <label for="subject">Asignatura relacionada con esta tarea:</label>
            <select id="subject" class="select-subjects-tasks" v-model="taskToAdd.subject_id">
                <option value="">Tarea general</option>
                <option v-for="subject in subjectsArray" v-bind:value="subject.subject_id">
                    {{subject.subject_name}} - {{subject.period_name}} - {{subject.study_name}}
                </option>
            </select>
            <button type="button" class="btn btn-primary" :disabled="addTaskDisabled" @click="addTask">Añadir tarea</button>
            <button type="button" class="btn btn-secondary" @click="disableAdding">Cancelar</button>

        </div>
        <div class="section-item">
            <ul>
                <li v-for="task in tasksArray" v-bind:style="{ borderLeftWidth: 20+'px',borderLeftStyle: 'solid', borderLeftColor: task.subject_color}">
                    <div class="check-done">
                        <div v-if="task.task_is_urgent" class ="urgent-task-div" :class="task.task_is_done && tachadoImgClass"></div>
                        <input type="checkbox" class="checkbox-task"   @click="taskCheckDone(task.task_id)"
                               checked v-if="task.task_is_done">
                        <input type="checkbox" class="checkbox-task2"   @click="taskCheckDone(task.task_id)"
                               v-if="!task.task_is_done">
                    </div>
                    <div class="task-item">
                            <p class="task_description" :class="task.task_is_done && tachadoClass"
                               @click="editTaskModal(task.task_id,task.task_description,task.task_date,task.task_is_urgent, task.subject_id)"
                            >{{task.task_description}}</p>
                            <p class="task_subject" :class="task.task_is_done && tachadoClass">{{task.subject_name}}</p>
                            <p class="task_date" :class="task.task_is_done && tachadoClass">{{formatDateFull(task.task_date)}}</p>
                        <div class ="delete-task-div" @click="deleteTaskModal(task.task_id,task.task_description)"></div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="empty-tasks-photo" v-if="showImgEmptyTasks">
            <img  class="empty-img-tasks" :src="this.routeImgEmptyTasks"  alt="empty_tasks_list"/>
            <h5 class="description-empty-tasks">¡Apunta todas tus tareas!</h5>
            <h5 class="description-empty-tasks second">Será tu lista de buenos propositos...</h5>
        </div>
<!--        <button @click="editTask('hey')">test edit call</button>-->

        <!--/*********************************EDIT TASK*********************************************/-->
        <div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
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
                                <label for="description-edit">Descripción de la tarea:</label>
                                <input type="text" class="form-control" id="description-edit" v-model="taskToEdit.description"
                                       aria-describedby="emailHelp" placeholder="Por ejemplo: Comprar libro de biología.">
                            </div>
                            <div class="form-group">
                                <label for="date-edit">Fecha de la tarea:</label>
                                <input type="date" class="form-control" id="date-edit" v-model="taskToEdit.date" >
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="checkbox-tarea-tag-edit" v-model="taskToEdit.is_urgent" >
                                <label for="checkbox-tarea-tag-edit">Marcar como tarea urgente.</label>
                            </div>
                            <div class="form-group">
                                <label for="subject-edit">Asignatura relacionada:</label>
                                <select id="subject-edit" class="select-subjects-tasks" v-model="taskToEdit.subject_id">
                                    <option value="">Tarea general</option>
                                    <option v-for="subject in subjectsArray" v-bind:value="subject.subject_id">
                                        {{subject.subject_name}} - {{subject.period_name}} - {{subject.study_name}}
                                    </option>
                                </select>
                                <small v-if="showEditFail"  class="text-danger">
                                    No se ha podido editar esta tarea, vuelve a intentarlo.
                                </small>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary"  @click="editTask">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--/***********************************************************************************************-->

        <!--/*********************************DELETE TASK*********************************************/-->
        <div class="modal fade" id="deleteTaskModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="editNameLabel3">¿Seguro que quieres eliminar esta tarea?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <h5>{{taskToDeleteName}}</h5>
                            </div>
                            <small v-if="showDeleteFail"  class="text-danger">
                                No se ha podido eliminar esta tarea, vuelve a intentarlo.
                            </small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-danger"  @click="deleteTask">Eliminar</button>
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
        name: "TodolistTag",
        props:['route_add_task','route_get_subjects_by_user','route_get_tasks_by_user','route_delete_task',
        'route_task_done','route_edit_task','route_get_tasks_by_subject','page_type','subject_prop','route_base_images'],
        created(){
            this.route_add_task_vue = this.route_add_task;
            this.route_get_subjects_by_user_vue = this.route_get_subjects_by_user;
            this.route_get_tasks_by_user_vue = this.route_get_tasks_by_user;
            this.route_delete_task_vue = this.route_delete_task;
            this.route_task_done_vue = this.route_task_done;
            this.route_edit_task_vue = this.route_edit_task;
            this.route_get_tasks_by_subject_vue = this.route_get_tasks_by_subject;
            this.page_type_vue = this.page_type;
            this.subject_prop_vue = this.subject_prop;
            this.route_base_images_vue = this.route_base_images;
            this.routeImgEmptyTasks = this.route_base_images_vue +"/content/clip-message-sent-1_v2.png";
            this.getSubjectsByUser();
            this.getTasksByPageType();
        },
        data(){
            return{
                route_add_task_vue:'',
                route_get_subjects_by_user_vue:'',
                route_get_tasks_by_user_vue:'',
                route_delete_task_vue:'',
                route_edit_task_vue:'',
                route_get_tasks_by_subject_vue:'',
                page_type_vue:'',
                subject_prop_vue:'',
                taskToAdd:{
                    description:'',
                    date:'',
                    is_urgent: false,
                    subject_id:'',
                },
                subjectsArray:[],
                tasksArray:[],
                taskToDeleteName:'',
                taskToDelete: {
                    task_id:'',
                },
                showDeleteFail: false,
                showEditFail: false,
                adding:false,
                route_task_done_vue:'',
                taskDone:{
                    task_id:'',
                },
                tachadoClass: 'tachado',
                tachadoImgClass: 'tachado-img',
                taskToEdit:{
                    task_id:'',
                    description:'',
                    date:'',
                    is_urgent: '',
                    subject_id:'',
                },
                currentSubject:{
                    subject_id:'',
                },
                routeImgEmptyTasks:'',
                route_base_images_vue:'',
            }
        },
        methods:{
            getTasksByPageType(){
                if(this.page_type_vue === 'dashboard'){
                    this.getAllTasks();
                }
                if(this.page_type_vue === 'subject'){
                    this.getTasksBySubject();
                }
            },
            getTasksBySubject(){
                this.currentSubject.subject_id = this.subject_prop_vue.subject_ID;
                var url = this.route_get_tasks_by_subject_vue;
                axios.get(url,{params:this.currentSubject}).then(response => {
                    console.log(response.data.result);
                    this.tasksArray = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            formatDateFull(date_to_format){
                if(date_to_format === null){
                    return '';
                }
                var date = new Date(date_to_format);
                var options = {weekday: 'short',year: 'numeric', month: 'short', day: 'numeric' };
                return date = date.toLocaleDateString("es-ES",options);
            },
            enableAdding(){
                if(this.page_type_vue === 'subject'){
                    this.taskToAdd.subject_id = this.subject_prop_vue.subject_ID;
                }
                this.adding = true;
            },
            disableAdding(){
                this.taskToAdd.description ='';
                this.taskToAdd.date='';
                this.taskToAdd.is_urgent = false;
                this.taskToAdd.subject_id='';
                this.adding = false;
            },
            addTask(){
                var url = this.route_add_task_vue;
                axios.post(url ,{task:this.taskToAdd}).then(response => {
                    console.log(response.data.result);
                    this.disableAdding();
                    // this.getAllTasks();
                    this.getTasksByPageType();
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            editTask(){
                var url = this.route_edit_task_vue;
                axios.put(url ,{task:this.taskToEdit}).then(response => {
                    console.log(response.data.result);
                    if(response.data.result === 'task_edited'){
                        $('#editTaskModal').modal('hide');
                        // this.getAllTasks()
                        this.getTasksByPageType();
                    }
                    if(response.data.result === 'error_edit_task'){
                        this.showEditFail = true;
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            editTaskModal(id, description, date, urgent, subject){
                this.taskToEdit.description = description;
                this.taskToEdit.task_id = id;
                this.taskToEdit.date = date;
                this.taskToEdit.is_urgent = urgent;
                if(subject === undefined){
                    this.taskToEdit.subject_id = null;
                }else{
                    this.taskToEdit.subject_id = subject;
                }
                this.showEditFail = false;
                $('#editTaskModal').modal('show');
            },
            deleteTask(){
                var url = this.route_delete_task_vue;
                axios.delete(url,{params:this.taskToDelete}).then(response => {
                    console.log(response.data.result)
                    if(response.data.result === 'task_deleted'){
                        $('#deleteTaskModal').modal('hide');
                        // this.getAllTasks()
                        this.getTasksByPageType();
                    }
                    if(response.data.result === 'error_delete_task'){
                        this.showDeleteFail = true;
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            deleteTaskModal(id, description){
                this.taskToDelete.task_id = id;
                this.taskToDeleteName = description;
                this.showDeleteFail = false;
                $('#deleteTaskModal').modal('show');
            },
            getSubjectsByUser(){
                var url = this.route_get_subjects_by_user_vue;
                axios.get(url).then(response => {
                    console.log(response.data.result);
                    this.subjectsArray = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            getAllTasks(){
                var url = this.route_get_tasks_by_user_vue;
                axios.get(url).then(response => {
                    console.log(response.data.result);
                    this.tasksArray = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            taskCheckDone(id){
                this.taskDone.task_id = id;
                var url = this.route_task_done_vue;
                axios.put(url ,{task:this.taskDone}).then(response => {
                    console.log(response.data.result);
                    // this.getAllTasks();
                    this.getTasksByPageType();
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
        },
        computed:{
            addTaskDisabled(){
                if(this.taskToAdd.description === ''){
                    return true;
                }else{
                    return false;
                }
            },
            showImgEmptyTasks(){
                if(this.tasksArray.length == 0){
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
