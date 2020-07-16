<template>
    <div>
        <modal title="View" :show="modalShow" @toggleShow="toggleShow">
            <dl slot="modal-body" v-if="data">
                <div v-for="(value,key) in data">
                    <dt>{{ key }}</dt>
                    <dd>{{ value }}</dd>
                </div>
            </dl>
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
