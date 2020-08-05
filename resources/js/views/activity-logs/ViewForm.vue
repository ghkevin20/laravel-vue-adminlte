<template>
    <div>
        <modal title="View" :show="modalShow" @toggleShow="toggleShow">
            <div slot="modal-body" v-if="data">
                <dl>
                    <dt>Log Name</dt>
                    <dd>{{ data.log_name }}</dd>
                    <dt>Description</dt>
                    <dd>{{ data.description }}</dd>
                    <dt>Subject</dt>
                    <dd>{{ data.subject_type }}</dd>
                    <dd><pre>{{ data.subject }}</pre></dd>
                    <dt>Causer</dt>
                    <dd>{{ data.causer_type }}</dd>
                    <dd><pre>{{ data.causer }}</pre></dd>
                    <dt>Properties</dt>
                    <dd><pre>{{ data.properties}}</pre></dd>
                    <dt>Created At</dt>
                    <dd>{{ data.created_at }}</dd>
                    <dt>Updated At</dt>
                    <dd>{{ data.updated_at }}</dd>
                </dl>
            </div>
        </modal>
    </div>
</template>

<script>
    import Modal from "../../components/Modal";

    export default {
        name: "ViewForm",
        props: {
            data: {},
            show: {
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
            }
        },
        methods: {
            toggleShow(isShow) {
                this.modalShow = isShow;
                this.$emit('toggleShow', isShow);
            }
        },
        watch: {
            show: function (val) {
                this.modalShow = val;
            }
        }
    }
</script>

<style scoped>

</style>
