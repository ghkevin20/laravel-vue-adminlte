<template>
    <div>
        <content-header
            :title="this.title"
            :breadCrumbs="this.breadCrumbs"
        ></content-header>
        <main-content>
            <data-table
                title="List"
                :source="roles.source"
                :soft-delete="false"
                :columns="roles.table.columns"
                :default-order="roles.table.defaultOrder"
                :refresh="roles.table.refresh"
                :actions="['create','view','edit','delete']"
                :controls="['search']"
                @actionCreate="actionCreate"
                @actionView="actionView"
                @actionEdit="actionEdit"
            ></data-table>
            <view-form
                :data="roles.view.data"
                :show="roles.view.show"
                @toggleShow="toggleView"
            ></view-form>
            <create-form
                :url="roles.source"
                :show="roles.create.show"
                :clear-after-submit="true"
                @toggleShow="toggleCreate"
                @submit="roles.table.refresh++"
            ></create-form>
            <edit-form
                :url="roles.source+'/'+roles.edit.id"
                :data="roles.edit.data"
                :show="roles.edit.show"
                @toggleShow="toggleEdit"
                @submit="roles.table.refresh++"
            ></edit-form>
        </main-content>
    </div>
</template>

<script>
    import axios from 'axios';
    import ContentHeader from '../../layouts/ContentHeader';
    import MainContent from '../../layouts/MainContent';
    import DataTable from "../../components/helpers/DataTable";
    import CreateForm from "./CreateForm";
    import ViewForm from "./ViewForm";
    import EditForm from "./EditForm";


    export default {
        name: "Roles",
        components: {
            ContentHeader, MainContent, DataTable, CreateForm, ViewForm, EditForm
        },
        data() {
            return {
                title: this.$options.name,
                breadCrumbs: [
                    {item: 'Home', to: '/home'},
                    {item: 'Roles', active: true},
                ],
                roles: {
                    source: '/api/roles',
                    table: {
                        refresh: 0,
                        columns: [
                            {'name': 'id', 'header': 'ID'},
                            {'name': 'name', 'header': 'Name'},
                            {'name': 'created_at', 'header': 'Created At'},
                            {'name': 'updated_at', 'header': 'Updated At'},
                        ],
                        defaultOrder: [2, 'desc']
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
                this.roles.create.show = isShow
            },
            toggleView(isShow) {
                this.roles.view.show = isShow
            },
            toggleEdit(isShow) {
                this.roles.edit.show = isShow
            },
            actionCreate() {
                this.roles.create.show = true;
            },
            actionView(id) {
                const vm = this;
                const url = this.roles.source + '/' + id;
                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200) {
                            vm.roles.view.data = response.data.data;
                            vm.roles.view.show = true;
                        }
                    })
                    .catch(function (response) {
                        console.log(response);
                    });

            },
            actionEdit(id) {
                const vm = this;
                const url = this.roles.source + '/' + id;
                vm.roles.edit.id = id;
                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200) {
                            vm.roles.edit.data = response.data.data;
                            vm.roles.edit.show = true;
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
