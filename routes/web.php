<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',HomeController::class)->name('mo')->middleware('throttle:uploads');


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale() .'/dashboard',
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function(){ //...
   
    Route::view('/', 'dashboard')->name('dashboard')->middleware('test:ahmed');
    // ============================================= products
    Route::resource('products', ProductController::class);
    Route::get('/download',[ProductController::class,'download']);
});

require __DIR__.'/auth.php';


