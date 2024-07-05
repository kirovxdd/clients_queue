<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'it`s api app, please set accept: application/json to headers for correct work';
});
