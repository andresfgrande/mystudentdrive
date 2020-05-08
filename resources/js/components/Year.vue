<template>

    <div class="container content year">
        <h3>Curso {{formatDateYear(chosed_year_vue.start_date)}} - {{formatDateYear(chosed_year_vue.end_date)}}</h3>

            <div v-for="subject in subjectsArray">

                <Subject :key="componentKey"
                         v-bind:current_subject="subject"
                ></Subject>

            </div>




    </div>
</template>

<script>
    import Subject from './Subject';
    export default {
        name: "Year",
        props:['chosed_year','route_get_subjects_by_year','route_get_subjects_by_year'],
        components: {
            Subject
        },
        created(){
           this.chosed_year_vue = this.chosed_year;
           //this.test_vue = this.test;
           this.route_get_subjects_by_year_vue =  this.route_get_subjects_by_year;
           this.getSubjectsByYear(this.chosed_year_vue.id);
        },
        data(){
            return{
                chosed_year_vue:'',
                test_vue:'',
                route_get_subjects_by_year_vue:'',
                year_info:{
                    year:'',
                },
                subjectsArray:[],
            }
        },
        methods:{
            formatDateYear(date_to_format){
                var date = new Date(date_to_format);
                return date = date.getFullYear();
            },
            /******************************DOING**************************************/
            getSubjectsByYear(year_id/*, study_name, start_date,end_date,study_id*/){
                this.year_info.year = year_id;
                var url = this.route_get_subjects_by_year_vue;
                axios.get(url ,{params:this.year_info}).then(response => {
                    console.log(response.data.result);
                    this.subjectsArray = response.data.result;
                    console.log(this.subjectsArray);
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            /*******************************************************************************/
        }
    }
</script>

<style scoped>

</style>
