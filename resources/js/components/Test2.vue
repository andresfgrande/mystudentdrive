<template>
<div class="container">
    <h1>HELLO FROM ANOTEHR COMPONENT!!!!!</h1>
    {{this.datotest_vue}}
    {{this.datotest2[0]}}
</div>
</template>

<script>
    export default {
        name: "Test2",
        props: ['datotest','datotest2'],

        created(){
            this.datotest_vue = this.datotest;
            this.datotest2_vue = this.datotest2;
        },
        data(){
            return{
                datotest_vue:'',
                datotest2_vue: '',
            }
        },

    }
</script>

<style scoped>

</style>
