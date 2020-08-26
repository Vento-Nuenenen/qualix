<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use NoelDeMartin\LaravelCypress\Http\Controllers\CypressController as LaravelCypressController;

class CypressController extends LaravelCypressController {

    public function createSnapshot($name = 'cypress_savepoint') {
        Artisan::call("snapshot:create $name");
        return $name;
    }

    public function restoreSnapshot($name = 'cypress_savepoint') {
        Artisan::call("snapshot:load $name");
    }

    public function cleanupSnapshots($name = 'cypress_savepoint') {
        Artisan::call("snapshot:cleanup --keep=0");
    }

}
