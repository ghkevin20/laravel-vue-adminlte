<template>
    <div>
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="https://vuejs.org/images/logo.png" alt="LaraVue Logo"
                     class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">LaravelAdmin</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="https://vuejs.org/images/logo.png" class="img-circle elevation-2"
                             alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">Kevin Rosario</a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                             with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <router-link to="/home" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>
                                    Home
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/users" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Users
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <router-link to="/categories" class="nav-link">
                                <i class="nav-icon fas fa-burn"></i>
                                <p>
                                    Categories
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="javascript:void(0);" class="nav-link">
                                <i class="nav-icon fas fa-user-lock"></i>
                                <p>
                                    Roles & Permissions
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <router-link to="/roles" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Roles</p>
                                    </router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link to="/permissions" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Permissions</p>
                                    </router-link>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <router-link to="/profile" class="nav-link" >
                                <i class="nav-icon fas fa-user-circle"></i>
                                <p>
                                    Profile
                                </p>
                            </router-link>
                        </li>
                        <li class="nav-item">
                            <a href="javascript: void(0)" @click="logout" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>
                                    Logout
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
    </div>
</template>

<script>
    export default {
        name: "MainSideBar",
        methods: {
            logout() {
                axios.post('api/logout').then(response => {
                    if (response.status === 200){
                        this.$router.push("/login")
                    }else{
                        console.log(response)
                    }
                }).catch(error => {
                    console.log(error)
                });
            },
            closeAllTreeView() {
                let openTreeViews = document.querySelectorAll('.has-treeview.menu-open');
                openTreeViews.forEach(function (item, index) {
                    let navTreeViews = item.querySelectorAll('.nav-treeview');
                    navTreeViews.forEach(function (item, index) {
                        $(item).slideUp();
                    });
                    item.classList.remove('menu-open');
                });
            },
            checkTreeView() {
                let activeLinks = document.querySelectorAll('.nav-link.active');
                activeLinks.forEach(function (item, index) {
                    let treeView = item.closest('.has-treeview');
                    treeView ? treeView.classList.add('menu-open') : 0;
                });
            },
            navEventListener(){
                let _this = this;
                let singleLinks = document.querySelectorAll('.nav-link:not(.has-treeview)');
                singleLinks.forEach(function (item, index) {
                    if(!item.closest('.has-treeview')){
                        item.addEventListener('click',function(){
                            _this.closeAllTreeView();
                        })
                    }
                });
            }
        },
        mounted() {
            this.checkTreeView();
            this.navEventListener();
        }
    }
</script>

<style scoped>

</style>
