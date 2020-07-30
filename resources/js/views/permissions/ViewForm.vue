<template>
    <div>
        <modal title="View" :show="modalShow" @toggleShow="toggleShow">
            <div slot="modal-body" v-if="data">
                <dl>
                    <dt>Name</dt>
                    <dd>{{ data.name }}</dd>
                    <dt>Roles</dt>
                    <dd>
                        <span class="badge badge-primary mr-1" v-for="role in data.roles" >{{ role.name }}</span>
                    </dd>
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
    import Modal from "../../components/helpers/Modal";

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
