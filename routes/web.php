<?php

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
/*
Route::get('/', function () {
    return view('home');
});
*/

use App\Buku;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index');
Route::get('surat/{surat}', function (Buku $surat) {
    return view('buku.view',compact('surat'));
});
/*
Route::get('/user', 'UserController@index');
Route::get('/user-register', 'UserController@create');
Route::post('/user-register', 'UserController@store');
Route::get('/user-edit/{id}', 'UserController@edit');
*/
// Route::resource('user', 'UserController');

Route::resource('buku', 'BukuController')->except('index');
Route::get('arsip/tahun/{year}', 'BukuController@index')->name('buku.index');
Route::post('filter/custom', 'BukuController@custom')->name('filter.custom');
Route::post('filter/', 'BukuController@filter')->name('filter');
Route::get('buku/filter/{name}', 'BukuController@filterName')->name('filter.name');
Route::get('/export/{type}', 'LaporanController@export')->name('export.excel');
Route::get('/exportDoc/{type}', 'LaporanController@exportDoc')->name('export.doc');
Route::get('/exportDoc/{from}/{to}', 'LaporanController@exportDocCustom')->name('export.doc.custom');

Route::get('/laporan/mingguan', 'LaporanController@minggu')->name('laporan.minggu');
Route::get('/laporan/bulanan', 'LaporanController@bulan')->name('laporan.bulan');
Route::get('/laporan/tahunan', 'LaporanController@tahun')->name('laporan.tahun');


