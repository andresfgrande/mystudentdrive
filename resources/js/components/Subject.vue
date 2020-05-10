<template>
    <div class="subject">

        {{current_subject_vue}}

        <div class="col-md-5">
            <div class="card">
                <div class="card-header"> <a>{{current_subject_vue.subject_name}} - {{current_subject_vue.period_name}}</a>
                </div>
    <!--            <div class="card-body ">-->
    <!--                <div class="account-data">-->
    <!--                    <p class="label-info"> {{this.userNameVue}} {{this.userSurnamesVue}}</p>-->
    <!--                    <button class="btn btn-success btn-edit"  @click="editNameModal">Editar</button>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--            <div class="card-body custom-card">-->
    <!--                <div class="account-data">-->
    <!--                    <p class="label-info"> {{this.userEmailVue}}</p>-->
    <!--                    <button class="btn btn-success btn-edit"  @click="editEmailModal">Editar</button>-->
    <!--                </div>-->
    <!--            </div>-->
            </div>
        </div>
<!--        For de sections -->
<button type="button" @click="getSectionsBySubject">test get sections</button>
    </div>
</template>

<script>
    export default {
        name: "Subject",
        props:['current_subject','route_get_sections_by_subject'],
        created(){
            this.current_subject_vue = this.current_subject;
            this.route_get_sections_by_subject_vue = this.route_get_sections_by_subject;
        },
        data(){
            return{
                current_subject_vue:'',
                route_get_sections_by_subject_vue:'',
            }
        },
        methods:{
            getSectionsBySubject(){
                var url = this.route_get_sections_by_subject_vue;
                axios.get(url,{params:this.yearIdChosen}).then(response => {
                    console.log(response.data.result);
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
