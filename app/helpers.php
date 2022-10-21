<?php

function currentModuleName() {
    $moduleName = 'customer';
    if(\Illuminate\Support\Facades\Route::getCurrentRoute()->middleware()[1] == 'auth:manager')
        $moduleName = 'manager';

    return $moduleName;
}
