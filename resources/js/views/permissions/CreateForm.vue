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
                        <label for="roles">Roles</label>
                        <v-select
                            multiple
                            placeholder="- Attach Roles -"
                            :options="['Role 1', 'Role 2', 'Role 3', 'Role 4', 'Role 5', 'Role 6', 'Role 7']"
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
    import Modal from "../../components/helpers/Modal";

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
                    roles: [],
                },
                invalidFields: [],
                invalidMessages: {},
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
            setDefaultFields(){
                this.defaultFields = Object.assign({},this.fields)
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
                formData.append('roles', this.fields.roles);

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
        },
        mounted() {
            this.setDefaultFields();
        },
        watch: {
            show: function (val) {
                this.modalShow = val;
            }
        }
    }
</script>
