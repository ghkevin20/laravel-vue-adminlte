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
                        <label for="update_permissions">Permissions</label>
                        <v-select
                            multiple
                            placeholder="- Attach Permissions -"
                            :options="options.permissions"
                            :reduce="name => name.id"
                            label="name"
                            v-model="fields.permissions"
                            id="update_permissions"
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
                fields: this.data,
                invalidFields: [],
                invalidMessages: {},
                options: {
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
            clearFields() {
                this.fields = Object.assign({}, this.defaultFields);
            },
            submit() {
                const vm = this;

                const config = {
                    headers: {'content-type': 'multipart/form-data'}
                }

                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('name', this.fields.name);
                for (const prop in this.fields.permissions) {
                    if (this.fields.permissions.hasOwnProperty(prop)) {
                        if (this.fields.permissions[prop] instanceof Object) {
                            formData.append('permissions[]', this.fields.permissions[prop].id);
                        } else {
                            formData.append('permissions[]', this.fields.permissions[prop]);
                        }
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

            }
            ,
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
            this.getPermissions();
        },
        watch: {
            show: function (val) {
                this.modalShow = val;
            },
            data: {
                handler(val) {
                    this.fields = Object.assign({}, this.data);
                },
                deep: true
            }
        }
    }
</script>

<style scoped>

</style>
