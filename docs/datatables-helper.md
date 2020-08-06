# Datatable Helper

The app provides a custom helper for easily listing out records from the database.

Just simple include the helper in your controller and init by calling `Datatable::make()`

```php
use App\Helpers\Datatable;
```

```php
/**
 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */
public function index()
{
    return Datatable::make(
        User::class,
        ['id', 'name', 'gender', 'email', 'created_at', 'updated_at'], // searchFields
        ['id', 'avatar', 'name', 'gender', 'email', 'created_at', 'updated_at', 'deleted_at'], // allowedFields
        ['id', 'avatar', 'name', 'gender', 'email', 'created_at', 'updated_at'], // allowedSorts
        ['roles_list'] // allowedAppends
    );
}
```

Datatable helper is located in `app/Helpers/Datatable.php`
 
It uses <a href="https://docs.spatie.be/laravel-query-builder/v2/introduction/">Spatie Laravel Query Builder</a> checkout here
how the following parameters works.