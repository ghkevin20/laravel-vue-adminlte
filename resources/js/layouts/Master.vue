<template>
    <div class="wrapper">

        <main-header></main-header>

        <main-side-bar
            :user="user"
            :app-name-first="appNameFirst"
            :app-name-last="appNameLast"
        ></main-side-bar>

        <content-wrapper>
            <router-view></router-view>
        </content-wrapper>

        <main-footer :content-left-strong="`${appNameFirst} ${appNameLast} 2020`"></main-footer>

    </div>
</template>

<script>
    import MainHeader from './MainHeader';
    import MainSideBar from './MainSideBar';
    import ContentWrapper from './ContentWrapper';
    import ControlSideBar from './ControlSideBar';
    import MainFooter from './MainFooter';
    import {mapGetters} from 'vuex';

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
            document.querySelector('body').classList.remove('hold-transition')
            $('ul[data-widget="treeview"]').Treeview('init');
        },
        computed:{
            ...mapGetters([
                'appNameFirst',
                'appNameLast',
                'user',
            ])
        }
    }
</script>

<style scoped>

</style>
