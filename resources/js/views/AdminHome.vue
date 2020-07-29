<template>
    <div>
        <content-header
            :title="this.title"
            :breadCrumbs="this.breadCrumbs"
        ></content-header>
        <main-content>
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ count.newUsers }}</h3>

                            <p>New Users</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <router-link to="/users" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ count.activeUsers }}</h3>

                            <p>Active Users</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <router-link to="/users" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ count.roles }}</h3>

                            <p>Roles</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-lock"></i>
                        </div>
                        <router-link to="/roles" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ count.permissions }}</h3>

                            <p>Permissions</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <router-link to="/permissions" class="small-box-footer">More info <i
                            class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
        </main-content>
    </div>
</template>

<script>
    import ContentHeader from "../layouts/ContentHeader";
    import MainContent from "../layouts/MainContent";
    import axios from "axios";

    export default {
        name: "AdminHome",
        components: {
            ContentHeader, MainContent
        },
        data() {
            return {
                title: 'Home',
                breadCrumbs: [
                    {item: 'Home', active: true},
                ],
                count: {
                    newUsers: 0,
                    activeUsers: 0,
                    roles: 0,
                    permissions: 0,
                }
            }
        },
        methods: {
            countNewUsers() {
                const vm = this;
                const secondsAgo = 86400; // 1 day
                const url = '/api/users/count/new/' + secondsAgo;
                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200) {
                            vm.count.newUsers = response.data.count;
                        }
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
            },
            countActiveUsers() {
                const vm = this;
                const scope = 'active'; // active , trashed, all
                const url = '/api/users/count/scoped/' + scope;
                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200) {
                            vm.count.activeUsers = response.data.count;
                        }
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
            },
            countRoles() {

            },
            countPermissions() {

            },
        },
        mounted() {
            this.countNewUsers();
            this.countActiveUsers();
            this.countRoles();
            this.countPermissions();
        }
    }
</script>

<style>


</style>
