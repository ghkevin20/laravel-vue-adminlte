<template>
    <div>
        <content-header
            :title="this.title"
            :breadCrumbs="this.breadCrumbs"
        ></content-header>
        <main-content>
            <data-table
                title="List"
                :source="permissions.source"
                :soft-delete="false"
                :columns="permissions.table.columns"
                :default-order="permissions.table.defaultOrder"
                :refresh="permissions.table.refresh"
                :actions="['view']"
                :controls="['search']"
                @actionView="actionView"
            ></data-table>
            <view-form
                :data="permissions.view.data"
                :show="permissions.view.show"
                @toggleShow="toggleView"
            ></view-form>
        </main-content>
    </div>
</template>

<script>
    import axios from 'axios';
    import ContentHeader from '../../layouts/ContentHeader';
    import MainContent from '../../layouts/MainContent';
    import DataTable from "../../components/helpers/DataTable";
    import ViewForm from "./ViewForm";


    export default {
        name: "ActivityLogs",
        components: {
            ContentHeader, MainContent, DataTable, ViewForm
        },
        data() {
            return {
                title: this.$options.name,
                breadCrumbs: [
                    {item: 'Home', to: '/home'},
                    {item: 'Activity Logs', active: true},
                ],
                permissions: {
                    source: '/api/activity-logs',
                    table: {
                        refresh: 0,
                        columns: [
                            {name: 'id', header: 'ID'},
                            {name: 'log_name', header: 'Log Name'},
                            {name: 'description', header: 'Description'},
                            {name: 'subject_id', header: 'Subject ID'},
                            {name: 'subject_type', header: 'Subject Type'},
                            {name: 'causer_id', header: 'Causer ID'},
                            {name: 'causer_id', header: 'Causer ID'},
                            {name: 'causer_type', header: 'Causer Type'},
                            {name: 'created_at', header: 'Created At'},
                            {name: 'updated_at', header: 'Updated At'},
                        ],
                        defaultOrder: [8, 'desc']
                    },
                    view: {
                        id: '',
                        data: {},
                        show: false
                    }
                },
            }
        },
        methods: {
            toggleView(isShow) {
                this.permissions.view.show = isShow
            },
            actionView(id) {
                const vm = this;
                const url = this.permissions.source + '/' + id;
                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200) {
                            vm.permissions.view.data = response.data.data;
                            vm.permissions.view.show = true;
                        }
                    })
                    .catch(function (response) {
                        console.log(response);
                    });

            }
        }
    }
</script>

<style scoped>

</style>
