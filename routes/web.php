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

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@firstStepAuth');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/chat/messages', 'ChatController@getMessages');
Route::post('/chat/send', 'ChatController@send');
Route::get('/chat/chats', 'ChatController@getInvoiceChats');

Route::get('/login-step-2', 'Auth\LoginController@showSecondStep');
Route::post('/login-step-2', 'Auth\LoginController@confirmPin')->name('login-confirm');
Route::get('/resend-pin', 'Auth\LoginController@showSecondStep');

Route::group(['as'=>'user', 'middleware' => 'auth'], function() {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/invoices/list', 'InvoiceController@invoiceList')->name('invoices');
    Route::get('/invoices/add', 'InvoiceController@invoiceAdd');
    Route::get('/chat/base', 'InvoiceController@chatBase');

});

Route::group(['as'=>'admin', 'middleware' => 'admin'], function() {
    Route::get('/dashboard', 'HomeController@admin')->name('dashboard');
    Route::get('/users/list', 'AdminController@userList');
    Route::get('/currency/list', 'AdminController@currencyList');
    Route::get('/settings/list', 'AdminController@settingsList');
});

