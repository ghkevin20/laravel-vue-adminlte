<template>
    <div class="row">
        <div class="col-12">
            <div class="card data-table">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title mb-0">List</h3>
                        <div class="card-tools ml-auto form-inline">
                            <slot name="card-tools"></slot>
                            &#160;
                            <div class="input-group" style="width: 150px;">
                                <input type="text" name="table_search" v-model="query.search_input"
                                       @keyup.enter="fetchIndexData()"
                                       class="form-control float-right"
                                       placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default" @click="fetchIndexData()"><i
                                        class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            &#160;
                            <div class="btn-group" v-if="softDelete">
                                <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                    {{ query.filter }}
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="javascript: void(0);" class="dropdown-item"
                                       :class="{ active: query.filter === 'Active' }" @click="filter($event)">Active</a>
                                    <a href="javascript: void(0);" class="dropdown-item"
                                       :class="{ active: query.filter === 'Trashed' }"
                                       @click="filter($event)">Trashed</a>
                                    <a href="javascript: void(0);" class="dropdown-item"
                                       :class="{ active: query.filter === 'All' }" @click="filter($event)">All</a>
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
                            <th v-for="column in columns" @click="toggleOder(column)"
                                class="cursor-pointer no-select">
                                <span>{{ column }}</span>
                                <span v-if="column === query.column">
                                    <span v-if="query.direction === 'asc'">&uarr;</span>
                                    <span v-if="query.direction === 'desc'">&darr;</span>
                                </span>
                            </th>
                            <th v-if="actions">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="row in model.data">
                            <td v-for="(value,key) in row">{{ value }}</td>
                            <td v-if="(query.filter === 'All' || query.filter === 'Active') && actions">
                                <button type="button" class="btn btn-sm btn-outline-info"
                                        v-if="actions.includes('view')" @click="view(row.id)">View
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        :data-link="source + '/' + row.id + '/edit'"
                                        v-if="actions.includes('edit')">Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        :data-link="source + '/' + row.id"
                                        v-if="actions.includes('delete')">Remove
                                </button>
                            </td>
                            <td v-if="query.filter === 'Trashed'">
                                <button type="button" class="btn btn-sm btn-outline-warning"
                                        v-if="trashActions.includes('restore')">Restore
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
                                    <span class="font-weight-light d-inline">Show&#160;</span>
                                </div>
                                <div class="form-group">
                                    <select class="form-control text-center" name="table_per_page"
                                            :value="query.per_page" @change="perPage($event.target.value)">
                                        <option value="10">10</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <span class="font-weight-light d-inline">&#160;items</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 d-flex">
                            <div class="d-flex align-items-center mx-auto">
                                <span>Showing {{ model.from }} - {{ model.to }} of {{ model.total }} items</span>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="table-pagination mx-auto ml-md-auto mx-md-0">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination mb-0">
                                            <li class="page-item" :class="{ disabled: model.current_page <= 1 }">
                                                <a href="javascript:void(0);" class="page-link" @click="firstPage()">&laquo;</a>
                                            </li>
                                            <li class="page-item" :class="{ disabled: model.current_page <= 1 }">
                                                <a href="javascript:void(0);" class="page-link"
                                                   @click="prev()">&lsaquo;</a></li>
                                            <li class="page-item disabled"><a href="javascript:void(0);"
                                                                              class="page-link">{{
                                                model.current_page + ' / ' + model.last_page
                                                }}</a></li>
                                            <li class="page-item"
                                                :class="{ disabled: model.current_page >= model.last_page }">
                                                <a href="javascript:void(0);" class="page-link"
                                                   @click="next()">&rsaquo;</a></li>
                                            <li class="page-item"
                                                :class="{ disabled: model.current_page >= model.last_page }">
                                                <a href="javascript:void(0);" class="page-link" @click="lastPage()">&raquo;</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
            <data-table-view :show="actionState.view.show" :data="actionState.view.data" @update="updateView"></data-table-view>
        </div>
    </div>
</template>

<script>
    import DataTableView from "./DataTableView";

    export default {
        name: "DataTable",
        props: {
            'source': '',
            'softDelete': false
        },
        components: {
            DataTableView
        },
        data() {
            return {
                model: {
                    current_page: 1,
                    last_page: 1,
                    next_page_url: false,
                    prev_page_url: false
                },
                columns: {},
                query: {
                    page: 1,
                    column: 'id',
                    direction: 'desc',
                    per_page: 10,
                    search_input: '',
                    filter: 'Active'
                },
                visiblePages: 3,
                actions: ['view', 'edit', 'delete'],
                trashActions: ['restore'],
                actionState: {
                    create: {
                        show: false,
                        data: null
                    },
                    view: {
                        show: false,
                        data: null
                    },
                    edit: {
                        show: false,
                        data: null
                    },
                    delete: {
                        show: false,
                        data: null
                    },
                    restore: {
                        show: false,
                        data: null
                    }
                }
            }
        },
        created() {
            this.fetchIndexData();
        },
        methods: {
            fetchIndexData() {
                var vm = this;
                var url = `${this.source}?column=${this.query.column}&direction=${this.query.direction}&page=${this.query.page}&per_page=${this.query.per_page}&search_input=${this.query.search_input}&filter=${this.query.filter}`;
                axios.get(url)
                    .then(function (response) {
                        vm.$set(vm.$data, 'model', response.data.model);
                        vm.$set(vm.$data, 'columns', response.data.columns);
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
            },
            toggleOder(column) {
                if (column === this.query.column) {
                    if (this.query.direction === 'desc') {
                        this.query.direction = 'asc';
                    } else {
                        this.query.direction = 'desc';
                    }
                } else {
                    this.query.column = column;
                    this.query.direction = 'desc';
                }

                this.fetchIndexData();
            },
            perPage(per_page) {
                this.query.per_page = per_page;
                this.query.page = 1;
                this.fetchIndexData();
            },
            next() {
                if (this.model.next_page_url) {
                    this.query.page++
                    this.fetchIndexData()
                }
            },
            prev() {
                if (this.model.prev_page_url) {
                    this.query.page--
                    this.fetchIndexData()
                }
            },
            firstPage() {
                this.query.page = 1;
                this.fetchIndexData()
            },
            lastPage() {
                this.query.page = this.model.last_page;
                this.fetchIndexData()
            },
            range(start, end) {
                return Array(end - start + 1).fill().map((_, idx) => start + idx)
            },
            filter(event) {
                this.query.filter = event.target.innerText;
                this.fetchIndexData();
            },
            view(id) {
                const vm = this;
                const url = `${this.source}/${id}`;
                axios.get(url)
                    .then(function (response) {
                        if (response.status === 200){
                            vm.actionState.view.data = response.data.data;
                        }
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
                this.actionState.view.show = true;
            },
            updateView(show){
                this.actionState.view.show = show
            }
        }

    }
</script>

<style scoped>
    .cursor-pointer {
        cursor: pointer;
    }

    .no-select {
        -webkit-touch-callout: none; /* iOS Safari */
        -webkit-user-select: none; /* Safari */
        -khtml-user-select: none; /* Konqueror HTML */
        -moz-user-select: none; /* Old versions of Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
        user-select: none;
        /* Non-prefixed version, currently
                                         supported by Chrome, Edge, Opera and Firefox */
    }
</style>
