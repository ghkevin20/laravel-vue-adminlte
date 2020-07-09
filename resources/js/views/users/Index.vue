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
                            <h3 class="card-title">List</h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control float-right"
                                           placeholder="Search">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0" style="height: 300px;">
                            <table class="table table-head-fixed text-nowrap table-bordered">
                                <thead>
                                <tr>
                                    <th>
                                        ID
                                    </th>
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
                                            <span class="fas fa-edit"></span>
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-default">
                                            <span class="fas fa-trash"></span>
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
                                            <span class="fas fa-edit"></span>
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-default">
                                            <span class="fas fa-trash"></span>
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
                                            <span class="fas fa-edit"></span>
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-default">
                                            <span class="fas fa-trash"></span>
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
                                            <span class="fas fa-edit"></span>
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-default">
                                            <span class="fas fa-trash"></span>
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>134</td>
                                    <td>Jim Doe</td>
                                    <td>11-7-2014</td>
                                    <td><span class="tag tag-success">Approved</span></td>
                                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-default">
                                            <span class="fas fa-edit"></span>
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-default">
                                            <span class="fas fa-trash"></span>
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>494</td>
                                    <td>Victoria Doe</td>
                                    <td>11-7-2014</td>
                                    <td><span class="tag tag-warning">Pending</span></td>
                                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-default">
                                            <span class="fas fa-edit"></span>
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-default">
                                            <span class="fas fa-trash"></span>
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>832</td>
                                    <td>Michael Doe</td>
                                    <td>11-7-2014</td>
                                    <td><span class="tag tag-primary">Approved</span></td>
                                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-default">
                                            <span class="fas fa-edit"></span>
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-default">
                                            <span class="fas fa-trash"></span>
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>982</td>
                                    <td>Rocky Doe</td>
                                    <td>11-7-2014</td>
                                    <td><span class="tag tag-danger">Denied</span></td>
                                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-default">
                                            <span class="fas fa-edit"></span>
                                            Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-default">
                                            <span class="fas fa-trash"></span>
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer p-2">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </main-content>
    </div>
</template>

<script>
    import ContentHeader from "../../components/ContentHeader";
    import MainContent from "../../components/MainContent";

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
        }

    }
</script>

<style scoped>

</style>
