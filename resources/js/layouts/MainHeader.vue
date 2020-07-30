<template>
    <div>
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                        class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- User Header -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-user-circle"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">
                            <div>Hi {{ user.name }}!</div>
                            <div>
                                <span class="badge badge-sm badge-primary" v-for="role in user.roles">{{ role.name }}</span>
                            </div>
                        </span>
                        <div class="dropdown-divider"></div>
                        <router-link to="/profile" class="dropdown-item">
                            <i class="fas fa-user-circle mr-2"></i> Profile
                        </router-link>
                        <a href="javascript:void(0);" @click="$emit('logout')" to="/profile" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-item dropdown-footer"></div>
                    </div>
                </li>

                <li class="nav-item" v-if="controlSideBar">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"><i
                        class="fas fa-th-large"></i></a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->
    </div>
</template>

<script>
    export default {
        name: "MainHeader",
        props:{
            appNameFirst: {
                type: String,
                required: true
            },
            appNameLast: '',
            user: {
                type: Object,
                required: true
            },
            controlSideBar:{
                type: Boolean,
                default: false
            }
        }
    }
</script>

<style scoped>

</style>
