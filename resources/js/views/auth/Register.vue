<template>
    <div class="register-box">
        <div class="register-logo">
            <a href="#"><b>{{ this.$store.getters.appNameFirst }}</b> {{ this.$store.getters.appNameLast }}</a>
        </div>

        <div class="card">
            <div class="card-body register-card-body">
                <p class="login-box-msg">Register a new membership</p>

                <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="this.errorMessage">
                    {{ this.errorMessage }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form role="form" method="post" @submit.prevent="register">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Full name"
                               :class="{ 'is-invalid': this.invalidFields.includes('name') }"
                               v-model="fields.name">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback">{{ this.invalidMessages.name }}</div>
                    </div>
                    <div class="input-group mb-3">
                        <select type="text" class="form-control"
                                :class="{ 'is-invalid': this.invalidFields.includes('gender') }"
                                v-model="fields.gender">
                            <option value="" selected disabled>Gender</option>
                            <option value="Male">Male</option>
                            <option value="Male">Female</option>
                        </select>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-venus-mars"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback">{{ this.invalidMessages.gender }}</div>
                    </div>
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
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Confirm password"
                               :class="{ 'is-invalid': this.invalidFields.includes('password_confirmation') }"
                               v-model="fields.password_confirmation">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        <div class="invalid-feedback">{{ this.invalidMessages.password_confirmation }}</div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                <label for="agreeTerms">
                                    I agree to the <a href="#">terms</a>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <!--                <div class="social-auth-links text-center">-->
                <!--                    <p>- OR -</p>-->
                <!--                    <a href="#" class="btn btn-block btn-primary">-->
                <!--                        <i class="fab fa-facebook mr-2"></i>-->
                <!--                        Sign up using Facebook-->
                <!--                    </a>-->
                <!--                    <a href="#" class="btn btn-block btn-danger">-->
                <!--                        <i class="fab fa-google-plus mr-2"></i>-->
                <!--                        Sign up using Google+-->
                <!--                    </a>-->
                <!--                </div>-->

                <router-link to="login" class="text-center">I already have a membership</router-link>
            </div>
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
</template>

<script>
    import Swal from 'sweetalert2';

    export default {
        name: "Register",
        mounted() {
            this.$root.$el.classList.add('login-page')
        },
        destroyed() {
            this.$root.$el.classList.remove('login-page')
        },
        data() {
            return {
                fields: {
                    name: '',
                    gender: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                errorMessage: '',
                invalidFields: [],
                invalidMessages: {}
            }
        },
        methods: {
            register() {
                const vm = this;
                vm.errorMessage = '';
                vm.invalidFields = [];
                vm.invalidMessages = {};

                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/api/register', {
                        name: this.fields.name,
                        gender: this.fields.gender,
                        email: this.fields.email,
                        password: this.fields.password,
                        password_confirmation: this.fields.password_confirmation
                    })
                        .then(response2 => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'You are now registered.'
                            })
                            // this.$store.dispatch('authenticate');
                            // this.$store.dispatch('setUser', response2.data.data);
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
