<template>
    <!-- Main Footer -->
    <footer class="main-footer">
        <!-- To the right -->
        <div class="float-right d-none d-sm-inline" v-if="contentRight">
            {{ contentRight }}
        </div>
        <!-- Default to the left -->
        <strong v-if="contentLeftStrong">{{ contentLeftStrong }}</strong>{{ contentLeft }}
    </footer>
</template>

<script>
    export default {
        name: "MainFooter",
        props: {
            contentRight:'',
            contentLeft:'',
            contentLeftStrong:''
        }
    }
</script>

<style scoped>

</style>
