<template>
    <form role="form" ref="details-form" class="form-horizontal" @submit.prevent="submit">
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name"
                       placeholder="Name"
                       :class="{ 'is-invalid': this.invalidFields.includes('name') }"
                       v-model="fields.name">
                <div class="invalid-feedback">{{ this.invalidMessages.name }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label for="gender" class="col-sm-2 col-form-label">Gender</label>
            <div class="col-sm-10">
                <select type="text" class="form-control" id="gender"
                        :class="{ 'is-invalid': this.invalidFields.includes('gender') }"
                        v-model="fields.gender">
                    <option value="" selected disabled>Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <div class="invalid-feedback">{{ this.invalidMessages.gender }}</div>
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email"
                       placeholder="Email" :value="data.email" readonly>
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
        name: "DetailsForm",
        props: {
            url: '',
            data: {
                type:Object,
                default: function () {
                    return {}
                }
            },
        },
        data() {
            return {
                modalShow: this.show,
                fields: {
                    name: '',
                    gender: '',
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

            },
            updateFields(){
                this.fields.name = this.data.name;
                this.fields.gender = this.data.gender;
            }
        },
        mounted() {
            this.updateFields();
        }
    }
</script>
