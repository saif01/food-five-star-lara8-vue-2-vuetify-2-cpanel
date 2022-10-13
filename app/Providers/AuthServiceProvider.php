<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // //Check User
        // Gate::define('User', function($user){
        //     return $user->hasUser($user);
        // });

        // //Check Admin
        // Gate::define('Admin', function($user){
        //     return $user->hasAdmin();
        // });

        // //Check hasOperator
        // Gate::define('Operator', function($user){
        //     return $user->hasOperator();
        // });

        // //Check hasOwner
        // Gate::define('Owner', function($user){
        //     return $user->hasOwner();
        // });


         

        //Administrator
        Gate::define('administration', function($user){
            return $user->hasRole(['Administrator']);
        });


        // // Admin-Management
        // Gate::define('adminManagement', function($user){
        //     return $user->hasAnyRoles(['Administrator','Admin','Admin-Management']);
        // });
        // // Operator-Management
        // Gate::define('operatorManagement', function($user){
        //     return $user->hasAnyRoles(['Administrator','Admin','Operator-Management']);
        // });
        // // Owner-Management
        // Gate::define('ownerManagement', function($user){
        //     return $user->hasAnyRoles(['Administrator','Admin','Owner-Management']);
        // });
        // // Zone-Assign
        // Gate::define('zoneAssign', function($user){
        //     return $user->hasAnyRoles(['Administrator','Admin','Zone-Assign']);
        // });
        // //Role-Assign
        // Gate::define('roleAssign', function($user){
        //     return $user->hasAnyRoles(['Administrator','Admin','Role-Assign']);
        // });
        // // Login-Log
        // Gate::define('loginLog', function($user){
        //     return $user->hasAnyRoles(['Administrator','Admin','Login-Log']);
        // });

      
        // // Product
        // Gate::define('product', function($user){
        //     return $user->hasAnyRoles(['Administrator','Admin','Product']);
        // });
        // // Product-Category
        // Gate::define('productCategory', function($user){
        //     return $user->hasAnyRoles(['Administrator','Admin','Product-Category']);
        // });
        // // Outlet
        // Gate::define('outlet', function($user){
        //     return $user->hasAnyRoles(['Administrator','Admin','Outlet']);
        // });
        // // Announcement
        // Gate::define('announcement', function($user){
        //     return $user->hasAnyRoles(['Administrator','Admin','Announcement']);
        // });


        // // Order Related

        // // Admin-Approve
        // Gate::define('adminApprove', function($user){
        //     return $user->hasAnyRoles(['Administrator', 'Admin', 'Admin-Approve']);
        // });
        // // Manager-Approve
        // Gate::define('managerApprove', function($user){
        //     return $user->hasAnyRoles(['Administrator', 'Admin', 'Manager-Approve']);
        // });
        // // Order-Modify
        // Gate::define('orderModify', function($user){
        //     return $user->hasAnyRoles(['Administrator', 'Admin', 'Order-Modify']);
        // });

        // // Message
        // Gate::define('message', function($user){
        //     return $user->hasAnyRoles(['Administrator', 'Admin', 'Message']);
        // });

        // // Report
        // Gate::define('report', function($user){
        //     return $user->hasAnyRoles(['Administrator', 'Admin', 'Report']);
        // });
        // // Data-Sync
        // Gate::define('dataSync', function($user){
        //     return $user->hasAnyRoles(['Administrator', 'Admin', 'Data-Sync']);
        // });



        // //Add Access
        // Gate::define('add', function($user){
        //     return $user->hasAnyRoles(['Administrator','Admin','Add']);
        // });

        // //Edit Access
        // Gate::define('edit', function($user){
        //     return $user->hasAnyRoles(['Administrator','Admin','Edit']);
        // });

        // //Delete
        // Gate::define('delete', function($user){
        //     return $user->hasAnyRoles(['Administrator','Delete']);
        // });


      

    }
}
