<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage_users',
            'manage_roles',
            'view_dashboard',
            'reply_cs_chats',
            'manage_products',
            'manage_orders',
            'view_reports',
            'manage_promos'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
        
        // Default assignment
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $cs = Role::firstOrCreate(['name' => 'cs', 'guard_name' => 'web']);
        
        // admin gets all (in reality superadmin gets all automatically, but we assign some to admin just in case)
        $admin->syncPermissions($permissions);
        
        $cs->syncPermissions(['view_dashboard', 'reply_cs_chats']);
        
        $this->command->info('Base permissions seeded and assigned to roles successfully.');
    }
}
