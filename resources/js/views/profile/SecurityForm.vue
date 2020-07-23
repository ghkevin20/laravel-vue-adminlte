<template>
    <form role="form" ref="security-form" class="form-horizontal" @submit.prevent="submit">
        <div class="form-group row">
            <label for="current_password" class="col-sm-2 col-form-label">Current Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="current_password"
                       placeholder="Current Password"
                       :class="{ 'is-invalid': this.invalidFields.includes('current_password') }"
                       v-model="fields.current_password">
                <div class="invalid-feedback">{{ this.invalidMessages.current_password }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label for="new_password" class="col-sm-2 col-form-label">New Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="new_password"
                       placeholder="New Password"
                       :class="{ 'is-invalid': this.invalidFields.includes('new_password') }"
                       v-model="fields.new_password">
                <div class="invalid-feedback">{{ this.invalidMessages.new_password }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label for="password_confirmation" class="col-sm-2 col-form-label">Password Confirmation</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" id="password_confirmation"
                       placeholder="Password Confirmation"
                       :class="{ 'is-invalid': this.invalidFields.includes('new_password_confirmation') }"
                       v-model="fields.new_password_confirmation">
                <div class="invalid-feedback">{{ this.invalidMessages.new_password_confirmation }}</div>
            </div>
        </div>

        <div class="form-group row">
            <div class="offset-sm-2 col-sm-10">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </form>
</template>

<script>
    import axios from 'axios';
    import Swal from 'sweetalert2';

    export default {
        name: "SecurityForm",
        props: {
            url: ''
        },
        data() {
            return {
                fields: {
                    current_password: '',
                    new_password: '',
                    new_password_confirmation: '',
                },
                invalidFields: [],
                invalidMessages: {},
            }
        },
        methods: {
            clearValidation() {
                this.invalidFields = [];
                this.invalidMessages = {};
            },
            clearFields() {
                this.fields = Object.assign({}, this.defaultFields);
            },
            setDefaultFields(){
                this.defaultFields = Object.assign({},this.fields)
            },
            submit() {
                const vm = this;

                const config = {
                    headers: {'content-type': 'multipart/form-data'}
                }

                let formData = new FormData();

                for (const field in this.fields) {
                    if (this.fields[field]) {
                        formData.append(field, this.fields[field]);
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

                            vm.clearFields();

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
                                if(errors){
                                    Object.keys(errors).forEach(function (key) {
                                        invalidFields.push(key);
                                        invalidMessages[key] = errors[key][0];
                                    });
                                }
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
        mounted() {
            this.setDefaultFields();
        }

    }
</script>
