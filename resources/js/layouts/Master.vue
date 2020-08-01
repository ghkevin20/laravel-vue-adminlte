<template>
    <div class="wrapper">

        <main-header
            :user="user"
            :app-name-first="appNameFirst"
            :app-name-last="appNameLast"
            @logout="logout"
        ></main-header>

        <main-side-bar
            :user="user"
            :app-name-first="appNameFirst"
            :app-name-last="appNameLast"
            :has-role="hasRole"
            :has-any-role="hasAnyRole"
            :has-permission="hasPermission"
            :has-any-permission="hasAnyPermission"
            @logout="logout"
        ></main-side-bar>

        <content-wrapper>
            <transition name="fade-in-right">
                <router-view></router-view>
            </transition>
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
        methods:{
            logout() {
                axios.post('/api/logout').then(response => {
                    if (response.status === 200){
                        this.$store.dispatch('disprove');
                        this.$store.dispatch('unsetUser');
                        this.$router.push("/login")
                    }else{
                        console.log(response)
                    }
                }).catch(error => {
                    console.log(error)
                });
            }
        },
        computed:{
            ...mapGetters([
                'appNameFirst',
                'appNameLast',
                'user',
                'hasRole',
                'hasAnyRole',
                'hasPermission',
                'hasAnyPermission'
            ])
        }
    }
</script>

<style scoped>
    @keyframes fadeInRight {
        from {
            transform: translate3d(40px, 0, 0);
        }

        to {
            transform: translate3d(0, 0, 0);
            opacity: 1
        }
    }

    .fade-in-right-leave-to {
        opacity: 0;
    }

    .fade-in-right-enter {
        opacity: 0;
        transform: translate3d(40px, 0, 0);
    }

    .fade-in-right-enter-to {
        opacity: 0;
        animation-duration: .7s;
        animation-fill-mode: both;
        animation-name: fadeInRight;
    }
</style>
