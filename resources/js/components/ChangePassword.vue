<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <a data-toggle="collapse" href="#collapse-password"  aria-expanded="false"
                           role="button"  aria-controls="collapseExample">
                            Cambiar la contraseña
                        </a>
                    </div>

                    <div class="collapse" id="collapse-password">
                        <div class="card-body ">
                            <div class="form-group">
                                <label for="current_password">Contraseña actual</label>
                                <!--                            v-model="inputEditUserData.surnames"-->
                                <input class="form-control"  id="current_password" type="password" name="current_password"
                                       placeholder="" v-model="password.current_password" v-on:keyup="cleanMessage('email')"required>
                                <small v-if="pass_fail" id="passwordHelp" class="text-danger">
                                    Contraseña incorrecta.
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="new_password">Nueva contraseña</label>
                                <!--                            v-model="inputEditUserData.surnames"-->
                                <input class="form-control"  id="new_password" type="password" name="new_password"
                                       placeholder="" v-model="password.new_password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirma la contraseña</label>
                                <input class="form-control"  id="confirm_password" type="password" name="confirm_password"
                                       placeholder="" v-model="password.confirm_password" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" @click="updatePassword" >Actualizar contraseña</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "ChangePassword",
        props:['route_update_password'],

        created(){
            this.route_update_password_vue = this.route_update_password;
        },

        data(){
            return{
                route_update_password_vue:'',
                password:{
                    current_password:'',
                    new_password:'',
                    confirm_password:'',
                },
                pass_fail:false,
            }
        },

        methods:{
            updatePassword(){
                var url= this.route_update_password_vue;
                axios.put(url,{params:this.password}).then(response => {
                    // this.getAccountsForDistributor();
                    console.log(response);
                    if(response.data.result === 'pass_fail'){
                        this.pass_fail = true;
                        this.password.current_password = '';
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            cleanMessage(){
                this.pass_fail=false;
            }
        }
    }
</script>

<style scoped>

</style>
