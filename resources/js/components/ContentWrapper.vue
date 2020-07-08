<template>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <ContentHeader></ContentHeader>
        <MainContent></MainContent>
    </div>
    <!-- /.content-wrapper -->
</template>

<script>
    import ContentHeader from "./ContentHeader";
    import MainContent from "./MainContent";

    export default {
        name: "ContentWrapper",
        components: {
            ContentHeader, MainContent
        }
    }
</script>

<style scoped>

</style>
