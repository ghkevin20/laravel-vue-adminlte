<template>
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>{{ this.$store.getters.appNameFirst }}</b> {{ this.$store.getters.appNameLast }}</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

                <div v-if="this.errorMessage">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ this.errorMessage }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>

                <form role="form" method="post" @submit.prevent="forgotPassword">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Email"
                               :class="{ 'is-invalid': this.invalidFields.includes('email') }"
                               v-model="fields.email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback">{{ this.invalidMessages.email }}</div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Request new password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <router-link to="/login">Login</router-link>
                </p>
                <p class="mb-0">
                    <router-link to="/register" class="text-center">Register a new membership</router-link>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import Swal from 'sweetalert2'

    export default {
        name: "ForgotPassword",
        mounted() {
            this.$root.$el.classList.add('login-page')
        },
        destroyed() {
            this.$root.$el.classList.remove('login-page')
        },
        data() {
            return {
                fields: {
                    email: '',
                },
                errorMessage: '',
                invalidFields: [],
                invalidMessages: {}
            }
        },
        methods: {
            forgotPassword() {
                const vm = this;
                vm.errorMessage = '';
                vm.invalidFields = [];
                vm.invalidMessages = {};
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/api/password/forgot', {
                        email: this.fields.email,
                    })
                        .then(response2 => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response2.data.message
                            })
                            this.$router.push('/login');
                        })
                        .catch(error => {
                            if (error.response) {
                                if (error.response.status === 422 || error.response.status === 401) {
                                    const errors = error.response.data.errors;
                                    let invalidFields = [];
                                    let invalidMessages = {};
                                    if (errors) {
                                        Object.keys(errors).forEach(function (key) {
                                            invalidFields.push(key);
                                            invalidMessages[key] = errors[key][0];
                                        });
                                    }
                                    vm.invalidFields = invalidFields;
                                    vm.invalidMessages = invalidMessages;
                                    vm.errorMessage = error.response.data.message;
                                }
                            } else if (error.request) {
                                console.log(error.request);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong!'
                                })
                            }
                        });

                });
            }
        }
    }
</script>

<style scoped>

</style>
