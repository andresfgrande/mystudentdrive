<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div style="width:75%;">
                    <h3 style="text-align:center;">Cursos</h3>
                    <div v-for="(item, index) in estudios_vue">
                        <div class="card-header">
                            <a data-toggle="collapse" v-bind:href="getRefId(item.id)" v-on:load="test(item.id)"  aria-expanded="false"
                               role="button"  aria-controls="collapseExample">
                                {{ item.name }}
                            </a>
                        </div>
                        <ul class="collapse" v-bind:id="getRefId2(item.id)" >
                            <li v-for="hey in auxArray[index]" v-if="hey !== 'vacio'" @click="getSubjectsByYear(hey.id, item.name)">
                                {{hey.start_date}} / {{hey.end_date}}
                            </li>
                        </ul>
                    </div>
                    <br>
                    <div>
                        <button type="button" class="btn btn-primary" @click="addStudyModal">Añadir estudios</button>
                        <button type="button" class="btn btn-primary" @click="addYearModal">Añadir año academico</button>
                    </div>
                </div>
            </div>
            <div class="col">
                <div style="width:75%;">
                    <h3 style="text-align:center;">Asignaturas</h3>
                    <div class="card-header" v-if="showSubjectsHeader">
                      {{this.chosenStudy}}
                    </div>
                    <ul>
                        <li v-for="subject in subjectsArray">
                            <p>{{subject.subject_name}} - {{subject.period_name}}</p>
                        </li>
                    </ul>
                    <br>
                    <div>
                        <button type="button" class="btn btn-primary" @click="addSubject">Añadir asignatura</button>
                    </div>
                </div>
            </div>
        </div>

        <!--/**********ADD STUDIES****************/-->
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
                            <button type="button" class="btn btn-primary" :disabled='isDisabled' @click="addStudy">Guardar</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!--/******************************************-->
        <!--/**********ADD YEAR****************/-->
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
                                       placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="year_academico_end">Fecha de finalización</label>
                                <input class="form-control" v-model="yearToAdd.year_end"
                                       id="year_academico_end" type="date" name="year_academico_end"
                                       placeholder="" required>
                            </div>
                            <div class="alert alert-danger alert-dismissible fade show" v-if="showYearExists" role="alert">
                                <strong>  Las fechas seleccionadas coinciden con las de otro año academico.</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
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
        <!--/******************************************-->

    </div>
</template>

<script>
    export default {
        name: "Estudios",
        props:['estudios','route_get_years_by_study','route_get_subjects_by_year',
                'route_add_study','route_add_year','route_add_subject','route_get_studies'],
        created(){
            this.estudios_vue = this.estudios;
            this.route_get_years_by_study_vue = this.route_get_years_by_study ;
            this.route_get_subjects_by_year_vue = this.route_get_subjects_by_year ;
            this.route_add_study_vue = this.route_add_study;
            this.route_add_year_vue = this.route_add_year;
            this.route_add_subject_vue = this.route_add_subject;
            this.route_get_studies_vue = this.route_get_studies;
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
                subjectsArray:'',
                route_add_study_vue:'',
                route_add_year_vue:'',
                route_add_subject_vue:'',
                route_get_studies_vue:'',
                studyToAdd:'',
                showNameExists:false,
                yearToAdd:{
                    year_start:'',
                    year_end:'',
                },
                showYearExists:true,

            }
        },

        methods:{
            getRefId(id){
                return "#collapse-list" + id;
            },
            getRefId2(id){
                return "collapse-list" + id;
            },
            getAcademicYears(){
                var url = this.route_get_years_by_study_vue;
                axios.get(url ,{params:this.studiesArray}).then(response => {
                    //console.log(response.data.result);
                    var aux2 = [];
                    response.data.result.forEach(function(valor, indice){
                        aux2.push(valor);
                        //console.log(valor);
                    });
                    this.auxArray = aux2;
                    console.log(this.auxArray);
                })
                    .catch(errors => {
                        console.log(errors);
                   });
            },
            test(num){
                //console.log('hellooooo'+ num);
            },
            getStudiesArray(){
                var aux = [];
                this.estudios_vue.forEach(function(valor, indice){
                    aux.push(valor.id);
                });
               this.studiesArray.studies = aux;
            },
            getSubjectsByYear(year_id, study_name){
                this.data.year = year_id;
                var url = this.route_get_subjects_by_year_vue;
                axios.get(url ,{params:this.data}).then(response => {
                    console.log(response.data.result);
                    this.chosenStudy = study_name;
                    this.subjectsArray = response.data.result;
                    console.log(this.subjectsArray);
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            addStudy(){
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
                    }

                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            addYear(){
                //console.log('add academic year');
                var url = this.route_add_year_vue;
                axios.post(url ,{params:this.yearToAdd}).then(response => {
                    console.log(response.data.result);
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            addSubject(){
                console.log('add subject');
            },
            getStudiesAjax(){
                var url = this.route_get_studies_vue;
                axios.get(url).then(response => {
                    console.log(response.data.result);
                    this.estudios_vue = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            addStudyModal(){
                $('#addStudyModal').modal('show');
            },
            cleanMessage(){
                this.showNameExists = false;
            },
            addYearModal(){
                $('#addYearModal').modal('show');
            },

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
                if(this.yearToAdd === ''){
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
