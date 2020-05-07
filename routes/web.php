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
})->name('welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/settings', 'SettingsController@index')->name('settings');
Route::get('/gathers', 'GatherController@index')->name('gathers');
Route::post('/gather', 'GatherController@store');
Route::delete('/gather/{gather}', 'GatherController@destroy');
Route::get('/main', 'Ajax\MainController@index')->name('main');
Route::get('/stats', 'Ajax\MainController@stats')->name('stats');
Route::get('/main/chart', 'Ajax\DataController@getChart');
Route::get('/main/getpilots', 'Ajax\DataController@getPilots');
Route::get('/main/pilot/{id}/{days}', 'Ajax\DataController@getPilot');
Route::get('/main/average', 'Ajax\DataController@getAverage');
Route::get('/main/balance', 'Ajax\DataController@getBalance');
//Route::view('/main/invite', 'invite');
Route::post('/main/setComment/{id}/{comment}', 'Ajax\DataController@setComments');
Route::post('/main/setAlts/{id}/{main_id}', 'Ajax\DataController@setAlts');
Route::get('/main/dashboard/corpid/{corpid}/days/{days}/top/{top}', ['uses' =>'Ajax\DataController@getDashboard']);
Route::get('/main/balance/corpid/{corpid}', ['uses' =>'Ajax\DataController@getBalance']);
Route::get('/main/prime/corpid/{corpid}', ['uses' =>'Ajax\DataController@getPrime']);
Route::get('/main/top', 'Ajax\DataController@getTopKills');

Route::get('/result', 'ResultController@index')->name('result');
Route::get('/chars', 'ResultController@chars')->name('chars');
Route::get('/corporations', 'ResultController@corporations')->name('corporations');

Route::get('/tokens',['uses' =>'ResultController@getcorps'])->name('tokens');
Route::get('/getmember',['uses' =>'EveAuthController@run_gather'])->name('getmember');

//admin
Route::view('/admin', 'admin.index');

// oauth2
Route::post('/login',['uses' =>'EveAuthController@geturl'])->name('login');
Route::post('/logout','EveAuthController@logout')->name('logout');
Route::get('/getuser',['uses' =>'EveAuthController@getuser'])->name('getuser');
Route::get('/auth/callback',['uses' =>'EveAuthController@callback'])->name('callback');

Route::get('/login2/{corpid}',['uses' =>'EveAuthController@geturl2'])->name('login2');
Route::get('/auth/callback2',['uses' =>'EveAuthController@callback2'])->name('callback2');

Route::get('/login3/{ret}',['uses' =>'EveAuthController@geturl3'])->name('login3');
Route::get('/auth/callback3',['uses' =>'EveAuthController@callback3'])->name('callback3');
//php

Route::group([
    'prefix' => 'user_has_roles',
], function () {
    Route::get('/', 'UserHasRolesController@index')
         ->name('user_has_roles.user_has_roles.index');
    Route::get('/create','UserHasRolesController@create')
         ->name('user_has_roles.user_has_roles.create');
    Route::get('/show/{userHasRoles}/{Roles}','UserHasRolesController@show')
         ->name('user_has_roles.user_has_roles.show')->where('id', '[0-9]+');
    Route::get('/edit/{userHasRoles}/{Roles}','UserHasRolesController@edit')
         ->name('user_has_roles.user_has_roles.edit')->where('id', '[0-9]+');
    Route::post('/', 'UserHasRolesController@store')
         ->name('user_has_roles.user_has_roles.store');
    Route::put('user_has_roles/{userHasRoles}/{Roles}', 'UserHasRolesController@update')
         ->name('user_has_roles.user_has_roles.update')->where('id', '[0-9]+');
    Route::delete('/user_has_roles','UserHasRolesController@destroy')
         ->name('user_has_roles.user_has_roles.destroy')->where('id', '[0-9]+');
});

Route::group([
    'prefix' => 'roles',
], function () {
    Route::get('/', 'RolesController@index')
         ->name('roles.roles.index');
    Route::get('/create','RolesController@create')
         ->name('roles.roles.create');
    Route::get('/show/{roles}','RolesController@show')
         ->name('roles.roles.show')->where('id', '[0-9]+');
    Route::get('/{roles}/edit','RolesController@edit')
         ->name('roles.roles.edit')->where('id', '[0-9]+');
    Route::post('/', 'RolesController@store')
         ->name('roles.roles.store');
    Route::put('roles/{roles}', 'RolesController@update')
         ->name('roles.roles.update')->where('id', '[0-9]+');
    Route::delete('/roles/{roles}','RolesController@destroy')
         ->name('roles.roles.destroy')->where('id', '[0-9]+');
});

Route::group([
    'prefix' => 'role_has_permissions',
], function () {
    Route::get('/', 'RoleHasPermissionsController@index')
         ->name('role_has_permissions.role_has_permissions.index');
    Route::get('/create','RoleHasPermissionsController@create')
         ->name('role_has_permissions.role_has_permissions.create');
    Route::get('/show/{roleHasPermissions}','RoleHasPermissionsController@show')
         ->name('role_has_permissions.role_has_permissions.show')->where('id', '[0-9]+');
    Route::get('/{roleHasPermissions}/edit','RoleHasPermissionsController@edit')
         ->name('role_has_permissions.role_has_permissions.edit')->where('id', '[0-9]+');
    Route::post('/', 'RoleHasPermissionsController@store')
         ->name('role_has_permissions.role_has_permissions.store');
    Route::put('role_has_permissions/{roleHasPermissions}', 'RoleHasPermissionsController@update')
         ->name('role_has_permissions.role_has_permissions.update')->where('id', '[0-9]+');
    Route::delete('/role_has_permissions/{roleHasPermissions}','RoleHasPermissionsController@destroy')
         ->name('role_has_permissions.role_has_permissions.destroy')->where('id', '[0-9]+');
});

Route::group([
    'prefix' => 'permissions',
], function () {
    Route::get('/', 'PermissionsController@index')
         ->name('permissions.permissions.index');
    Route::get('/create','PermissionsController@create')
         ->name('permissions.permissions.create');
    Route::get('/show/{permissions}','PermissionsController@show')
         ->name('permissions.permissions.show')->where('id', '[0-9]+');
    Route::get('/{permissions}/edit','PermissionsController@edit')
         ->name('permissions.permissions.edit')->where('id', '[0-9]+');
    Route::post('/', 'PermissionsController@store')
         ->name('permissions.permissions.store');
    Route::put('permissions/{permissions}', 'PermissionsController@update')
         ->name('permissions.permissions.update')->where('id', '[0-9]+');
    Route::delete('/permissions/{permissions}','PermissionsController@destroy')
         ->name('permissions.permissions.destroy')->where('id', '[0-9]+');
});

Route::group([
    'prefix' => 'alts',
], function () {
    Route::get('/', 'AltsController@index')
         ->name('alts.alts.index');
    Route::get('/create','AltsController@create')
         ->name('alts.alts.create');
    Route::get('/show/{alts}','AltsController@show')
         ->name('alts.alts.show')->where('id', '[0-9]+');
    Route::get('/{alts}/edit','AltsController@edit')
         ->name('alts.alts.edit')->where('id', '[0-9]+');
    Route::post('/', 'AltsController@store')
         ->name('alts.alts.store');
    Route::put('alts/{alts}', 'AltsController@update')
         ->name('alts.alts.update')->where('id', '[0-9]+');
    Route::delete('/alts/{alts}','AltsController@destroy')
         ->name('alts.alts.destroy')->where('id', '[0-9]+');
});

Route::group([
    'prefix' => 'characters',
], function () {
    Route::get('/', 'CharactersController@index')
         ->name('characters.characters.index');
    Route::get('/create','CharactersController@create')
         ->name('characters.characters.create');
    Route::get('/show/{characters}','CharactersController@show')
         ->name('characters.characters.show')->where('id', '[0-9]+');
    Route::get('/{characters}/edit','CharactersController@edit')
         ->name('characters.characters.edit')->where('id', '[0-9]+');
    Route::post('/', 'CharactersController@store')
         ->name('characters.characters.store');
    Route::put('characters/{characters}', 'CharactersController@update')
         ->name('characters.characters.update')->where('id', '[0-9]+');
    Route::delete('/characters/{characters}','CharactersController@destroy')
         ->name('characters.characters.destroy')->where('id', '[0-9]+');
});

Route::group([
    'prefix' => 'corporations',
], function () {
    Route::get('/', 'CorporationsController@index')
         ->name('corporations.corporations.index');
    Route::get('/create','CorporationsController@create')
         ->name('corporations.corporations.create');
    Route::get('/show/{corporations}','CorporationsController@show')
         ->name('corporations.corporations.show')->where('id', '[0-9]+');
    Route::get('/{corporations}/edit','CorporationsController@edit')
         ->name('corporations.corporations.edit')->where('id', '[0-9]+');
    Route::post('/', 'CorporationsController@store')
         ->name('corporations.corporations.store');
    Route::put('corporations/{corporations}', 'CorporationsController@update')
         ->name('corporations.corporations.update')->where('id', '[0-9]+');
    Route::delete('/corporations/{corporations}','CorporationsController@destroy')
         ->name('corporations.corporations.destroy')->where('id', '[0-9]+');
});


Route::group([
    'prefix' => 'invite',
], function () {
    Route::get('/', 'InviteController@index')
         ->name('invite.invite.index');
    Route::get('/list', 'InviteController@list')
         ->name('invite.invite.list');
    Route::get('/check', 'InviteController@check')
         ->name('invite.invite.check');
    Route::post('/send', 'InviteController@send')
         ->name('invite.invite.send');
    Route::post('/', 'InviteController@store')
         ->name('invite.invite.post');
});
Route::group([
    'prefix' => 'notifeve',
], function () {
    Route::get('/', 'NotifEveController@index')
         ->name('notificaton_eves.notificaton_eves.index');
    Route::get('/create','NotifEveController@create')
         ->name('notificaton_eves.notificaton_eves.create');
    Route::get('/show/{notificatonEves}','NotifEveController@show')
         ->name('notificaton_eves.notificaton_eves.show')->where('id', '[0-9]+');
    Route::get('/{notificatonEves}/edit','NotifEveController@edit')
         ->name('notificaton_eves.notificaton_eves.edit')->where('id', '[0-9]+');
    Route::post('/', 'NotifEveController@store')
         ->name('notificaton_eves.notificaton_eves.store');
    Route::put('notificaton_eves/{notificatonEves}', 'NotifEveController@update')
         ->name('notificaton_eves.notificaton_eves.update')->where('id', '[0-9]+');
    Route::delete('/notificaton_eves/{notificatonEves}','NotifEveController@destroy')
         ->name('notificaton_eves.notificaton_eves.destroy')->where('id', '[0-9]+');
});
