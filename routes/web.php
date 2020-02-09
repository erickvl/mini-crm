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
    return view('welcome');
});

Route::get('/faker', function(Faker $faker) {
    // dd($faker->image('images', 100, 100, null, false));
    // echo '<img src="'.$faker->imageUrl(100, 100).'">';
    dd($faker->imageUrl(100, 100));
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

Route::get('/home', 'HomeController@index')->name('home');
