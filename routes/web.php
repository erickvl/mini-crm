<?php

use App\Company;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes([ 'register' => false ]);

Route::group(['middleware' => 'auth:web'], function () {

    Route::get('/admin/dashboard', 'HomeController@index')->name('admin.dashboard');
    Route::get('/admin/companies', 'CompanyController@index')->name('admin.companies');
    Route::get('/admin/companies/add', 'CompanyController@create')->name('admin.companies.add');
    Route::get('/admin/companies/{id}', 'CompanyController@show')->name('admin.companies.view');
    Route::get('/admin/companies/edit/{id}', 'CompanyController@edit')->name('admin.companies.edit');
    Route::delete('/admin/companies/delete/{id}', 'CompanyController@destroy')->name('admin.companies.delete');

    Route::post('/admin/companies/save', 'CompanyController@store')->name('admin.companies.save');
    Route::post('/admin/companies/update', 'CompanyController@update')->name('admin.companies.update');


    // Employee routes
    Route::get('/admin/employees', 'EmployeeController@index')->name('admin.employees');

    // Import and Export Routes
    Route::get('/admin/employees/export/{type?}/{id?}', 'EmployeeController@export')->name('admin.employees.export');
    Route::get('/admin/employees/import', 'EmployeeController@import')->name('admin.employees.import');
    Route::post('/admin/employees/import/save', 'EmployeeController@importSave')->name('admin.employees.import.save');

    Route::get('/admin/employees/add', 'EmployeeController@create')->name('admin.employees.add');
    Route::get('/admin/employees/{id}', 'EmployeeController@show')->name('admin.employees.view');
    Route::get('/admin/employees/edit/{id}', 'EmployeeController@edit')->name('admin.employees.edit');
    Route::delete('/admin/employees/delete/{id}', 'EmployeeController@destroy')->name('admin.employees.delete');

    Route::post('/admin/employees/save', 'EmployeeController@store')->name('admin.employees.save');
    Route::post('/admin/employees/update', 'EmployeeController@update')->name('admin.employees.update');

});

Route::group(['middleware' => 'auth:employee'], function () {

    Route::get('/dashboard', 'HomeController@index')->name('dashboard');

    // Company routes
    Route::get('/companies', 'CompanyController@index')->name('companies');
    Route::get('/companies/{id}', 'CompanyController@show')->name('companies.view');
    
    // Employee routes
    Route::get('/employees', 'EmployeeController@index')->name('employees');

    // Import and Export Routes
    Route::get('/employees/{id}', 'EmployeeController@show')->name('employees.view');
    Route::get('/employees/edit/{id}', 'EmployeeController@edit')->name('employees.edit');
    Route::post('/employees/update', 'EmployeeController@update')->name('employees.update');

});

Route::get('/', function() {
    return redirect('login');
});

Route::get('/login/employee', 'Auth\LoginController@showEmployeeLogin');
Route::post('/login/employee', 'Auth\LoginController@employeeLogin')->name('login.employee');

// Email Routes
Route::get('/emails', 'EmailController@index')->name('emails');
Route::post('/emails/send', 'EmailController@store')->name('emails.send');




