<template>
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-5">
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
                                       placeholder="" v-model="password.current_password" v-on:keyup="cleanMessage" required>
                                <small v-if="pass_fail" id="passwordHelp" class="text-danger">
                                    Contraseña incorrecta.
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="new_password">Nueva contraseña</label>
                                <input class="form-control"  id="new_password" type="password" name="new_password"
                                       placeholder="" v-model="password.new_password" v-on:keyup="showMessageNotMatch"  required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirma la contraseña</label>
                                <input class="form-control"  id="confirm_password" type="password" name="confirm_password"
                                       placeholder="" v-model="password.confirm_password" v-on:keyup="showMessageNotMatch"  required>
                                <small v-if="pass_not_match"  class="text-danger">
                                    Contraseñas no coinciden.
                                </small>
                                <small v-if="result_not_match"  class="text-danger">
                                    Contraseñas no coinciden, vuelve a intentarlo.
                                </small>
                                <small v-if="length_not_ok"  class="text-danger">
                                    La contraseña tiene que tener más de 8 caracteres.
                                </small>
                                <small v-if="new_equals_current"  class="text-danger">
                                    Nueva contraseña no puede ser igual a la anterior.
                                </small>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" @click="updatePassword" :disabled="isDisabled" >Actualizar contraseña</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-succes alert-dismissible fade show" v-if="updated_ok" role="alert">
                    <strong>¡Contraseña cambiada correctamente!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "ChangePassword",
        props:['route_update_password'],
        ///

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
                pass_not_match: false,
                length_not_ok: false,
                result_not_match: false,
                updated_ok: false,
                new_equals_current: false,
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
                        this.password.new_password = '';
                        this.password.confirm_password = '';
                    }
                    if(response.data.result === 'pass_not_equals'){
                        this.result_not_match = true;
                        this.pass_not_match = false;
                        this.password.current_password = '';
                        this.password.new_password = '';
                        this.password.confirm_password = '';
                    }
                    if(response.data.result === 'uptade_pass_ok'){
                        this.updated_ok = true;
                        this.password.current_password = '';
                        this.password.new_password = '';
                        this.password.confirm_password = '';
                        $('#collapse-password').toggle();
                    }
                    if(response.data.result === 'new_current_equals_fail'){
                        this.new_equals_current = true;
                        this.password.current_password = '';
                        this.password.new_password = '';
                        this.password.confirm_password = '';
                    }
                })
                    .catch(errors => {
                        console.log(errors);
                    });
            },
            cleanMessage(){
                this.pass_fail = false;
                this.result_not_match = false;
            },
             showMessageNotMatch(){
                 this.result_not_match = false;
                 this.new_equals_current = false;
                this.showMessageLength();

                 if(this.password.new_password !== this.password.confirm_password){
                     this.pass_not_match = true;
                 }else{
                     this.pass_not_match = false;
                 }

             },
            showMessageLength(){
                if(this.password.new_password.length < 8 || this.password.new_password.length < 8){
                    this.length_not_ok = true;
                }else{
                    this.length_not_ok = false;
                }
            }

        },
        computed :{
            isDisabled: function(){
                //Actualizar deshabilitado si algun input vacio
                return !(this.password.current_password !== "" && this.password.confirm_password!== ""
                    && this.password.new_password!== "" && this.password.new_password === this.password.confirm_password);
            },
        }
    }
</script>

<style scoped>

</style>
