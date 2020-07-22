<template>
    <div>
        <content-header
            :title="this.title"
            :breadCrumbs="this.breadCrumbs"
        ></content-header>
        <main-content>
            <div>
                Logged in as
            </div>
        </main-content>
    </div>
</template>

<script>
    import ContentHeader from "../components/ContentHeader";
    import MainContent from "../components/MainContent";

    export default {
        name: "AdminHome",
        components : {
            ContentHeader,MainContent
        },
        data(){
            return {
                title: 'Home',
                breadCrumbs: [
                    {item: 'Home', active: true},
                ],
            }
        },
    }
</script>

<style scoped>

</style>
