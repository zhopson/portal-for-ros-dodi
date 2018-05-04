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

//Auth::routes();

        Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\LoginController@login');

Route::middleware('auth')->group(function () {

    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('lc', function () {
        return redirect('/home');
    });
    
    Route::get('/lc/personal', 'HomeController@personal_view')->name('lc.personal');
    Route::get('/lc/personal/new', 'HomeController@personal_new')->name('lc.personal.new');
    Route::post('/lc/personal/store', 'HomeController@personal_store')->name('lc.personal.store');    

//    Route::middleware('role:Учителя')->group(function () {
//        Route::get('/telephony', 'TelephonyController@index')->name('telephony');    
//    });
    
//    Route::get('/telephony', 'TelephonyController@index')->name('telephony')
//            ->middleware('role:Сотрудники ТП ИНТ','role:Сотрудники ТП РОС','role:Сотрудники ТП ГБУ РЦИТ');

    //Route::middleware(['role:Сотрудники ТП ИНТ','role:Сотрудники ТП РОС','role:Сотрудники ТП ГБУ РЦИТ'])->group(function () {
//    Route::group(['middleware' => ['role:Сотрудники ТП ИНТ','role:Сотрудники ТП РОС','role:Сотрудники ТП ГБУ РЦИТ']],function () {
    //Route::middleware('role:Сотрудники ТП ИНТ,Сотрудники ТП РОС,Сотрудники ТП ГБУ РЦИТ')->group(function () {        
    //Route::group(['middleware' => ['TP_users']],function () {
    
    Route::middleware('role:Сотрудники ТП РОС')->group(function () {    
        
        Route::get('/telephony', 'TelephonyController@index')->name('telephony');    
        Route::get('/telephony/stat', 'TelephonyController@stat_index')->name('telephony.stat');
        Route::get('/telephony/common_call_list', 'TelephonyController@common_call_list_index')->name('telephony.common_call_list');
        Route::get('/telephony/call_list', 'TelephonyController@call_list_index')->name('telephony.call_list');
        Route::get('/telephony/calls_list/json', 'TelephonyController@Get_json_calls_list');
        Route::post('/telephony/reports/ajax', 'TelephonyController@Get_ajax_reports');
        Route::get('/telephony/reports/ajax_rep_table', 'TelephonyController@Get_ajax_reports_table');
        
        Route::get('/netflow/clients/graph/{id?}/{ip?}', 'NetFlowController@clt_traf_gr')->name('netflow.clients.graph');
        Route::any('/netflow/clients/ajax_get_traf', 'NetFlowController@do_ajax_get_traf');
        Route::get('/netflow/common/graph', 'NetFlowController@cmn_traf_gr')->name('netflow.common.graph');
        
        Route::get('/documents', 'WikiController@index')->name('documents');
        Route::get('/documents/json/{keywords?}/{mode?}', 'WikiController@Get_json_docs_list');
        Route::get('/documents/view/{id}', 'WikiController@doc_view')->name('documents.view');
        Route::get('/documents/new', 'WikiController@doc_new')->name('documents.new');
        Route::get('/documents/edit/{id}', 'WikiController@doc_edit')->name('documents.edit');
        Route::post('/documents/store', 'WikiController@doc_store')->name('documents.store');
        Route::post('/documents/update/{id}', 'WikiController@doc_update')->name('documents.update');
        Route::get('/documents/remove/{id}', 'WikiController@doc_remove')->name('documents.remove');
        
        Route::post('/documents/ajax_upload_file', 'WikiController@do_ajax_upload_file');
        Route::post('/documents/ajax_remove_file', 'WikiController@do_ajax_remove_file');
        Route::post('/documents/ajax_remove_doc', 'WikiController@do_ajax_remove_doc');
        
        Route::get('/clients/edit/{id}/{src?}', 'ClientsController@clt_edit')->name('clients.edit');        
        Route::get('/clients/new/{usr_id?}', 'ClientsController@clt_new')->name('clients.new');
        Route::post('/clients/store', 'ClientsController@clt_store')->name('clients.store');
        Route::post('/clients/update/{id}', 'ClientsController@clt_update')->name('clients.update');
        
        
    });
    
    Route::middleware('role_tp')->group(function () {
        
        Route::get('/tasks/new/{id}/{ch_id?}', 'TSupport\TasksController@tsk_new')->name('tasks.new');
        Route::post('/tasks/store/{id}', 'TSupport\TasksController@tsk_store')->name('tasks.store');
        Route::get('/tasks/edit/{id}', 'TSupport\TasksController@tsk_edit')->name('tasks.edit');
        Route::post('/tasks/update/{id}', 'TSupport\TasksController@tsk_update')->name('tasks.update');

        Route::get('/calls/new/{id}/{ch_id?}', 'TSupport\CallsController@call_new')->name('calls.new');
        Route::post('/calls/store/{id}', 'TSupport\CallsController@call_store')->name('calls.store');
        Route::get('/calls/edit/{id}', 'TSupport\CallsController@call_edit')->name('calls.edit');
        Route::post('/calls/update/{id}', 'TSupport\CallsController@call_update')->name('calls.update');
        
        Route::get('/chains/edit/{id}', 'TSupport\ChainsController@chain_edit')->name('chains.edit');
        Route::post('/chains/update/{id}', 'TSupport\ChainsController@chain_update')->name('chains.update');
        Route::get('/chains/remove/{id}', 'TSupport\ChainsController@chain_remove')->name('chains.remove');
        Route::post('/chains/removing/{id}', 'TSupport\ChainsController@chain_removing')->name('chains.removing');
        Route::get('/chains/close/{id}', 'TSupport\ChainsController@chain_close')->name('chains.close');
        Route::post('/chains/closing/{id}', 'TSupport\ChainsController@chain_closing')->name('chains.closing');
        
        Route::post('/clients/ajax_get_chains_opened', 'TSupport\AjaxController@Get_json_chains_opened');
        Route::post('/clients/ajax_create_chain_by_call', 'TSupport\AjaxController@do_create_chain_by_call');
        Route::post('/clients/ajax_update_call_status_by_tel', 'TSupport\AjaxController@do_update_call_by_tel');
        Route::post('/clients/ajax_update_cdr_user', 'TSupport\AjaxController@do_update_cdr_user');

        Route::get('/contacts', 'ContactsController@index')->name('contacts');
        Route::get('/contacts/json', 'ContactsController@Get_json_contacts');
        
        Route::get('/contacts_clt/{punkt}', 'ContactsController@index4clt')->name('contacts_clt');;
        Route::get('/contacts/json4clt/{punkt}', 'ContactsController@Get_json_contacts4clt');
        
        Route::get('/contacts/new', 'ContactsController@cnt_new')->name('contacts.new');
        Route::post('/contacts/store', 'ContactsController@contacts_store')->name('contacts.store');
        Route::get('/contacts/edit/{id}', 'ContactsController@contacts_edit')->name('contacts.edit');        
        Route::post('/contacts/update/{id}', 'ContactsController@contacts_update')->name('contacts.update');

        Route::get('/contracts', 'ContractsController@index')->name('contracts');
        Route::get('/contracts/json', 'ContractsController@Get_json_contracts');
        Route::post('/contracts/ajax_add', 'ContractsController@cntr_add');
        Route::post('/contracts/ajax_edit', 'ContractsController@cntr_edit');
        Route::post('/contracts/ajax_save', 'ContractsController@cntr_save');

        Route::get('/providers', 'ProvidersController@index')->name('providers');
        Route::get('/providers/json', 'ProvidersController@Get_json_providers');
        Route::post('/providers/ajax_add', 'ProvidersController@prd_add');
        Route::post('/providers/ajax_edit', 'ProvidersController@prd_edit');
        Route::post('/providers/ajax_save', 'ProvidersController@prd_save');
        
        Route::get('/groups', 'GroupsController@index')->name('groups');
        Route::get('/groups/json', 'GroupsController@Get_json_groups');
        Route::post('/groups/ajax_add', 'GroupsController@grp_add');
        Route::post('/groups/ajax_edit', 'GroupsController@grp_edit');
        Route::post('/groups/ajax_save', 'GroupsController@grp_save');

        Route::get('/categories', 'CategoriesController@index')->name('categories');
        Route::get('/categories/json', 'CategoriesController@Get_json_categories');
        Route::post('/categories/ajax_add', 'CategoriesController@cat_add');
        Route::post('/categories/ajax_edit', 'CategoriesController@cat_edit');
        Route::post('/categories/ajax_save', 'CategoriesController@cat_save');
        
        
//        Route::middleware('role:Учителя')->group(function () {
//
//            Route::get('/chains', 'TSupport\ChainsController@index')->name('chains');
//            Route::get('/chains/json', 'TSupport\ChainsController@Get_json_chains');
//    
//            Route::get('/clients', 'ClientsController@index')->name('clients');
//            Route::get('/clients/json', 'ClientsController@Get_json_clients');
//            Route::get('/clients/view/{id}', 'ClientsController@clt_view')->name('clients.view');
//        
//        });
        
    });
    
//    Route::get('/telephony', 'TelephonyController@index')->name('telephony')
//            ->middleware('role:Сотрудники ТП ИНТ','role:Сотрудники ТП РОС','role:Сотрудники ТП ГБУ РЦИТ');

    Route::middleware('role_tp_tch')->group(function () {
        
            Route::get('/chains', 'TSupport\ChainsController@index')->name('chains');
            Route::get('/chains/json', 'TSupport\ChainsController@Get_json_chains');

            Route::get('/chains_clt/{clt_id}', 'TSupport\ChainsController@index4clt')->name('chains_clt');
            Route::get('/chains/json4clt/{clt_id}', 'TSupport\ChainsController@Get_json_chains4clt');

            Route::get('/clients', 'ClientsController@index')->name('clients');
            Route::get('/clients/json', 'ClientsController@Get_json_clients');
            Route::get('/clients/view/{id}', 'ClientsController@clt_view')->name('clients.view');
            //Route::post('/clients/ajax_get_chains_opened', 'TSupport\AjaxController@Get_json_chains_opened');
            
            Route::get('/calls', 'TSupport\CallsController@index')->name('calls');
            Route::get('/calls/json', 'TSupport\CallsController@Get_json_calls');

            Route::get('/requests', 'TSupport\RequestsController@index')->name('requests');
            Route::get('/requests/json', 'TSupport\RequestsController@Get_json_requests');
            
            Route::get('/tasks', 'TSupport\TasksController@index')->name('tasks');
            Route::get('/tasks/json', 'TSupport\TasksController@Get_json_tasks');

            Route::get('/notes', 'TSupport\NotesController@index')->name('notes');
            Route::get('/notes/json', 'TSupport\NotesController@Get_json_notes');
            
    });
    
    Route::get('/chains/view/{id}', 'TSupport\ChainsController@chain_view')->name('chains.view');
  
    Route::post('/adresses/adr_part_list', 'AddressController@list_adr_components');
    
    Route::post('/adresses/chg_adr_components', 'AddressController@do_chg_adr_components');
    
    
    
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
