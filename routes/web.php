<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ParkingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/tes', function () {
    return view('welcome');
});
Route::get('/', function () {
    return redirect()->route('login');
});
Route::group(['middleware' => 'auth'], function () {

    Route::post('parking/datatable', [ParkingController::class, 'datatable'])->name('parking.datatable');
    Route::get('parking/checkout', [ParkingController::class, 'checkout'])->name('parking.checkout');
    Route::get('parking', [ParkingController::class, 'index'])->name('parking.index');
    Route::post('parking', [ParkingController::class, 'store'])->name('parking.store');
    Route::put('parking', [ParkingController::class, 'update'])->name('parking.update');

    Route::post('category/datatable', [CategoryController::class, 'datatable'])->name('category.datatable');
    Route::get('category/get_all_categories', [CategoryController::class, 'get_all_categories'])->name('get-all-categories');
    Route::resource('category', CategoryController::class);
});

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/dashboard', function () {
    $title = 'Dashboard';
    return view('dashboard', compact('title'));
})->name('home');
