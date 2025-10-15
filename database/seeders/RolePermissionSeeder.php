<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Member Management
            'view-members',
            'create-members',
            'edit-members',
            'delete-members',
            
            // UMKM
            'view-umkm',
            'verify-umkm',
            'manage-umkm',
            
            // Education
            'view-education-aids',
            'approve-education-aids',
            'manage-education-aids',
            
            // Health Events
            'view-health-events',
            'create-health-events',
            'manage-health-events',
            
            // Legal Aids
            'view-legal-aids',
            'verify-legal-aids',
            'manage-legal-aids',
            
            // Social Activities
            'view-social-activities',
            'create-social-activities',
            'manage-social-activities',
            
            // E-Money
            'view-emoney',
            'manage-emoney',
            
            // Helpdesk
            'view-helpdesk',
            'respond-helpdesk',
            
            // Reports
            'view-reports',
            'export-reports',
            
            // Settings
            'manage-settings',
            'manage-roles',
            'view-activity-logs',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Roles
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'view-members',
            'create-members',
            'edit-members',
            'view-umkm',
            'verify-umkm',
            'view-education-aids',
            'approve-education-aids',
            'view-health-events',
            'create-health-events',
            'view-legal-aids',
            'verify-legal-aids',
            'view-social-activities',
            'create-social-activities',
            'view-emoney',
            'view-helpdesk',
            'respond-helpdesk',
            'view-reports',
        ]);

        $staff = Role::create(['name' => 'staff']);
        $staff->givePermissionTo([
            'view-members',
            'view-umkm',
            'view-education-aids',
            'view-health-events',
            'view-legal-aids',
            'view-social-activities',
            'view-emoney',
            'view-helpdesk',
            'respond-helpdesk',
        ]);

        $member = Role::create(['name' => 'member']);
        // Member permissions akan ditambahkan sesuai kebutuhan
    }
}
