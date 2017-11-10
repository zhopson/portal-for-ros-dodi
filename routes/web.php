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

    Route::get('/chains/edit/{id}', 'TSupport\ChainsController@chain_edit')->name('chains.edit');

    Route::post('/chains/update/{id}', 'TSupport\ChainsController@chain_update')->name('chains.update');

    Route::get('/chains/remove/{id}', 'TSupport\ChainsController@chain_remove')->name('chains.remove');

    Route::get('/chains/close/{id}', 'TSupport\ChainsController@chain_close')->name('chains.close');
    
    Route::get('/clients', 'ClientsController@index')->name('clients');

    Route::get('/clients/view/{id}', 'ClientsController@clt_view')->name('clients.view');

    Route::get('/clients/new', 'ClientsController@clt_new')->name('clients.new');
    
    Route::post('/clients/store', 'ClientsController@clt_store')->name('clients.store');

    Route::get('/clients/edit/{id}', 'ClientsController@clt_edit')->name('clients.edit');

    Route::post('/clients/update/{id}', 'ClientsController@clt_update')->name('clients.update');
    
    Route::post('/adresses/adr_part_list', 'AddressController@list_adr_components');
    
    Route::get('/tasks/new/{id}/{ch_id?}', 'TSupport\TasksController@tsk_new')->name('tasks.new');

    Route::post('/tasks/store/{id}', 'TSupport\TasksController@tsk_store')->name('tasks.store');

    Route::get('/tasks/edit/{id}', 'TSupport\TasksController@tsk_edit')->name('tasks.edit');

    Route::post('/tasks/update/{id}', 'TSupport\TasksController@tsk_update')->name('tasks.update');

    Route::get('/requests/new/{id}/{ch_id?}', 'TSupport\RequestsController@req_new')->name('requests.new');

    Route::post('/requests/store/{id}', 'TSupport\RequestsController@req_store')->name('requests.store');

    Route::get('/requests/edit/{id}', 'TSupport\RequestsController@req_edit')->name('requests.edit');

    Route::post('/requests/update/{id}', 'TSupport\RequestsController@req_update')->name('requests.update');
    
    Route::get('calls/edit/{id}', function ($id) {
        return 'call ' . $id;
    })->name('calls.edit');

    Route::get('notes/edit/{id}', function ($id) {
        return 'note ' . $id;
    })->name('notes.edit');
});
