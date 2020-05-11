<template>
<div class="subject-section">
    <div class="section-title">
       <p>{{current_section_vue.name}} </p>
    </div>
    <div class="section-item">
        <ul>
            <li v-for="file in sectionFiles">
                {{ file.name}}
            </li>
        </ul>
    </div>

</div>
</template>

<script>
    export default {
        name: "Section",
        props:['current_section','route_get_files_by_section'],
        created(){
            this.current_section_vue = this.current_section;
            this.route_get_files_by_section_vue = this.route_get_files_by_section;
            this.getFilesBySection();
        },
        data(){
            return{
                current_section_vue: {},
                componentKey:'',
                route_get_files_by_section_vue:'',
                section_info:{
                    section_id:''
                },
                sectionFiles:'',
            }
        },
        methods:{
            getFilesBySection(){
                this.section_info.section_id= this.current_section_vue.id;
                var url = this.route_get_files_by_section_vue;
                console.log(this.route_get_files_by_section_vue)
                axios.get(url,{params:this.section_info}).then(response => {
                    console.log(response.data.result);
                    this.sectionFiles = response.data.result;
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
