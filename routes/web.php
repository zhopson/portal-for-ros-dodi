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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('lc', function () {
        return redirect('/home');
    });

    Route::get('/telephony', 'TelephonyController@index')->name('telephony');

    Route::get('/chains', 'TSupport\ChainsController@index')->name('chains');

    Route::get('/chains/view/{id}', 'TSupport\ChainsController@chain_view')->name('chains.view');
    
    Route::get('/clients', 'ClientsController@index')->name('clients');

    Route::get('/clients/view/{id}', 'ClientsController@clt_view')->name('clients.view');

    Route::get('/clients/new', 'ClientsController@clt_new')->name('clients.new');
    
    Route::post('/clients/store', 'ClientsController@clt_store')->name('clients.store');

    Route::get('/clients/edit/{id}', 'ClientsController@clt_edit')->name('clients.edit');
    
    Route::any('/adresses/regions/list', 'AddressController@list_regions')->name('adresses.regions.list');
    
    Route::get('calls/edit/{id}', function ($id) {
        return 'call ' . $id;
    })->name('calls.edit');

    Route::get('tasks/edit/{id}', function ($id) {
        return 'task ' . $id;
    })->name('tasks.edit');

    Route::get('requests/edit/{id}', function ($id) {
        return 'request ' . $id;
    })->name('requests.edit');

    Route::get('notes/edit/{id}', function ($id) {
        return 'note ' . $id;
    })->name('notes.edit');
});
