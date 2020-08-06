# Datatable Vue Component

`resources/js/components/Datatable.vue`

We also created a vue component which you can easily import in your other vue components and use our [Datatables helper](datatables-helper.md).

#### Usage
```vue
<template>
    <data-table
        title="List"
        :source="users.source"
        :columns="users.table.columns"
        :default-order="users.table.defaultOrder"
        :controls="['search']"
    ></data-table>
</template>

<script>
    import DataTable from "../../components/DataTable";
    
    export default {
            name: "Users",
            components: {
                DataTable
            },
            data() {
                return {
                    users: {
                        source: '/api/users',
                        table: {
                            columns: [
                                {name: 'id', header: 'ID'},
                                {name: 'avatar_url', header: 'Avatar', sortable: false},
                                {name: 'name', header: 'Name'},
                                {name: 'gender', header: 'Gender'},
                                {name: 'email', header: 'Email'},
                                {name: 'created_at', header: 'Created At'},
                                {name: 'updated_at', header: 'Updated At'},
                            ],
                            defaultOrder: [5, 'desc']
                        }
                    },
                }
            }       
    }
</script>
```

#### Attributes

##### title
The title of the datable bootstrap card.

##### source
Url where the axios will perform data fetching.

##### columns

Accepts an array required with `name` and `header` keys.

Assign `name` value same field from the source. while the `header` will be the one shown in the table. 

```javascript
{name:'id',header:'SYSTEM ID'}
```

The columns are sortable by default. if you want to disable sorting just add `sortable:false` in the specific column.
```javascript
{name:'id',header:'SYSTEM ID', sortable:false}
```

##### default-order

You can set the default order by identifying the index of the column you want to order and either `asc` or `desc` direction
```javascript
return {
    users: {
        source: '/api/users',
        table: {
            columns: [
                {name: 'id', header: 'ID'},
                {name: 'avatar_url', header: 'Avatar', sortable: false},
                {name: 'name', header: 'Name'},
                {name: 'gender', header: 'Gender'},
                {name: 'email', header: 'Email'},
                {name: 'created_at', header: 'Created At'},
                {name: 'updated_at', header: 'Updated At'},
            ],
            defaultOrder: [5, 'desc'] // the index of created_at is 5
        }
    },
}
``` 

##### refresh

Use this attribute to refresh the data from the source . Every time the property increments it will trigger the refresh.
 
 
##### actions

Accepts an array can contain `create`, `view`, `edit`, `delete` which applies their own methods.

```vue
<template>
    <data-table
        title="List"
        :source="users.source"
        :columns="users.table.columns"
        :default-order="users.table.defaultOrder"
        :controls="['search']"
        :actions="['edit','delete']"
    ></data-table>
</template>
```

##### trash-actions

Accepts an array can contain `restore` which applies methods each row (trashed items).

```vue
<template>
    <data-table
        title="List"
        :source="users.source"
        :columns="users.table.columns"
        :default-order="users.table.defaultOrder"
        :controls="['search']"
        :actions="['edit','delete']"
        :trash-actions="['restore']"
    ></data-table>
</template>
```

##### controls

Accepts an array can contain `search` and `scope`.

`search` to enable search input to search the table, `scope` to show scope filter *Active, Trashed, All*

#### Events

##### actionCreate

To receive the action when the create button clicked

```vue
<template>
    <data-table
        title="List"
        :source="users.source"
        :columns="users.table.columns"
        :default-order="users.table.defaultOrder"
        :controls="['search']"
        :actions="['edit','delete']"
        :trash-actions="['restore']"
        @actionCreate="alert('Create Button Clicked.')"             
    ></data-table>
</template>
```



##### actionView


To receive the action when the view button clicked, passes the `id` of the row.

```vue
<template>
    <data-table
        title="List"
        :source="users.source"
        :columns="users.table.columns"
        :default-order="users.table.defaultOrder"
        :controls="['search']"
        :actions="['edit','delete']"
        :trash-actions="['restore']"
        @actionEdit="function(id){alert('View: '+id)}"             
    ></data-table>
</template>
```

##### actionEdit


To receive the action when the edit button clicked, passes the `id` of the row.

```vue
<template>
    <data-table
        title="List"
        :source="users.source"
        :columns="users.table.columns"
        :default-order="users.table.defaultOrder"
        :controls="['search']"
        :actions="['edit','delete']"
        :trash-actions="['restore']"
        @actionEdit="function(id){alert('Edit: '+id)}"             
    ></data-table>
</template>
```

#### Custom Column Value

You can use `v-slot` to modify your column value. The slot name of each column is prefix with `column_`

```vue
<template>
    <data-table
        title="List"
        :source="users.source"
        :soft-delete="true"
        :columns="users.table.columns"
        :default-order="users.table.defaultOrder"
        :refresh="users.table.refresh"
        :actions="users.actions"
        :trash-actions="users.trashActions"
        :controls="['search','scope']"
        @actionCreate="actionCreate"
        @actionView="actionView"
        @actionEdit="actionEdit">
        <template v-slot:column_avatar_url="{ value }">
            <img :src="value" alt="Avatar" height="64" width="64">
        </template>
    </data-table>
</template>
```