<template>
    <div class="wrapper">

        <main-header></main-header>

        <main-side-bar></main-side-bar>

        <content-wrapper>
            <router-view></router-view>
        </content-wrapper>

        <main-footer></main-footer>

    </div>
</template>

<script>
    import MainHeader from "../components/MainHeader";
    import MainSideBar from '../components/MainSideBar';
    import ContentWrapper from '../components/ContentWrapper';
    import ControlSideBar from '../components/ControlSideBar';
    import MainFooter from '../components/MainFooter';

    export default {
        name: "Master",
        components: {
            MainHeader, MainSideBar, ContentWrapper, ControlSideBar, MainFooter
        },
        beforeRouteEnter(to,from,next) {
            document.querySelector("body").classList.add('hold-transition');
            document.querySelector("body").classList.add('sidebar-mini');
            next()
        },
        destroyed() {
            document.querySelector("body").classList.remove('hold-transition');
            document.querySelector("body").classList.remove('sidebar-mini');
        },
        mounted() {
            $('ul[data-widget="treeview"]').Treeview('init');
        }
    }
</script>

<style scoped>

</style>
