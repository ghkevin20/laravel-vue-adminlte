<template>
    <div>
        <content-header
            :title="this.title"
            :breadCrumbs="this.breadCrumbs"
        ></content-header>
        <main-content>
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                     :src="`/storage/avatars/${user.avatar}`"
                                     alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{ user.name }}</h3>

                            <a href="#" class="btn btn-primary btn-block"><b>Upload Avatar</b></a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#details" data-toggle="tab">Details</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="#security" data-toggle="tab">Security</a>
                                </li>
                            </ul>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="active tab-pane" id="details">
                                    <details-form
                                        :url="detailsUrl"
                                        :data="user"
                                    ></details-form>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="security">
                                    <security-form
                                        :url="securityUrl"
                                    >
                                    </security-form>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
        </main-content>
    </div>
</template>

<script>
    import ContentHeader from "../../layouts/ContentHeader";
    import MainContent from "../../layouts/MainContent";
    import {mapGetters} from "vuex";
    import DetailsForm from "./DetailsForm";
    import SecurityForm from "./SecurityForm";

    export default {
        name: "Profile",
        components: {
            ContentHeader, MainContent, DetailsForm, SecurityForm
        },
        data() {
            return {
                title: this.$options.name,
                breadCrumbs: [
                    {item: 'Home', to: 'home'},
                    {item: 'Profile', active: true},
                ],
                detailsUrl: '/api/profile/update',
                securityUrl: '/api/profile/change-password',
                changeAvatarUrl: '/api/profile/change-avatar',
            }
        },
        computed: {
            ...mapGetters([
                'user'
            ])
        }

    }
</script>

<style scoped>

</style>
