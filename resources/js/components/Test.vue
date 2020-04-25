<template>
    <div class="container">

        <button @click="loadContent" >load 1</button>

<!--        <div id="contenedor" v-html="rawHtml" ></div>-->
        <component :is="currentComponent"
        :datotest="this.datotest"
        :datotest2="this.datotest2"/>
<!--        <component :is="rawHtml"/>-->

<!--        <test2 :datotest="this.datotest"></test2>-->
<!---->
    </div>
</template>

<script>
    import Test2 from './Test2';
    export default {
        name: "Test",
        props:['route_test'],
        components: {
            Test2
        },

        created(){
            this.route_test_vue = this.route_test;
        },

        data(){
            return{
                // rawHtml:'<p>'+ 'dddd'+ '</p>',
                datotest:'Hola soy dato de vuejs',
                datotest2: '',
                string:undefined,
                currentComponent: ''
            }
        },
        methods:{
            loadContent(){
                var url= this.route_test_vue;
               var aux = ['1','2','3'];
                axios.get(url, {params:aux}).then(response => {
                    console.log(response.data.dataTest);
                    this.datotest = response.data.dataTest;
                    this.currentComponent = "test2";
                    console.log(response.data.dataTest2[0]);
                    this.datotest2 = response.data.dataTest2;

                })
                    .catch(errors => {
                        console.log(errors);
                    });
            }
        }
    }
</script>

<style scoped>

</style>
