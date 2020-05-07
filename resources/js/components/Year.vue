<template>

    <div class="container content">
        <h3>Curso {{formatDateYear(chosed_year_vue.start_date)}} - {{formatDateYear(chosed_year_vue.end_date)}}</h3>

<!--        {{formatDateYear(chosedYear.start_date)}} - {{formatDateYear(chosedYear.end_date)}}-->
    </div>
</template>

<script>
    export default {
        name: "Year",
        props:['chosed_year','test'],
        created(){
           this.chosed_year_vue = this.chosed_year;
           this.test_vue = this.test;
        },
        data(){
            return{
                chosed_year_vue:'',
                test_vue:'',
            }
        },
        methods:{
            formatDateYear(date_to_format){
                var date = new Date(date_to_format);
                return date = date.getFullYear();
            },
        }
    }
</script>

<style scoped>

</style>
