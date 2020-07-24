<template>
    <transition name="fade-in-up">
        <router-view></router-view>
    </transition>
</template>

<script>
    export default {
        name: "Public",
        beforeCreate() {
            document.querySelector("body").classList.add('hold-transition');
            document.querySelector("html").style.height = '100%';
            document.querySelector("body").style.height = '100%';
        },
        destroyed() {
            document.querySelector("body").classList.remove('hold-transition');
            document.querySelector("html").style.height = 'auto';
            document.querySelector("body").style.height = 'auto';
        }
    }
</script>

<style>
    .login-card-body .input-group .input-group-text, .register-card-body .input-group .input-group-text {
        border-bottom-right-radius: .25rem!important;
        border-top-right-radius: .25rem!important;
    }
    .login-box-msg{

    }
</style>

<style scoped>
    @keyframes fadeInUp {
        from {
            transform: translate3d(0, 40px, 0);
        }

        to {
            transform: translate3d(0, 0, 0);
            opacity: 1
        }
    }

    .fade-in-up-leave-to {
        opacity: 0;
        transition: opacity .6s;
    }

    .fade-in-up-enter {
        opacity: 0;
        transform: translate3d(0, 40px, 0);
    }

    .fade-in-up-enter-to {
        opacity: 0;
        animation-duration: .7s;
        animation-fill-mode: both;
        animation-name: fadeInUp;
    }
</style>
