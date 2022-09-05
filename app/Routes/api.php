<?php
use App\Config\Route as Route;

//authentication routes
Route::post('/auth/login','AuthController@login');
Route::post('/auth/register','AuthController@register');

//link routes
Route::get('/:link','MainController@index');
Route::post('/link','LinkController@store');
Route::put('/link/:linkId','LinkController@update');
Route::delete('/link/:linkId','LinkController@delete');
