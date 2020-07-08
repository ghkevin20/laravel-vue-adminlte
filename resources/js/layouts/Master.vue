<template>
    <div class="wrapper">

        <main-header></main-header>

        <main-side-bar></main-side-bar>

        <content-wrapper>
            <router-view></router-view>
        </content-wrapper>

        <control-side-bar></control-side-bar>

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
        }
    }
</script>

<style scoped>

</style>
