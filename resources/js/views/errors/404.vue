<template>
    <div class="container-fluid d-flex align-items-center error-page-wrapper">
        <div class="error-page">
            <h2 class="headline text-warning"> 404</h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Page not found.</h3>

                <p>
                    We could not find the page you were looking for.
                    Meanwhile, you may <router-link to="/">return to home.</router-link>
                </p>
            </div>
            <!-- /.error-content -->
        </div>
    </div>
</template>

<script>
    export default {
        name: "NotFound"
    }
</script>

<style scoped>
    .error-page-wrapper{
        height:100vh;
    }
</style>
