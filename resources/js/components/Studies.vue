<template>
    <div class="container">


        <div class="row">
            <div class="col">
                <div style="width:75%;">

                    <div v-for="(item, index) in estudios_vue">
                        <div class="card-header">
                            <a data-toggle="collapse" v-bind:href="getRefId(item.id)" v-on:load="test(item.id)"  aria-expanded="false"
                               role="button"  aria-controls="collapseExample">
                                {{ item.name }}
                            </a>
                        </div>

                        <ul class="collapse" v-bind:id="getRefId2(item.id)" >
<!--                            <li class="card-body" v-for="elemento in getAcademicYears(item.id)" :value="elemento">-->
<!--                                <p>{{elemento}}</p>-->
<!--                            </li>-->
                            <li v-for="hey in auxArray[index]" @click="getSubjectsByPeriod(hey.id)">{{hey.start_date}} {{hey.end_date}}</li>
<!--                            <li >hola</li>-->
<!--                            <li>{{this.tesddd}}</li>-->


                        </ul>
                    </div>
                </div>
            </div>
            <div class="col">
                <h2> col2 </h2>
            </div>
        </div>




    </div>
</template>

<script>
    export default {
        name: "Estudios",
        props:['estudios','route_get_years_by_study','route_get_subjects_by_period'],
        created(){
            this.estudios_vue = this.estudios;
            this.route_get_years_by_study_vue = this.route_get_years_by_study ;
            this.route_get_subjects_by_period_vue = this.route_get_subjects_by_period ;
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
                    period:'',
                },
                studiesArray:{
                    studies:[],
                },
                indexArray:0,
                route_get_subjects_by_period_vue:'',
            }
        },

        methods:{
            getRefId(id){
                return "#collapse-list" + id;

            },
            getRefId2(id){

                return "collapse-list" + id;
            },
            async getAcademicYears(){
                //this.data.study = study;
                //console.log(this.data.study);
                var url = this.route_get_years_by_study_vue;
                axios.get(url ,{params:this.studiesArray}).then(response => {
                    console.log(response.data.result);

                    var aux2 = [];
                    response.data.result.forEach(function(valor, indice){
                        aux2.push(valor);
                        console.log(valor);
                    });

                    //this.yearsArray= response.data.result;
                    //this.auxArray.push(this.yearsArray);
                    this.auxArray = aux2;
                    console.log(this.auxArray);
                })
                    .catch(errors => {
                        console.log(errors);
                   });
            },
            test(num){
                console.log('hellooooo'+ num);
            },
            getStudiesArray(){
                var aux = [];
                this.estudios_vue.forEach(function(valor, indice){
                    aux.push(valor.id);
                });
               this.studiesArray.studies = aux;
            },
            getSubjectsByPeriod(period_id){
                this.data.period = period_id;
                var url = this.route_get_subjects_by_period_vue;
                axios.get(url ,{params:this.data}).then(response => {
                    console.log(response.data.result);
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            }
        },
        beforeMount(){
        },
    }
</script>

<style scoped>
</style>
