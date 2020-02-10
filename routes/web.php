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

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/faker', function(Faker $faker) {
    return view('auth.login',[
        'title' => 'test',
        'loginRoute' => 'test',
        'forgotPasswordRoute' => 'password.request',
    ]);
});

Route::get('/companies', function() {
    $companies = Company::all();

    foreach ($companies as $company) {
        # code...
        $asset = asset('images/'.$company->logo);
        echo $asset;
        echo "<img src='$asset' >";
        dd( $company->logo );
    }
});

Auth::routes([ 'register' => false ]);

// Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('dashboard');

// Company routes
Route::get('/companies', 'CompanyController@index')->name('companies');

Route::get('/companies/add', 'CompanyController@create')->name('companies.add');
Route::get('/companies/{id}', 'CompanyController@show')->name('companies.view');
Route::get('/companies/edit/{id}', 'CompanyController@edit')->name('companies.edit');
Route::delete('/companies/delete/{id}', 'CompanyController@destroy')->name('companies.delete');

Route::post('/companies/save', 'CompanyController@store')->name('companies.save');
Route::post('/companies/update', 'CompanyController@update')->name('companies.update');


// Employee routes
Route::get('/employees', 'EmployeeController@index')->name('employees');

Route::get('/employees/add', 'EmployeeController@create')->name('employees.add');
Route::get('/employees/{id}', 'EmployeeController@show')->name('employees.view');
Route::get('/employees/edit/{id}', 'EmployeeController@edit')->name('employees.edit');
Route::delete('/employees/delete/{id}', 'EmployeeController@destroy')->name('employees.delete');

Route::post('/employees/save', 'EmployeeController@store')->name('employees.save');
Route::post('/employees/update', 'EmployeeController@update')->name('employees.update');

