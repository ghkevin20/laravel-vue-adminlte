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
    import ContentHeader from "../../layouts/ContentHeader";
    import MainContent from "../../layouts/MainContent";

    export default {
        name: "Roles",
        components: {
            ContentHeader, MainContent
        },
        data() {
            return {
                title: this.$options.name,
                breadCrumbs: [
                    {item: 'Home', to: 'home'},
                    {item: 'Roles', active: true},
                ]
            }
        }

    }
</script>

<style scoped>

</style>
