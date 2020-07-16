<template>
    <div class="modal fade data-table-view" ref="data-table-view">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">View</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <dl v-if="data">
                        <span v-for="(value,index) in data">
                            <dt>{{ index }}</dt>
                            <dd>{{ value }}</dd>
                        </span>
                    </dl>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal" @click="close()">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</template>

<script>
    export default {
        name: "DataTableView",
        props: {
            'show': false,
            'data': {}
        },
        data() {
            return {
                'modalShow': this.show
            }
        },
        watch: {
            show: function (newVal, oldVal) { // watch it
                if(newVal){
                    $(this.$refs['data-table-view']).modal('show');
                    this.modalShow = true;
                }else{
                    $(this.$refs['data-table-view']).modal('hide');
                    this.modalShow = false;
                }
            }
        },
        methods: {
            close() {
                this.modalShow = false;
                this.$emit('update',this.modalShow);
            }
        },
        mounted() {
            const vm = this;
            $(this.$refs['data-table-view']).on("hide.bs.modal", function () {
                this.modalShow = false;
                vm.$emit('update',this.modalShow);
            });
        }
    }
</script>

<style scoped>

</style>
