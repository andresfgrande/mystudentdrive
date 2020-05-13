<template>
    <div class="container content study">

        <!-- Sidebar -->


        <div class="d-flex" id="wrapper">

            <div class="bg-light border-right" id="sidebar-wrapper">
                <div class="sidebar-heading">{{study_prop_vue.name}}
                    <button class="btn btn-primary" id="menu-toggle" @click="toggleSidebar">Toggle Menu</button>
                </div>
                <div class="list-group list-group-flush">
                    <ul class="list-unstyled components years">
                        <li class="list-group-item list-group-item-action bg-light" v-for="year in years_prop_vue" @click="choseYear(year)">
                            <a href="#homeSubmenu" class="year-range" aria-expanded="false" >{{formatDateYear(year.start_date)}} - {{formatDateYear(year.end_date)}}</a>
                            <a class="date-range">{{formatDateFull(year.start_date)}} - {{formatDateFull(year.end_date)}}</a>
                        </li>
                        <button class="btn btn-secondary"  type="button" @click="addYearModal(study_prop_vue.id)">Añadir curso</button>
                        <button class="btn btn-secondary"  type="button" @click="getAcademicYears()">test get years</button>

                    </ul>
                </div>
            </div>
            <div id="page-content-wrapper">
<!--                <button style="margin-top: 100px" class="btn btn-primary" id="menu-toggle" @click="toggleSidebar">Toggle Menu</button>-->
                <Year :key="componentKey" v-bind:chosed_year = "chosedYear"
                      v-bind:route_get_subjects_by_year = "route_get_subjects_by_year_vue"
                      v-bind:chosed_study="chosedStudy"
                      v-bind:route_add_subject="route_add_subject_vue"
                      v-bind:route_add_period="route_add_period_vue"
                      v-bind:route_get_periods_by_year="route_get_periods_by_year_vue"
                      v-bind:route_get_sections_by_subject="route_get_sections_by_subject_vue"
                      v-bind:route_get_files_by_section="route_get_files_by_section_vue"
                      v-bind:route_add_section="route_add_section_vue"
                      v-bind:route_edit_section="route_edit_section_vue"
                      v-bind:route_upload_file="route_upload_file_vue"
                      v-bind:route_base_images="route_base_images_vue"
                >
                </Year>
            </div>
            </div>
        <!-- /#sidebar-wrapper -->



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

    </div>
</template>

<script>
    import Year from './Year';
    export default {
        name: "Study",
        props:['study_prop','years_prop','index_chosen_year','route_add_year',
        'route_get_years_by_one_study','route_get_subjects_by_year','route_add_subject','route_add_period',
        'route_get_periods_by_year','route_get_sections_by_subject','route_get_files_by_section','route_add_section',
        'route_edit_section','route_upload_file','route_base_images'],
        components: {
            Year
        },
        created(){
            this.study_prop_vue = this.study_prop;
            this.years_prop_vue = this.years_prop;
            this.chosedYear = this.years_prop_vue[this.index_chosen_year];
            this.index_chosen_year_vue = this.index_chosen_year;
            this.route_add_year_vue = this.route_add_year;
            this.route_get_years_by_one_study_vue = this.route_get_years_by_one_study;
            this.route_get_subjects_by_year_vue = this.route_get_subjects_by_year ;
            this.chosedStudy = this.study_prop_vue.name;
            this.route_add_subject_vue = this.route_add_subject;
            this.route_add_period_vue = this.route_add_period;
            this.route_get_periods_by_year_vue = this.route_get_periods_by_year;
            //this.current_subject_vue = this.current_subject;
            this.route_get_sections_by_subject_vue = this.route_get_sections_by_subject;
            this.route_get_files_by_section_vue = this.route_get_files_by_section;
            this.route_add_section_vue = this.route_add_section;
            this.route_edit_section_vue = this.route_edit_section;
            this.route_upload_file_vue = this.route_upload_file;
            this.route_base_images_vue = this.route_base_images;
        },
        data(){
            return{
                study_prop_vue:'',
                years_prop_vue:'',
                chosedYear:'',
                chosedStudy:'',
                currentYear:'year',
                componentKey: 0,
                index_chosen_year_vue:'',

                /***************/
                route_add_year_vue:'',
                yearToAdd:{
                    study_id:'',
                    year_start:'',
                    year_end:'',
                },
                showYearExists:false,
                showYearGreater:false,
                route_get_years_by_one_study_vue:'',
                study_info:{
                    study_id:'',
                },
                /***************/
                route_get_subjects_by_year_vue:'',
                route_add_subject_vue:';',
                route_add_period_vue:'',
                route_get_sections_by_subject_vue:'',
                route_get_periods_by_year_vue:'',
                route_get_files_by_section_vue:'',
                route_add_section_vue:'',
                route_edit_section_vue:'',
                route_upload_file_vue:'',
                route_base_images_vue:'',
            }
        },
        methods:{
            formatDateYear(date_to_format){
                var date = new Date(date_to_format);
                return date = date.getFullYear();
            },
            choseYear(year){
                this.currentYear = "year";
                this.chosedYear = year;
                console.log(this.chosedYear);
                this.componentKey += 1;
            },
            addYear(){ //OK
                var url = this.route_add_year_vue;
                axios.post(url ,{params:this.yearToAdd}).then(response => {
                    console.log(response.data.result);
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
            addYearModal(study_id){
                this.showYearExists = false;
                this.showGrater = false;
                this.yearToAdd.year_start = '';
                this.yearToAdd.year_end = '';
                this.yearToAdd.study_id = study_id;
                $('#addYearModal').modal('show');
            },
            getAcademicYears(){
                this.study_info.study_id= this.study_prop_vue.id;
                var url = this.route_get_years_by_one_study_vue;
                axios.get(url ,{params:this.study_info}).then(response => {
                    console.log(response.data.result);
                    this.years_prop_vue = response.data.result;
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            cleanMessageDates(){
                this.showYearExists = false;
                this.showGrater = false;
            },
            formatDateFull(date_to_format){
                var date = new Date(date_to_format);
                return date = date.toLocaleDateString();
            },
            toggleSidebar(){
                $("#wrapper").toggleClass("toggled");
            }
        },
        computed:{
            isDisabledSaveYear: function(){
                if(this.yearToAdd.year_start === '' || this.yearToAdd.year_end === ''){
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
