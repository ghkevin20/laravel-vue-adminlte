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
                            <h3>150</h3>

                            <p>New Users</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <router-link to="/users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>53</h3>

                            <p>Active Users</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <router-link to="/users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>44</h3>

                            <p>Roles</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-lock"></i>
                        </div>
                        <router-link to="/roles" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>65</h3>

                            <p>Permissions</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <router-link to="/permissions" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></router-link>
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

    export default {
        name: "AdminHome",
        components : {
            ContentHeader,MainContent
        },
        data(){
            return {
                title: 'Home',
                breadCrumbs: [
                    {item: 'Home', active: true},
                ],
            }
        },
    }
</script>

<style >


</style>
