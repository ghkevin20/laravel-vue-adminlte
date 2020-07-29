<template>
    <div class="container-fluid d-flex align-items-center error-page-wrapper">
        <div class="error-page mt-6">
            <h2 class="headline text-warning"> 403</h2>

            <div class="error-content">
                <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Access Forbidden.</h3>

                <p>
                    You are not allowed to continue this request.
                    Meanwhile, you may <router-link to="/">return to home.</router-link>
                </p>
            </div>
            <!-- /.error-content -->
        </div>
    </div>
</template>

<script>
    export default {
        name: "Forbidden"
    }
</script>

<style scoped>
    .error-page-wrapper{
        height:100vh;
    }
</style>
