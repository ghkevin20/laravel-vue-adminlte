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
                :actions="['create','view','edit','delete']"
                :controls="['search']"
                @actionCreate="actionCreate"
                @actionView="actionView"
                @actionEdit="actionEdit"
            ></data-table>
            <view-form
                :data="permissions.view.data"
                :show="permissions.view.show"
                @toggleShow="toggleView"
            ></view-form>
            <create-form
                :url="permissions.source"
                :show="permissions.create.show"
                :clear-after-submit="true"
                @toggleShow="toggleCreate"
                @submit="permissions.table.refresh++"
            ></create-form>
            <edit-form
                :url="permissions.source+'/'+permissions.edit.id"
                :data="permissions.edit.data"
                :show="permissions.edit.show"
                @toggleShow="toggleEdit"
                @submit="permissions.table.refresh++"
            ></edit-form>
        </main-content>
    </div>
</template>

<script>
    import axios from 'axios';
    import ContentHeader from '../../layouts/ContentHeader';
    import MainContent from '../../layouts/MainContent';
    import DataTable from "../../components/DataTable";
    import CreateForm from "./CreateForm";
    import ViewForm from "./ViewForm";
    import EditForm from "./EditForm";


    export default {
        name: "Permissions",
        components: {
            ContentHeader, MainContent, DataTable, CreateForm, ViewForm, EditForm
        },
        data() {
            return {
                title: this.$route.name,
                breadCrumbs: [
                    {item: 'Home', to: '/home'},
                    {item: 'Permissions', active: true},
                ],
                permissions: {
                    source: '/api/permissions',
                    table: {
                        refresh: 0,
                        columns: [
                            {name: 'id', header: 'ID'},
                            {name: 'name', header: 'Name'},
                            {
                                name: 'roles_list',
                                header: 'Roles',
                                sortable: false,
                                searchable: false,
                                included: true
                            },
                            {name: 'created_at', header: 'Created At'},
                            {name: 'updated_at', header: 'Updated At'},
                        ],
                        defaultOrder: [3, 'desc']
                    },
                    create: {
                        show: false
                    },
                    view: {
                        id: '',
                        data: {},
                        show: false
                    },
                    edit: {
                        id: '',
                        data: {},
                        show: false
                    }
                },
            }
        },
        methods: {
            toggleCreate(isShow) {
                this.permissions.create.show = isShow
            },
            toggleView(isShow) {
                this.permissions.view.show = isShow
            },
            toggleEdit(isShow) {
                this.permissions.edit.show = isShow
            },
            actionCreate() {
                this.permissions.create.show = true;
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

            },
            actionEdit(id) {
                const vm = this;
                const url = this.permissions.source + '/' + id;
                vm.permissions.edit.id = id;
                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200) {
                            vm.permissions.edit.data = response.data.data;
                            vm.permissions.edit.show = true;
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
