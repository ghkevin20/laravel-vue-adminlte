<template>
    <div>
        <router-view></router-view>
    </div>
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
</style>
