<template>
    <div>
        <content-header
            :title="this.title"
            :breadCrumbs="this.breadCrumbs"
        ></content-header>
        <main-content>
            <data-table
                :source="categories.source"
                :soft-delete="true"
                :columns="categories.table.columns"
                :default-order="categories.table.defaultOrder"
                :refresh="categories.table.refresh"
                @actionCreate="actionCreate"
                @actionView="actionView"
                @actionEdit="actionEdit"
            >
            </data-table>
<!--            <view-form-->
<!--                :data="users.view.data"-->
<!--                :show="users.view.show"-->
<!--                @toggleShow="toggleView"-->
<!--            ></view-form>-->
<!--            <create-form-->
<!--                :url="users.source"-->
<!--                :show="users.create.show"-->
<!--                :clear-after-submit="true"-->
<!--                @toggleShow="toggleCreate"-->
<!--                @submit="users.table.refresh++"-->
<!--            ></create-form>-->
<!--            <edit-form-->
<!--                :url="users.source+'/'+users.edit.id"-->
<!--                :data="users.edit.data"-->
<!--                :show="users.edit.show"-->
<!--                @toggleShow="toggleEdit"-->
<!--                @submit="users.table.refresh++"-->
<!--            ></edit-form>-->
        </main-content>
    </div>
</template>

<script>
    import axios from 'axios';
    import ContentHeader from '../../components/ContentHeader';
    import MainContent from '../../components/MainContent';
    import DataTable from "../../components/helpers/DataTable";
    // import CreateForm from "./CreateForm";
    // import ViewForm from "./ViewForm";
    // import EditForm from "./EditForm";


    export default {
        name: "Categories",
        components: {
            ContentHeader, MainContent, DataTable,
            // CreateForm, ViewForm, EditForm
        },
        data() {
            return {
                title: this.$options.name,
                breadCrumbs: [
                    {item: 'Home', to: 'home'},
                    {item: 'Categories', active: true},
                ],
                categories: {
                    source: '/api/categories/datatable',
                    table: {
                        refresh:0,
                        columns: [
                            {'name': 'id', 'header': 'ID'},
                            {'name': 'name', 'header': 'Name'},
                            {'name': 'user_name', 'header': 'User Name'},
                            {'name': 'created_at', 'header': 'Created At'},
                            {'name': 'updated_at', 'header': 'Updated At'},
                        ],
                        defaultOrder: [3, 'desc']
                    },
                    create: {
                        show: false
                    },
                    view: {
                        id:'',
                        data: {},
                        show: false
                    },
                    edit: {
                        id:'',
                        data: {},
                        show: false
                    }
                },
            }
        },
        methods: {
            toggleCreate(isShow) {
                this.categories.create.show = isShow
            },
            toggleView(isShow) {
                this.categories.view.show = isShow
            },
            toggleEdit(isShow) {
                this.categories.edit.show = isShow
            },
            actionCreate() {
                this.categories.create.show = true;
            },
            actionView(id) {
                const vm = this;
                const url = this.categories.source + '/' + id;
                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200) {
                            vm.categories.view.data = response.data.data;
                            vm.categories.view.show = true;
                        }
                    })
                    .catch(function (response) {
                        console.log(response);
                    });

            },
            actionEdit(id) {
                const vm = this;
                const url = this.categories.source + '/' + id;
                vm.categories.edit.id = id;
                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200) {
                            vm.categories.edit.data = response.data.data;
                            vm.categories.edit.show = true;
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
