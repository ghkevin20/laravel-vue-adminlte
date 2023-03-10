<template>
    <form role="form" ref="create-form" @submit.prevent="submit">
        <modal title="Create" :show="modalShow" @toggleShow="toggleShow" :submit="true">
            <div slot="modal-body" class="row">
                <div class="col-12">
                    <div class="form-group d-flex justify-content-center align-items-center">
                        <avatar-cropper @updateBlob="updateBlob"></avatar-cropper>
                    </div>
                    <div class="d-none form-control"
                         :class="{ 'is-invalid': this.invalidFields.includes('avatar') }"></div>
                    <div class="d-inline invalid-feedback">{{ this.invalidMessages.avatar }}</div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name *"
                               v-model="fields.name"
                               :class="{ 'is-invalid': this.invalidFields.includes('name') }">
                        <div class="invalid-feedback">{{ this.invalidMessages.name }}</div>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <v-select
                            placeholder="- Select Gender -"
                            :options="['Male', 'Female']"
                            v-model="fields.gender"
                            id="gender"
                            :class="{ 'is-invalid': this.invalidFields.includes('gender') }"
                        ></v-select>
                        <div class="invalid-feedback">{{ this.invalidMessages.gender }}</div>
                    </div>
<!--                    <div class="form-group">-->
<!--                        <label for="gender">Gender</label>-->
<!--                        <select class="form-control" id="gender" name="gender"-->
<!--                                v-model="fields.gender"-->
<!--                                :class="{ 'is-invalid': this.invalidFields.includes('gender') }"-->
<!--                        >-->
<!--                            <option value="">- Select Option -</option>-->
<!--                            <option value="Male">Male</option>-->
<!--                            <option value="Female">Female</option>-->
<!--                        </select>-->
<!--                        <div class="invalid-feedback">{{ this.invalidMessages.gender }}</div>-->
<!--                    </div>-->
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email *"
                               v-model="fields.email"
                               :class="{ 'is-invalid': this.invalidFields.includes('email') }">
                        <div class="invalid-feedback">{{ this.invalidMessages.email }}</div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" autocomplete="off"
                               placeholder="Password *"
                               v-model="fields.password"
                               :class="{ 'is-invalid': this.invalidFields.includes('password') }">
                        <div class="invalid-feedback">{{ this.invalidMessages.password }}</div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" autocomplete="off"
                               name="password_confirmation"
                               placeholder="Confirm Password *" v-model="fields.password_confirmation"
                               :class="{ 'is-invalid': this.invalidFields.includes('password_confirmation') }">
                        <div class="invalid-feedback">{{ this.invalidMessages.password_confirmation }}</div>
                    </div>
                    <div class="form-group">
                        <label for="roles">Role/s</label>
                        <v-select
                            multiple
                            placeholder="- Attach Roles -"
                            :options="options.roles"
                            :reduce="name => name.id"
                            label="name"
                            v-model="fields.roles"
                            id="roles"
                            :class="{ 'is-invalid': this.invalidFields.includes('roles') }"
                        ></v-select>
                        <div class="invalid-feedback">{{ this.invalidMessages.roles }}</div>
                    </div>
                </div>
            </div>
        </modal>
    </form>
</template>

<script>
    import axios from 'axios';
    import Swal from 'sweetalert2';
    import Modal from "../../components/Modal";
    import AvatarCropper from "../../components/AvatarCropper";
    import qs from "qs";

    export default {
        name: "CreateForm",
        props: {
            url: '',
            show: {
                type: Boolean,
                default: false
            },
            clearAfterSubmit: {
                type: Boolean,
                default: false
            }
        },
        components: {
            Modal, AvatarCropper
        },
        data() {
            return {
                modalShow: this.show,
                fields: {
                    avatar: null,
                    name: '',
                    gender: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    roles: []
                },
                invalidFields: [],
                invalidMessages: {},
                options: {
                    roles:[]
                }
            }
        },
        methods: {
            toggleShow(isShow) {
                this.modalShow = isShow;
                this.$emit('toggleShow', isShow);
            },
            clearValidation() {
                this.invalidFields = [];
                this.invalidMessages = {};
            },
            setDefaultFields() {
                this.defaultFields = Object.assign({}, this.fields)
            },
            clearFields() {
                this.fields = Object.assign({}, this.defaultFields);
            },
            submit() {
                const vm = this;

                const config = {
                    headers: {'content-type': 'multipart/form-data'}
                }

                let formData = new FormData();
                if (this.fields.avatar instanceof Blob) {
                    formData.append('avatar', this.fields.avatar);
                }
                formData.append('name', this.fields.name);
                formData.append('gender', this.fields.gender);
                formData.append('email', this.fields.email);
                formData.append('password', this.fields.password);
                formData.append('password_confirmation', this.fields.password_confirmation);
                for(const prop in this.fields.roles){
                    if(this.fields.roles.hasOwnProperty(prop)){
                        formData.append('roles[]', this.fields.roles[prop]);
                    }
                }


                axios.post(this.url, formData, config)
                    .then(function (response) {
                        if (response.status === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Nothing went wrong.'
                            });

                            if (vm.clearAfterSubmit) {
                                vm.clearFields();
                            }

                            vm.clearValidation();

                            vm.$emit('submit')
                        }
                    })
                    .catch(function (error) {
                        if (error.response) {
                            if (error.response.status === 422) {
                                const errors = error.response.data.errors;
                                let invalidFields = [];
                                let invalidMessages = {};
                                Object.keys(errors).forEach(function (key) {
                                    invalidFields.push(key);
                                    invalidMessages[key] = errors[key][0];
                                });
                                vm.invalidFields = invalidFields;
                                vm.invalidMessages = invalidMessages;
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

            },
            updateBlob(avatar) {
                this.fields.avatar = avatar;
            },
            getRoles(){
                const vm = this;
                var parameters = {
                    scope: 'active',
                    fields: {
                        roles: 'id,name'
                    }
                }

                axios.get('/api/roles', {
                    params: parameters
                    , paramsSerializer: params => {
                        return qs.stringify(params)
                    }
                })
                    .then(function (response) {
                        vm.options.roles = response.data;
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
            }
        },
        mounted() {
            this.setDefaultFields();
            this.getRoles();
        },
        watch: {
            show: function (val) {
                this.modalShow = val;
            }
        }
    }
</script>

<style scoped>
    /* Ensure the size of the image fit the container perfectly */
    img {
        display: block;

        /* This rule is very important, please don't ignore this */
        max-width: 100%;
    }
</style>
