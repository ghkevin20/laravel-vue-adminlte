<template>
    <div class="modal fade" ref="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ title }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <slot name="modal-body" v-if="show"></slot>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal" @click="close()">Close</button>
                    <button type="submit" v-if="submit" class="btn btn-primary" @click="emitSubmit">Submit</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</template>

<script>
    export default {
        name: "Modal",
        props: {
            title: '',
            show: {
                type: Boolean,
                default: false
            },
            submit: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                modalShow: this.show,
            }
        },
        methods: {
            emitSubmit(){
                this.$emit('actionSubmit');
            },
            emitToggle(){
                this.$emit('toggleShow',this.modalShow);
            },
            close() {
                this.modalShow = false;
                this.emitToggle();
            },
            setShow(){
                if (this.modalShow){
                    $(this.$refs['modal']).modal('show');
                }
            }
        },
        watch: {
            show: function (newVal, oldVal) { // watch it
                if(newVal){
                    $(this.$refs['modal']).modal('show');
                    this.modalShow = true;
                }else{
                    $(this.$refs['modal']).modal('hide');
                    this.modalShow = false;
                }
            }
        },
        mounted() {
            const vm = this;

            $(this.$refs['modal']).on("hide.bs.modal", function () {
                vm.modalShow = false;
                vm.emitToggle();
            });

            this.setShow();
        }
    }
</script>

<style scoped>

</style>
