<template>
    <div>
        <content-header
            :title="this.title"
            :breadCrumbs="this.breadCrumbs"
        ></content-header>
        <main-content>
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6" v-show="show.newUsers">
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
                <div class="col-lg-3 col-6" v-show="show.activeUsers">
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
                <div class="col-lg-3 col-6" v-show="show.roles">
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
                <div class="col-lg-3 col-6" v-show="show.permissions">
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

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-lg-7 connectedSortable">
                    <!-- Custom tabs (Charts with tabs)-->
                    <div class="card" v-if="hasAnyRole(['Super Admin','Admin'])">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Users
                            </h3>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#bar-chart" data-toggle="tab">Bar</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#doughnut-chart" data-toggle="tab">Donut</a>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content p-0">
                                <!-- Morris chart - Sales -->
                                <div class="chart tab-pane active" id="bar-chart"
                                     style="position: relative;">
                                    <users-bar></users-bar>
                                </div>
                                <div class="chart tab-pane" id="doughnut-chart"
                                     style="position: relative;">
                                    <users-doughnut></users-doughnut>
                                </div>
                            </div>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- Welcome -->
                    <div class="card" v-else>
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-bullhorn"></i>
                                Welcome
                            </h3>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="d-flex align-items-center" style="min-height: 300px">
                                        <div class="text-center w-100">
                                            <h1 class="display-2">Hi {{ user.name }} !</h1>
                                            <p class="display-4">Welcome to {{ appNameFirst }} {{ appNameLast }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.Left col -->
                <!-- right col (We are only adding the ID to make the widgets sortable)-->
                <div class="col-lg-5 connectedSortable">

                    <!-- Calendar card -->
                    <div class="card bg-gradient-primary">
                        <div class="card-header border-0">
                            <h3 class="card-title">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Calendar
                            </h3>
                            <!-- card tools -->
                            <div class="card-tools">
                                <button type="button"
                                        class="btn btn-primary btn-sm"
                                        data-card-widget="collapse"
                                        data-toggle="tooltip"
                                        title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                            <!-- /.card-tools -->
                        </div>
                        <div class="card-body">
                            <v-calendar
                                is-expanded
                                is-dark
                                style="background-color: transparent;border: none"
                                :attributes="calendar"></v-calendar>
                        </div>
                        <!-- /.card-body-->
                        <div class="card-footer bg-transparent">

                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- right col -->
            </div>
            <!-- /.row (main row) -->
        </main-content>
    </div>
</template>

<script>
    import ContentHeader from "../../layouts/ContentHeader";
    import MainContent from "../../layouts/MainContent";
    import axios from "axios";
    import {mapGetters} from 'vuex';
    import UsersBar from "./UsersBar";
    import UsersDoughnut from "./UsersDoughnut";
    import VCalendar from 'v-calendar/lib/components/calendar.umd'

    export default {
        name: "MasterHome",
        components: {
            ContentHeader, MainContent, UsersBar, UsersDoughnut, VCalendar
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
                },
                show: {
                    newUsers: false,
                    activeUsers: false,
                    roles: false,
                    permissions: false,
                },
                calendar: [
                    {
                        key: 'today',
                        highlight: true,
                        dates: new Date(),
                    }
                ]
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
                const vm = this;
                const scope = 'active'; // active , trashed, all
                const url = '/api/roles/count/scoped/' + scope;
                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200) {
                            vm.count.roles = response.data.count;
                        }
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
            },
            countPermissions() {
                const vm = this;
                const scope = 'active'; // active , trashed, all
                const url = '/api/permissions/count/scoped/' + scope;
                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200) {
                            vm.count.permissions = response.data.count;
                        }
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
            },
        },
        mounted() {
            if (this.hasPermission('Browse User')) {
                this.countNewUsers();
                this.countActiveUsers();
                this.show.newUsers = true;
                this.show.activeUsers = true;
            }
            if (this.hasPermission('Browse Role')) {
                this.countRoles();
                this.show.roles = true;
            }
            if (this.hasPermission('Browse Permission')) {
                this.countPermissions();
                this.show.permissions = true;
            }
        },
        computed: {
            ...mapGetters([
                'appNameFirst',
                'appNameLast',
                'user',
                'hasAnyRole',
                'hasAnyRole',
                'hasPermission',
            ])
        }
    }
</script>

<style>


</style>
