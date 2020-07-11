<template>
    <div>
        <content-header
            :title="this.title"
            :breadCrumbs="this.breadCrumbs"
        ></content-header>
        <main-content>
            <data-table source="/api/users" soft-delete="true">
                <div slot="card-tools">
                    <button type="button" class="btn btn-primary"> Create</button>
                </div>
            </data-table>
        </main-content>
    </div>
</template>

<script>
    import ContentHeader from '../../components/ContentHeader';
    import MainContent from '../../components/MainContent';
    import DataTable from "../../components/helpers/DataTable";

    export default {
        name: "Users",
        components: {
            ContentHeader, MainContent, DataTable
        },
        data() {
            return {
                title: this.$options.name,
                breadCrumbs: [
                    {item: 'Home', to: 'home'},
                    {item: 'Users', active: true},
                ]
            }
        }
    }
</script>

<style scoped>

</style>
