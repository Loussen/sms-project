<?php

use Illuminate\Support\Facades\Route;

function currentModuleName() {
    $moduleName = 'customer';
    if(isset(Route::getCurrentRoute()->middleware()[1]) && Route::getCurrentRoute()->middleware()[1] == 'auth:manager')
        $moduleName = 'manager';

    return $moduleName;
}
