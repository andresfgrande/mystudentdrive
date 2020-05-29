<template>
    <div class="container content todo-list dashboard">
        <h3 style="text-align:center;">Mi lista</h3>
        <div class="form-group" style="margin-top: 2em;">
            <input type="text" class="form-control" id="input-description" v-model="taskToAdd.description"
                   aria-describedby="emailHelp" placeholder="por ejemplo: Comprar libro de biología.">
            <input type="checkbox" id="checkbox-tarea-tag" v-model="taskToAdd.is_urgent" >
            <label for="checkbox-tarea-tag">Marcar como tarea urgente.</label>
            <input type="date" class="form-control" id="date" v-model="taskToAdd.date" >
            <select id="subject" class="select-subjects-tasks" v-model="taskToAdd.subject_id">
                <option value="">Tarea general</option>
                <option v-for="subject in subjectsArray" v-bind:value="subject.subject_id">
                    {{subject.subject_name}} - {{subject.period_name}} - {{subject.study_name}}
                </option>
            </select>
            <button type="button" class="btn btn-primary" :disabled="addTaskDisabled" @click="addTask">Añadir tarea</button>
        </div>
        <ul>
            <li v-for="task in tasksArray">
                {{task}}
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: "TodolistTag",
        props:['route_add_task','route_get_subjects_by_user','route_get_tasks_by_user'],
        created(){
            this.route_add_task_vue = this.route_add_task;
            this.route_get_subjects_by_user_vue = this.route_get_subjects_by_user;
            this.route_get_tasks_by_user_vue = this.route_get_tasks_by_user;
            this.getSubjectsByUser();
            this.getAllTasks();
        },
        data(){
            return{
                route_add_task_vue:'',
                route_get_subjects_by_user_vue:'',
                route_get_tasks_by_user_vue:'',
                taskToAdd:{
                    description:'',
                    date:'',
                    is_urgent: false,
                    subject_id:'',
                },
                subjectsArray:[],
                tasksArray:[],
            }
        },
        methods:{
            addTask(){
                var url = this.route_add_task_vue;
                axios.post(url ,{task:this.taskToAdd}).then(response => {
                    console.log(response.data.result);
                    this.getAllTasks();
                })
                    .catch(errors => {
                        console.log(errors);
                    });
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
        },
        computed:{
            addTaskDisabled(){
                if(this.taskToAdd.description === ''){
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
