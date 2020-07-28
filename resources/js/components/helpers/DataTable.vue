<template>
    <div class="row">
        <div class="col-12">
            <div class="card data-table">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title mb-0">{{ title }}</h3>
                        <div class="card-tools ml-auto form-inline">
                            <div v-if="actions.includes('create')">
                                <button type="button" class="btn btn-primary" @click="create">
                                    <span class="fas fa-plus-circle"></span>
                                    Create
                                </button>
                                &#160;
                            </div>
                            <div class="input-group" style="width: 150px;" v-if="controls.includes('search')">
                                <input type="text" name="table_search" v-model="query.search"
                                       @keyup.enter="fetchIndexData()"
                                       class="form-control float-right"
                                       placeholder="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default" @click="fetchIndexData()"><i
                                        class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div v-if="controls.includes('scope')">
                                &#160;
                                <div class="btn-group">
                                    <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        <span class="fas fa-filter"></span>
                                        {{ scopeText }}
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="javascript: void(0);" class="dropdown-item"
                                           :class="{ active: query.scope === 'active' }"
                                           data-value="active"
                                           @click="scope">Active</a>
                                        <a href="javascript: void(0);" class="dropdown-item"
                                           :class="{ active: query.scope === 'trashed' }"
                                           data-value="trashed"
                                           @click="scope">Trashed</a>
                                        <a href="javascript: void(0);" class="dropdown-item"
                                           :class="{ active: query.scope === 'all' }"
                                           data-value="all"
                                           @click="scope">All</a>
                                    </div>
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
                            <th v-for="column in columns"
                                @click="(column.sortable !== false)?toggleOder(column.name):0"
                                class="no-select" :class="{ 'cursor-pointer': column.sortable !== false}">
                                <span>{{ column.header }}</span>
                                <span v-if="column.name === query.sort">
                                    <span v-if="query.order === 'asc'">&uarr;</span>
                                    <span v-if="query.order === 'desc'">&darr;</span>
                                </span>
                            </th>
                            <th v-if="actions.length">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-if="dataCount === 0">
                            <td :colspan="columns.length" class="text-center">
                                <i>No result found.</i>
                            </td>
                        </tr>
                        <tr v-else v-for="row in model.data">
                            <td v-for="(value,key) in columns">
                                <slot
                                    :name="`column_${value.name}`"
                                    :value="row[value.name]"
                                >{{ row[value.name] }}
                                </slot>
                            </td>
                            <td v-if="actions.length">
                                <span v-if="row.deleted_at != null">
                                     <button type="button" class="btn btn-sm btn-warning"
                                             v-if="trashActions.includes('restore')" @click="restore(row.id)">
                                         <span class="fas fa-trash-restore-alt"></span>
                                         Restore
                                    </button>
                                </span>
                                <span v-else>
                                    <button type="button" class="btn btn-sm btn-outline-info"
                                            v-if="actions.includes('view')" @click="view(row.id)">
                                        <span class="fas fa-eye"></span>
                                        View
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            :data-link="source + '/' + row.id + '/edit'"
                                            v-if="actions.includes('edit')" @click="edit(row.id)">
                                        <span class="fas fa-edit"></span>
                                        Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            :data-link="source + '/' + row.id"
                                            v-if="actions.includes('delete')" @click="remove(row.id)">
                                        <span class="fas fa-trash-alt"></span>
                                        Remove
                                    </button>
                                </span>
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
                                            <li class="page-item" :class="{ disabled: currentPageNumber <= 1 }">
                                                <a href="javascript:void(0);" class="page-link" @click="firstPage()">&laquo;</a>
                                            </li>
                                            <li class="page-item" :class="{ disabled: currentPageNumber <= 1 }">
                                                <a href="javascript:void(0);" class="page-link"
                                                   @click="prev()">&lsaquo;</a></li>
                                            <li class="page-item disabled">
                                                <a href="javascript:void(0);"
                                                   class="page-link">{{ currentPageNumber + ' / ' + lastPageNumber
                                                    }}</a></li>
                                            <li class="page-item"
                                                :class="{ disabled: currentPageNumber >= lastPageNumber }">
                                                <a href="javascript:void(0);" class="page-link"
                                                   @click="next()">&rsaquo;</a></li>
                                            <li class="page-item"
                                                :class="{ disabled: currentPageNumber >= lastPageNumber }">
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

        </div>
    </div>
</template>

<script>
    import Swal from 'sweetalert2'

    export default {
        name: "DataTable",
        props: {
            title: '',
            source: '',
            softDelete: {
                type: Boolean,
                default: false
            },
            columns: {
                required: true,
                type: Array,
                default: function () {
                    return [];
                }
            },
            defaultOrder: {
                type: Array,
                default: function () {
                    return []
                }
            },
            refresh: {
                type: Number,
                default: 0
            },
            actions: {
                type: Array,
                default: function () {
                    return []
                }
            },
            trashActions: {
                type: Array,
                default: function () {
                    return []
                }
            },
            controls: {
                type: Array,
                default: function () {
                    return ['search']
                }
            }
        },
        data() {
            return {
                scopeText: 'Active',
                model: {
                    current_page: 1,
                    last_page: 1,
                    next_page_url: false,
                    prev_page_url: false
                },
                query: {
                    page: 1,
                    sort: (this.defaultOrder[0] !== undefined) ? this.columns[this.defaultOrder[0]].name : null,
                    order: (this.defaultOrder[1] !== undefined) ? this.defaultOrder[1] : 'desc',
                    per_page: 10,
                    search: '',
                    scope: 'active',
                    filter: {}
                }
            }
        },
        created() {
            this.fetchIndexData();
        },
        methods: {
            fetchIndexData() {
                var vm = this;

                var url = vm.source;

                var params = {
                    per_page: vm.query.per_page,
                    page: vm.query.page,
                    search: vm.query.search,
                    scope: vm.query.scope,
                }

                if (vm.query.sort !== "") {
                    params.sort = ((vm.query.order === 'desc') ? '-' : '') + vm.query.sort
                }

                let includes = [];

                for (const prop in vm.columns) {
                    if (vm.columns.hasOwnProperty(prop)) {
                        if (vm.columns[prop].included) {
                            includes.push(vm.columns[prop].name);
                        }

                        if (vm.columns[prop].searchable !== false) {
                            vm.query.filter[vm.columns[prop].name] = vm.query.search;
                        }
                    }
                }

                if (includes.length) {
                    params.append = includes.join();
                }

                params.filter = [];
                params.filter['name'] = 'Ad';

                // for (const prop in vm.query.filter) {
                //     if (vm.query.filter.hasOwnProperty(prop)) {
                //         params.filter[prop] = vm.query.filter[prop];
                //     }
                // }

                axios.get(url, {
                    params: params
                })
                    .then(function (response) {
                        console.log(response)
                        vm.$set(vm.$data, 'model', response.data);
                    })
                    .catch(function (response) {
                        console.log(response);
                    });
            },
            toggleOder(column) {
                if (column === this.query.sort) {
                    if (this.query.order === 'desc') {
                        this.query.order = 'asc';
                    } else {
                        this.query.order = 'desc';
                    }
                } else {
                    this.query.sort = column;
                    this.query.order = 'desc';
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
            scope(event) {
                this.query.scope = event.target.dataset.value;
                this.scopeText = event.target.innerText;
                this.fetchIndexData();
            },
            create() {
                this.$emit('actionCreate');
            },
            view(id) {
                this.$emit('actionView', id);
            },
            edit(id) {
                this.$emit('actionEdit', id);
            },

            remove(id) {
                const vm = this;
                const url = `${this.source}/${id}`;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Please confirm your action",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        axios.delete(url)
                            .then(function (response) {
                                if (response.status === 200) {
                                    Swal.fire(
                                        'Removed!',
                                        '',
                                        'success'
                                    )
                                    vm.fetchIndexData();
                                }
                            })
                            .catch(function (response) {
                                console.log(response);
                            });
                    }
                });
            },
            restore(id) {
                const vm = this;
                const url = `${this.source}/${id}/restore`;

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Please confirm your action",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.value) {
                        axios.patch(url)
                            .then(function (response) {
                                if (response.status === 200) {
                                    Swal.fire(
                                        'Restored!',
                                        '',
                                        'success'
                                    )
                                    vm.fetchIndexData();
                                }
                            })
                            .catch(function (response) {
                                console.log(response);
                            });
                    }
                });
            }
        },
        watch: {
            refresh: function (val) {
                this.fetchIndexData();
            }
        },
        computed: {
            currentPageNumber() {
                return this.model.current_page ? this.model.current_page : 0
            },
            lastPageNumber() {
                return this.model.last_page ? this.model.last_page : 0
            },
            dataCount() {
                return this.model.data ? this.model.data.length : 0;
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
