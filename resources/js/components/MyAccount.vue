<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"> Información de la cuenta
                    </div>
                    <div class="card-body ">
                        <div class="account-data">
                            <p class="label-info">Nombre: {{this.userNameVue}} {{this.userSurnamesVue}}</p>
                            <button class="btn btn-success btn-edit"  @click="editNameModal">Editar</button>
                        </div>
                    </div>
                    <div class="card-body custom-card">
                        <div class="account-data">
                            <p class="label-info">E-mail: {{this.userEmailVue}}</p>
                            <button class="btn btn-success btn-edit"  @click="editEmailModal">Editar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<!--/**********EDIT NAME****************/-->
        <div class="modal fade" id="editNameModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="editNameLabel">Edita tu nombre y apellidos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                    <input class="form-control" v-model="inputEditUserData.name" id="name" type="text" name="name"
                                           placeholder="Nombre" required>
                            </div>
                            <div class="form-group">
                                <label for="surnames">Apellidos</label>
                                    <input class="form-control" v-model="inputEditUserData.surnames" id="surnames" type="text" name="surnames"
                                           placeholder="Apellidos" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" :disabled='isDisabled' @click="saveName">Guardar</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
<!--/********************-->

<!--/**********EDIT EMAIL****************/-->
    <div class="modal fade" id="editEmailModal" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"  id="editEmailLabel">Edita tu e-mail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form autocomplete="off">
                    <div class="modal-body">
                    <div class="form-group">
                        <label for="email"> E-mail</label>
                            <input v-model="inputEditUserData.email" type="email" id="email" name="email"
                                   placeholder="Email Address"
                                   class="form-control" v-on:keyup="cleanMessage('email')" required>
                        <small v-if="email_fail"  class="text-danger">
                            Este e-mail ya existe.
                        </small>

                    </div>
                    <div class="form-group">
                        <label for="password">Confirma tu contraseña</label>
                        <input v-model="inputEditUserData.password" class="form-control" type="password" name="password"
                               id="password" placeholder="Contraseña" v-on:keyup="cleanMessage('pass')">
                        <small v-if="pass_fail" id="passwordHelp" class="text-danger">
                            Contraseña incorrecta.
                        </small>
                    </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" :disabled='isDisabledSaveEmail' @click="saveEmail">Guardar</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
<!--/********************-->
    </div>
</template>

<script>

    export default {
        name: "my_account",
        props: ['user_name','user_id','user_email','user_surnames','route_edit_user_account'],
        created(){
            this.userNameVue = this.user_name;
            this.userIdVue = this.user_id;
            this.userEmailVue = this.user_email;
            this.userSurnamesVue = this.user_surnames;
            this.route_edit_user_account_vue = this.route_edit_user_account;
        },
        data(){
            return{
                inputEditUserData:{
                    name:'',
                    surnames:'',
                    email:'',
                    is_email:'',
                    password:''
                },
                userNameVue:'',
                userIdVue:'',
                userEmailVue:'',
                userSurnamesVue:'',
                route_edit_user_account_vue:'',
                pass_fail: false,
                email_fail: false,
            }
        },
        methods: {
            editNameModal(){
                $('#editNameModal').modal('show');
                this.inputEditUserData.name = this.userNameVue;
                this.inputEditUserData.surnames = this.userSurnamesVue;
            },
            editEmailModal(){
                $('#editEmailModal').modal('show');
                this.inputEditUserData.email = this.userEmailVue;
                this.inputEditUserData.password = '';
            },
            saveName(){
                //var url = url_edit.replace(':account_number_id',this.editedAccount.id );
                var url = this.route_edit_user_account_vue;
                this.inputEditUserData.is_email = false;
                /***********Ajax call*************/
                console.log(url);
                axios.put(url,{params:this.inputEditUserData}).then(response => {
                    // this.getAccountsForDistributor();
                    console.log(response);
                })
                    .catch(errors => {
                        console.log(errors);
                    });
                /*********************************/
                //this.editedAccount = null;
                this.userNameVue = this.inputEditUserData.name;
                this.userSurnamesVue = this.inputEditUserData.surnames;
                $('#editNameModal').modal('hide');
               // window.location.reload()
            },
            saveEmail(){
                var url = this.route_edit_user_account_vue;
                this.inputEditUserData.is_email = true;
                /***********Ajax call*************/
                console.log(url);
                axios.put(url,{params:this.inputEditUserData}).then(response => {
                    // this.getAccountsForDistributor();
                    console.log(response);
                    if(response.data.result === 'pass_fail'){
                        this.pass_fail=true;
                        this.inputEditUserData.password='';
                    }
                    if(response.data.result === 'mail_exists_fail'){
                        this.email_fail=true;
                    }
                    if(response.data.result === 'edit_mail_ok' ||response.data.result === 'mantenido_mail_ok' ){
                        $('#editEmailModal').modal('hide');
                        this.userEmailVue = this.inputEditUserData.email;
                    }
                })
                .catch(errors => {
                    console.log(errors);
                });
                /*********************************/
            },
            cleanMessage(ele){
                if(ele === 'email'){
                    this.email_fail=false;
                }else{
                    this.pass_fail=false;
                }
                          }
        },
        computed:{
            isDisabled: function(){
                //Guardar deshabilitado si algun input vacio
                return !(this.inputEditUserData.name !== "" && this.inputEditUserData.surnames !== "");
            },
            isDisabledSaveEmail: function(){
                //Guardar deshabilitado si algun inputsvacio
                return !(this.inputEditUserData.email !== "" && this.inputEditUserData.password!== "");
            }
        }
    }
</script>

<style scoped>

</style>
