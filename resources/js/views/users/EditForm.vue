<template>
    <form role="form" ref="edit-form" @submit.prevent="submit">
        <modal title="Edit" :show="modalShow" @toggleShow="toggleShow" :submit="true">
            <div slot="modal-body" class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="update_name">Name</label>
                        <input type="text" class="form-control" id="update_name" name="name" placeholder="Name *"
                               v-model="fields.name"
                               :class="{ 'is-invalid': this.invalidFields.includes('name') }">
                        <div class="invalid-feedback">{{ this.invalidMessages.name }}</div>
                    </div>
                    <div class="form-group">
                        <label for="update_email">Email</label>
                        <input type="email" class="form-control" id="update_email" name="email" placeholder="Email *"
                               v-model="fields.email"
                               :class="{ 'is-invalid': this.invalidFields.includes('email') }">
                        <div class="invalid-feedback">{{ this.invalidMessages.email }}</div>
                    </div>
                    <div class="form-group">
                        <label for="update_password">Password</label>
                        <input type="password" class="form-control" id="update_password" name="password" autocomplete="off"
                               placeholder="Password *"
                               v-model="fields.password"
                               :class="{ 'is-invalid': this.invalidFields.includes('password') }">
                        <div class="invalid-feedback">{{ this.invalidMessages.password }}</div>
                    </div>
                    <div class="form-group">
                        <label for="update_password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" id="update_password_confirmation" autocomplete="off"
                               name="password_confirmation"
                               placeholder="Confirm Password *" v-model="fields.password_confirmation"
                               :class="{ 'is-invalid': this.invalidFields.includes('password_confirmation') }">
                        <div class="invalid-feedback">{{ this.invalidMessages.password_confirmation }}</div>
                    </div>
                </div>
            </div>
        </modal>
    </form>
</template>

<script>
    import axios from 'axios';
    import Modal from "../../components/helpers/Modal";
    import Swal from 'sweetalert2';

    export default {
        name: "EditForm",
        props: {
            url: '',
            data: {},
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
            Modal
        },
        data() {
            return {
                modalShow: this.show,
                defaultFields: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                fields: this.data,
                invalidFields: [],
                invalidMessages: {}
            }
        },
        methods: {
            toggleShow(isShow) {
                this.modalShow = isShow;
                this.$emit('toggleShow', isShow);
            },
            clearFields(){
                this.fields = Object.assign({}, this.defaultFields);
                this.invalidFields = [];
                this.invalidMessages = {};
            },
            submit() {
                const vm = this;

                const config = {
                    headers: {'content-type': 'multipart/form-data'}
                }

                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('name', this.fields.name);
                formData.append('email', this.fields.email);
                formData.append('password', this.fields.password);
                formData.append('password_confirmation', this.fields.password_confirmation);

                axios.post(this.url, formData, config)
                    .then(function (response) {
                        if (response.status === 200) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Nothing went wrong.'
                            });

                            if (vm.clearAfterSubmit){
                                vm.clearFields();
                            }

                            vm.$emit('submit')
                        }
                    })
                    .catch(function (error) {
                        if (error.response) {
                            if (error.response.status === 422) {
                                const errors = error.response.data.errors;
                                let invalidFields = [];
                                let invalidMessages = {};
                                Object.keys(errors).forEach(function (key){
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

            }
        },
        watch: {
            show: function (val) {
                this.modalShow = val;
            },
            data: {
                handler(val){
                    this.fields = this.data;
                },
                deep: true
            }
        }
    }
</script>

<style scoped>

</style>
