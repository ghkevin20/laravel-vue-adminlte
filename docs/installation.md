# Installation

Setup environment and go to the project folder.

``` bash
cd {your_path}/laravel-vue-admin
```
12:30 lunch
Run composer install

```bash
composer install
```

Run npm install

```bash
npm install
```

Setup you `.env` file. This will copy `.env.example` to `.env`

```bash
composer run-script post-root-package-install
```

Make sure to set your database credentials correctly.

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_user_name
DB_PASSWORD=your_password
```

The app uses <a href="https://laravel.com/docs/7.x/sanctum#spa-authentication">Laravel Sanctum SPA authentication</a> make sure to declare your 
`SESSION_DOMAIN` and `SANCTUM_STATEFUL_DOMAINS`.

```dotenv
SESSION_DOMAIN="laravel-vue-admin.test"
SANCTUM_STATEFUL_DOMAINS="laravel-vue-admin.test"
```

The app provides installation artisan command. 

```bash
php artisan laravel-vue-admin:install
```

This generates the app keys and will also perform the database migrations.

Default Roles are: 
* Super Admin
* Admin
* User

Default Users:
* superadmin@demo.com
* admin@demo.com
* user@demo.com

Default Password:
* *password*

If you want to include sample data upon installation add `--sample` option.

```bash
php artisan laravel-vue-admin:install --sample
```

If you want to declare your email as super admin set `--super-admin` option.

```bash
php artisan laravel-vue-admin:install --super-admin=youremail@email.com
```

Wait the installation process, and you're done.





