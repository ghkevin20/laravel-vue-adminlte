<template>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">{{ title }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item" v-for="item in breadCrumbs" :class="{ active : item.active}">
                            <router-link v-if="item.to" :to="item.to">{{ item.item }}</router-link>
                            <span v-else>{{ item.item }}</span>
                        </li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
</template>

<script>
    export default {
        name: "ContentHeader",
        props: [
            'title',
            'breadCrumbs'
        ]
    }
</script>

<style scoped>

</style>
