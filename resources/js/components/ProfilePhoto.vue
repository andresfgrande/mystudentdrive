<template>
<div class="container">


    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="">
                <img class="card-img" style="width:50% ; border-radius: 50%; margin-left:25%;" :src="this.route_profile_photo_vue"  alt="profile_photo"/>
            </div>
        </div>
    </div>


</div>
</template>

<script>
    export default {
        name: "ProfilePhoto",
        props:['route_profile_photo'],

        created(){
            this.route_profile_photo_vue = this.route_profile_photo;
        },

        data(){
            return{
                route_profile_photo_vue:'',
            }
        },

    }
</script>

<style scoped>

</style>
