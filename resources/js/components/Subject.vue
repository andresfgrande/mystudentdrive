<template>
    <div class="subject">

        {{current_subject_vue}}

<!--        For de sections -->

    </div>
</template>

<script>
    export default {
        name: "Subject",
        props:['current_subject'],
        created(){
            this.current_subject_vue = this.current_subject;
        },
        data(){
            return{
                current_subject_vue:'',
            }
        }
    }
</script>

<style scoped>

</style>
