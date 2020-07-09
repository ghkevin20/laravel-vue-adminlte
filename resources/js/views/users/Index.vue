<template>
    <div>
        <content-header
            :title="this.title"
            :breadCrumbs="this.breadCrumbs"
        ></content-header>
        <main-content>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h3 class="card-title mb-0">List</h3>
                                <div class="card-tools ml-auto">
                                    <div class="input-group" style="width: 150px;">
                                        <input type="text" name="table_search" class="form-control float-right"
                                               placeholder="Search">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default"><i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>183</td>
                                        <td>John Doe</td>
                                        <td>11-7-2014</td>
                                        <td><span class="tag tag-success">Approved</span></td>
                                        <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-default">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-default">
                                                <i class="fas fa-trash"></i>
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>219</td>
                                        <td>Alexander Pierce</td>
                                        <td>11-7-2014</td>
                                        <td><span class="tag tag-warning">Pending</span></td>
                                        <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-default">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-default">
                                                <i class="fas fa-trash"></i>
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>657</td>
                                        <td>Bob Doe</td>
                                        <td>11-7-2014</td>
                                        <td><span class="tag tag-primary">Approved</span></td>
                                        <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-default">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-default">
                                                <i class="fas fa-trash"></i>
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>175</td>
                                        <td>Mike Doe</td>
                                        <td>11-7-2014</td>
                                        <td><span class="tag tag-danger">Denied</span></td>
                                        <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-default">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-default">
                                                <i class="fas fa-trash"></i>
                                                Remove
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer ">
                            <div class="row">
                                <div class="col-sm-12 col-md-4 d-flex">
                                    <div class="form-inline mx-auto mx-md-0">
                                        <div class="form-group">
                                            <span class="font-weight-light d-inline">Show</span>
                                        </div>

                                        <div class="form-group">
                                            <select class="form-control text-center" name="table_per_page">
                                                <option value="10" selected>10</option>
                                                <option value="50">50</option>
                                                <option value="100">100</option>
                                                <option value="500">500</option>
                                                <option value="1000">1000</option>
                                                <option value="All">All</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <span class="font-weight-light d-inline">items</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-12 col-md-4 d-flex">
                                    <div class="d-flex align-items-center mx-auto">
                                        <span>Showing 1 - 10 of 100 items</span>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="table-pagination mx-auto ml-md-auto mx-md-0">
                                            <nav aria-label="Page navigation example">
                                                <ul class="pagination mb-0">
                                                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </main-content>
    </div>
</template>

<script>
    import ContentHeader from '../../components/ContentHeader';
    import MainContent from '../../components/MainContent';

    export default {
        name: "Users",
        components: {
            ContentHeader, MainContent
        },
        data() {
            return {
                title: this.$options.name,
                breadCrumbs: [
                    {item: 'Home', to: 'home'},
                    {item: 'Users', active: true},
                ]
            }
        },
        mounted() {

        }

    }
</script>

<style scoped>

</style>
