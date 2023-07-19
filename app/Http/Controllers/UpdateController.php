<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TestDbController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use App\Models\Role;
use App\Models\Permission;

class UpdateController extends Controller
{

    public function viewStep1(Request $request)
    {
        return view('update.viewStep1');
    }
    
    public function lastStep(Request $request)
    {
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes 

        try {
           
            Artisan::call('config:cache');
            Artisan::call('config:clear');

            Artisan::call('migrate --force');

            $role = Role::findOrFail(1);

            $permissions = array(
                0 => 'view_employee',
                1 => 'add_employee',
                2 => 'edit_employee',
                3 => 'delete_employee',
                4 => 'company',
                5 => 'department',
                6 => 'designation',
                7 => 'office_shift',
                8 => 'attendance',
                9 => 'leave',
                10 => 'holiday',
                11 => 'Top_products',
                12 => 'Top_customers',
                13 => 'shipment',
                14 => 'users_report',
                15 => 'stock_report',
            );


            foreach ($permissions as $permission_slug) {

                //get the permission object by name
                $perm = Permission::firstOrCreate(['name' => $permission_slug]);
                $data[] = $perm->id;
            }

            $role->permissions()->attach($data);
            
        } catch (\Exception $e) {
            
            return $e->getMessage();
            
            return 'Something went wrong';
        }
        
        return view('update.finishedUpdate');
    }

    

}
