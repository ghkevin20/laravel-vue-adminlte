<template>
    <form role="form" @submit.prevent="submit" ref="form-create">
        <div class="modal fade data-table-view" ref="data-table-create">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Create</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <slot name="fields"></slot>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal" @click="close()">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </form>
</template>

<script>
    import Swal from 'sweetalert2';

    export default {
        name: "DataTableCreate",
        props: {
            show: {
                type: Boolean,
                default: false
            },
            action: {
                type: String,
                default: ''
            },
            clearAfterSubmit: {
                type: Boolean,
                default: true
            },
            data: null
        },
        data() {
            return {
                modalShow: this.show,
                actualData: this.data
            }
        },
        watch: {
            show: function (newVal, oldVal) { // watch it
                if (newVal) {
                    $(this.$refs['data-table-create']).modal('show');
                    this.modalShow = true;
                } else {
                    $(this.$refs['data-table-create']).modal('hide');
                    this.modalShow = false;
                }
            }
        },
        methods: {
            close() {
                this.modalShow = false;
                this.$emit('update', this.modalShow);
            },
            submit(event) {
                const vm = this;
                axios.post(this.action, this.actualData)
                    .then(function (res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Nothing went wrong.'
                        });

                        if (vm.clearAfterSubmit) {
                            // vm.$emit('updateFields', {
                            //     fields: vm.defaultFields,
                            //     validFields: [],
                            //     invalidFields: [],
                            //     invalidMessages: {}
                            // });
                        }
                    })
                    .catch(function (error) {
                        if (error.response) {
                            if (error.response.status === 422) {
                                const errors = error.response.data.errors;

                                let invalidFields = [];
                                let invalidMessages = {};

                                Object.keys(errors).forEach(key => {
                                    invalidFields.push(key);
                                    invalidMessages[key] = errors[key][0];
                                });

                                // vm.$emit('updateFields', {
                                //     fields: vm.fields,
                                //     validFields: [],
                                //     invalidFields: invalidFields,
                                //     invalidMessages: invalidMessages
                                // });
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
            const vm = this;
            $(this.$refs['data-table-create']).on("hide.bs.modal", function () {
                this.modalShow = false;
                vm.$emit('update', this.modalShow);
            });

            // this.$on('updateData', (data) => {
            //     this.data = data;
            // });
            //
            // this.$on('updateFields', (data) => {
            //     this.fields = data;
            // });
            //
            // this.$on('setDefaultFields', (data) => {
            //     this.defaultFields = data;
            // });
        }
    }
</script>

<style scoped>

</style>
