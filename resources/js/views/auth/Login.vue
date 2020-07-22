<template>
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>{{ this.$store.getters.appNameFirst }}</b> {{ this.$store.getters.appNameLast }}</a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <div class="login-box-msg">
                    <p>Sign in to start your session</p>
                    <div class="text-danger invalid-feedback d-block mb-1">{{ this.errorMessage }}</div>
                </div>

                <form role="form" method="post" @submit.prevent="login">
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

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password"
                               :class="{ 'is-invalid': this.invalidFields.includes('password') }"
                               v-model="fields.password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback">{{ this.invalidMessages.password }}</div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <!--                <div class="social-auth-links text-center mb-3">-->
                <!--                    <p>- OR -</p>-->
                <!--                    <a href="#" class="btn btn-block btn-primary">-->
                <!--                        <i class="fab fa-facebook mr-2"></i> Sign in using Facebook-->
                <!--                    </a>-->
                <!--                    <a href="#" class="btn btn-block btn-danger">-->
                <!--                        <i class="fab fa-google-plus mr-2"></i> Sign in using Google+-->
                <!--                    </a>-->
                <!--                </div>-->
                <!-- /.social-auth-links -->

                <p class="mb-1">
                    <router-link to="forgot-password">I forgot my password</router-link>
                </p>
                <p class="mb-0">
                    <router-link to="register" class="text-center">Register a new membership</router-link>
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
        name: "Login",
        beforeCreate() {
            document.querySelector("body").classList.add('login-page');
        },
        destroyed() {
            document.querySelector("body").classList.remove('login-page');
        },
        data() {
            return {
                fields: {
                    email: '',
                    password: ''
                },
                errorMessage: '',
                invalidFields: [],
                invalidMessages: {}
            }
        },
        methods: {
            login() {
                const vm = this;
                vm.errorMessage = '';
                vm.invalidFields = [];
                vm.invalidMessages = {};
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/api/login', {
                        email: this.fields.email,
                        password: this.fields.password
                    })
                        .then(response2 => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'You are now logged in.'
                            })
                            this.$store.dispatch('authenticate');
                            this.$store.dispatch('setUser', response2.data.data);
                            this.$router.push('/home');
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
