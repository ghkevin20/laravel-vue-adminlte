<template>
    <form role="form" ref="create-form" @submit.prevent="submit">
        <modal title="Create" :show="modalShow" @toggleShow="toggleShow" :submit="true">
            <div slot="modal-body" class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name *"
                               v-model="fields.name"
                               :class="{ 'is-invalid': this.invalidFields.includes('name') }">
                        <div class="invalid-feedback">{{ this.invalidMessages.name }}</div>
                    </div>
                    <div class="form-group">
                        <label for="permissions">Permissions</label>
                        <v-select
                            multiple
                            placeholder="- Attach Permissions -"
                            :options="options.permissions"
                            :reduce="name => name.id"
                            label="name"
                            v-model="fields.permissions"
                            id="permissions"
                            :class="{ 'is-invalid': this.invalidFields.includes('permissions') }"
                        ></v-select>
                        <div class="invalid-feedback">{{ this.invalidMessages.permissions }}</div>
                    </div>
                </div>
            </div>
        </modal>
    </form>
</template>

<script>
    import axios from 'axios';
    import Swal from 'sweetalert2';
    import Modal from "../../components/helpers/Modal";
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
            Modal
        },
        data() {
            return {
                modalShow: this.show,
                fields: {
                    name: '',
                    permissions: [],
                },
                invalidFields: [],
                invalidMessages: {},
                options:{
                    permissions: []
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
                formData.append('name', this.fields.name);

                for(const prop in this.fields.permissions){
                    if(this.fields.permissions.hasOwnProperty(prop)){
                        formData.append('permissions[]', this.fields.permissions[prop]);
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
            getPermissions() {
                const vm = this;
                var parameters = {
                    scope: 'active',
                    fields: {
                        permissions: 'id,name'
                    }
                }

                axios.get('/api/permissions', {
                    params: parameters
                    , paramsSerializer: params => {
                        return qs.stringify(params)
                    }
                })
                    .then(function (response) {
                        vm.options.permissions = response.data;
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
            }
        },
        mounted() {
            this.setDefaultFields();
            this.getPermissions();
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
