<template>
    <div class="container study">

        <div class="container sidebar">
            <div class="wrapper">
                <!------- Sidebar ------->
                <nav id="sidebar">
                    <div class="sidebar-header">
                        <h3>{{study_prop.name}}</h3>
                    </div>

                    <ul class="list-unstyled components">
                        <li class="active" v-for="year in years_prop_vue" @click="choseYear(year)">
                            <a href="#homeSubmenu"  aria-expanded="false" >{{formatDateYear(year.start_date)}} - {{formatDateYear(year.end_date)}}</a>
                        </li>

                        <button style="margin-top: 1em;" type="button" class="btn btn-secondary" >Añadir curso</button>
                    </ul>
                </nav>
                <!---------------------->
            </div>
        </div>

<!--        <div class="container content">-->
<!--            <h3>Curso {{formatDateYear(chosedYear.start_date)}} - {{formatDateYear(chosedYear.end_date)}}</h3>-->
<!--        </div>-->
<!--        <year-->
<!--            v-bind:chosed_year="chosedYear"-->
<!--        ></year>-->
<!--        <component :is="currentYear"-->
<!--                   v-bind:test: fra-->

<!--        </component>-->
<Year :key="componentKey" v-bind:chosed_year ="chosedYear"
></Year>

    </div>
</template>

<script>
    import Year from './Year';

    export default {

        name: "Study",
        props:['study_prop','years_prop'],
        components: {
            Year
        },
        created(){
            this.study_prop_vue = this.study_prop;
            this.years_prop_vue = this.years_prop;
            this.chosedYear = this.years_prop_vue[0];
        },
        data(){
            return{
                study_prop_vue:'',
                years_prop_vue:'',
                chosedYear:'',
                currentYear:'year',

                frase:3,
                componentKey: 0,
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
                this.frase = 2;
                this.componentKey += 1;

            }
        },
        computed:{
            sendData: function() {
                    return { test: this.frase }

            }
        }
    }
</script>

<style scoped>

</style>
