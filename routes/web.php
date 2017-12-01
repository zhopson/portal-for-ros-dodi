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

    Route::middleware('role:Учителя')->group(function () {
        Route::get('/telephony', 'TelephonyController@index')->name('telephony');    
    });
    
    Route::get('/chains', 'TSupport\ChainsController@index')->name('chains');

    Route::get('/chains/json', 'TSupport\ChainsController@Get_json_chains');

    Route::get('/chains/view/{id}', 'TSupport\ChainsController@chain_view')->name('chains.view');

    Route::get('/chains/edit/{id}', 'TSupport\ChainsController@chain_edit')->name('chains.edit');

    Route::post('/chains/update/{id}', 'TSupport\ChainsController@chain_update')->name('chains.update');

    Route::get('/chains/remove/{id}', 'TSupport\ChainsController@chain_remove')->name('chains.remove');

    Route::post('/chains/removing/{id}', 'TSupport\ChainsController@chain_removing')->name('chains.removing');

    Route::get('/chains/close/{id}', 'TSupport\ChainsController@chain_close')->name('chains.close');

    Route::post('/chains/closing/{id}', 'TSupport\ChainsController@chain_closing')->name('chains.closing');
    
    Route::get('/clients', 'ClientsController@index')->name('clients');

    Route::get('/clients/json', 'ClientsController@Get_json_clients');
    
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
    
    Route::get('/notes/new/{id}/{ch_id?}', 'TSupport\NotesController@note_new')->name('notes.new');

    Route::post('/notes/store/{id}', 'TSupport\NotesController@note_store')->name('notes.store');
    
    Route::get('/notes/edit/{id}', 'TSupport\NotesController@note_edit')->name('notes.edit');

    Route::post('/notes/update/{id}', 'TSupport\NotesController@note_update')->name('notes.update');

    Route::middleware('admin')->group(function () {
    
        Route::get('/admin/users', 'admin\UsersGroupsController@index')->name('admin.users');
        Route::get('/users/json/{id?}', 'admin\UsersGroupsController@Get_json_users')->name('users.get');
    
        Route::get('/admin/users/new', 'admin\UsersGroupsController@new_usr')->name('admin.users.new');
        Route::post('/admin/users/store', 'admin\UsersGroupsController@store_usr')->name('admin.users.store');
        Route::get('/admin/users/edit/{id}', 'admin\UsersGroupsController@edit_usr')->name('admin.users.edit');
        Route::post('/admin/users/update/{id}', 'admin\UsersGroupsController@update_usr')->name('admin.users.update');
    });
    
    Route::get('/forbidden', function () {
//        $ttt = App\User::find(1)->roles;
//        $ddd = $ttt->where('role', '=' ,'Сотрудники ТП ИНТ')->first();
//        if ($ddd==null) var_dump('Нет роли');
//        else var_dump('Роль найдена');
//        exit;
        return view('deny_info');
    })->name('forbidden');
});
