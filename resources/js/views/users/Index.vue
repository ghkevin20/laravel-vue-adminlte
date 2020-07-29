<template>
    <div>
        <content-header
            :title="this.title"
            :breadCrumbs="this.breadCrumbs"
        ></content-header>
        <main-content>
            <data-table
                title="List"
                :source="users.source"
                :soft-delete="true"
                :columns="users.table.columns"
                :default-order="users.table.defaultOrder"
                :refresh="users.table.refresh"
                :actions="users.actions"
                :trash-actions="users.trashActions"
                :controls="['search','scope']"
                @actionCreate="actionCreate"
                @actionView="actionView"
                @actionEdit="actionEdit"
            >
                <template v-slot:column_avatar="{ value }">
                    <img :src="`/storage/avatars/${value}`" alt="Avatar" height="64" width="64">
                </template>
            </data-table>
            <view-form
                :data="users.view.data"
                :show="users.view.show"
                @toggleShow="toggleView"
            ></view-form>
            <create-form
                :url="users.source"
                :show="users.create.show"
                :clear-after-submit="true"
                @toggleShow="toggleCreate"
                @submit="users.table.refresh++"
            ></create-form>
            <edit-form
                :url="users.source+'/'+users.edit.id"
                :data="users.edit.data"
                :show="users.edit.show"
                @toggleShow="toggleEdit"
                @submit="users.table.refresh++"
            ></edit-form>
        </main-content>
    </div>
</template>

<script>
    import axios from 'axios';
    import {mapGetters} from 'vuex';
    import ContentHeader from '../../layouts/ContentHeader';
    import MainContent from '../../layouts/MainContent';
    import DataTable from "../../components/helpers/DataTable";
    import CreateForm from "./CreateForm";
    import ViewForm from "./ViewForm";
    import EditForm from "./EditForm";


    export default {
        name: "Users",
        components: {
            ContentHeader, MainContent, DataTable, CreateForm, ViewForm, EditForm
        },
        data() {
            return {
                title: this.$options.name,
                breadCrumbs: [
                    {item: 'Home', to: '/home'},
                    {item: 'Users', active: true},
                ],
                users: {
                    source: '/api/users',
                    table: {
                        refresh: 0,
                        columns: [
                            {name: 'id', header: 'ID'},
                            {name: 'avatar', header: 'Avatar', sortable: false},
                            {name: 'name', header: 'Name'},
                            {name: 'gender', header: 'Gender'},
                            {name: 'email', header: 'Email'},
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
                        defaultOrder: [6, 'desc']
                    },
                    actions: [],
                    trashActions: [],
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
                this.users.create.show = isShow
            },
            toggleView(isShow) {
                this.users.view.show = isShow
            },
            toggleEdit(isShow) {
                this.users.edit.show = isShow
            },
            actionCreate() {
                this.users.create.show = true;
            },
            actionView(id) {
                const vm = this;
                const url = this.users.source + '/' + id;
                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200) {
                            vm.users.view.data = response.data.data;
                            vm.users.view.show = true;
                        }
                    })
                    .catch(function (response) {
                        console.log(response);
                    });

            },
            actionEdit(id) {
                const vm = this;
                const url = this.users.source + '/' + id;
                vm.users.edit.id = id;
                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200) {
                            vm.users.edit.data = response.data.data;
                            vm.users.edit.show = true;
                        }
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
            },
            setPermissionControl() {
                // users.actions
                if (this.hasPermission('Create User')) {
                    this.users.actions.push('create');
                }
                if (this.hasPermission('View User')) {
                    this.users.actions.push('view');
                }
                if (this.hasPermission('Edit User')) {
                    this.users.actions.push('edit');
                }
                if (this.hasPermission('Delete User')) {
                    this.users.actions.push('delete');
                }
                // users.trashActions
                if (this.hasPermission('Restore User')) {
                    this.users.trashActions.push('restore');
                }
            }
        },
        mounted() {
            this.setPermissionControl();
        },
        computed: {
            ...mapGetters([
                'hasPermission'
            ])
        }
    }
</script>

<style scoped>

</style>
