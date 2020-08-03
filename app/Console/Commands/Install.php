<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-vue-admin:install {--sample} {--super-admin=superadmin@demo.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installation process.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /**
         * Installation
         */
        $this->info('Starting Installation...');

        // Clear Cache
        $this::call('cache:clear');
        $this::call('route:clear');
        $this::call('view:clear');

        // Generate Key
        $this::call('key:generate');

        // Migrate fresh
        $this->call('migrate:fresh');

        // Create Default Roles And Permissions
        $this->call('db:seed', ['--class' => 'RoleAndPermissionSeeder']);

        // Create Super Admin
        User::create([
            'name' => 'John Doe',
            'gender' => 'Male',
            'email' => $this->option('super-admin'),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ])->assignRole('Super Admin');
        $this->info('Created Super Admin: ' . $this->option('super-admin') . ' Password: password');

        // Create Admin
        User::create([
            'name' => 'Jane Doe',
            'gender' => 'Female',
            'email' => 'admin@demo.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ])->assignRole('Admin');
        $this->info('Created Admin: admin@demo.com Password: password');

        // Create User
        User::create([
            'name' => 'Jane Smith',
            'gender' => 'Female',
            'email' => 'user@demo.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ])->assignRole('User');
        $this->info('Created User: user@demo.com Password: password');

        // Check to include sample data
        if ($this->option('sample')) {
            $this->info('With sample');
        }

        $this->info('Finished installation. Thank you.');
    }
}
