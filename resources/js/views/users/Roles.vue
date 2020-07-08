<template>
    <div>
        <content-header
            :title="this.title"
            :breadCrumbs="this.breadCrumbs"
        ></content-header>
        <main-content>

        </main-content>
    </div>
</template>

<script>
    import ContentHeader from "../../components/ContentHeader";
    import MainContent from "../../components/MainContent";

    export default {
        name: "Roles",
        components : {
            ContentHeader,MainContent
        },
        data(){
            return {
                title: this.$options.name,
                breadCrumbs: [
                    {item: 'Home', to: 'home'},
                    {item: 'Profile', active: true},
                ]
            }
        }

    }
</script>

<style scoped>

</style>
