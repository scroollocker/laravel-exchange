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

Route::get('/login-step-2', 'Auth\LoginController@showSecondStep');
Route::post('/login-step-2', 'Auth\LoginController@confirmPin')->name('login-confirm');
Route::get('/resend-pin', 'Auth\LoginController@showSecondStep');

Route::get('/test', 'Dashboard@getPayments');

Route::group(['as'=>'user', 'middleware' => 'auth'], function() {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/chat/base', 'InvoiceController@chatBase');

    Route::get('/chat/messages', 'ChatController@getMessages');
    Route::post('/chat/send', 'ChatController@send');
    Route::get('/chat/chats', 'ChatController@getInvoiceChats');

    Route::get('/user/settings', 'UserSettingsController@showUserSettingsView');
    Route::get('/user/settings/get', 'UserSettingsController@getUserSettings');
    Route::post('/user/settings/save', 'UserSettingsController@saveUserSettings');

    Route::get('/user/accounts', 'UserSettingsController@accountsView');
    Route::get('/user/accounts/get', 'UserSettingsController@getUserAccounts');
    Route::post('/user/accounts/state', 'UserSettingsController@enableUserAccount');

    Route::get('user/partners',  'UserSettingsController@partnersView');
    Route::get('user/partners/get', 'UserSettingsController@getPartners');
    Route::post('user/partners/state', 'UserSettingsController@setPartnersState');
    Route::post('user/partners/remove', 'UserSettingsController@removePartner');
    Route::post('user/partners/userlist', 'UserSettingsController@getUserList');
    Route::post('user/partners/add', 'UserSettingsController@savePartner');

    Route::prefix('invoices')->group(function () {
        Route::get('list', 'InvoiceController@invoiceList')->name('invoices');
        Route::get('add', 'InvoiceController@invoiceAdd');
        Route::get('getCurrences', 'InvoiceController@getCurrences');
        Route::get('getPartners', 'InvoiceController@getPartners');
        Route::get('getInvoices', 'InvoiceController@getInvoiceList');
        Route::post('getAccounts', 'InvoiceController@getAccounts');
        Route::post('getInvoiceById', 'InvoiceController@getInvoiceById');
        Route::post('save', 'InvoiceController@saveInvoice');
        Route::post('remove', 'InvoiceController@closeDeclare');
        Route::get('offers', 'InvoiceController@getOffersInvoice');
        Route::get('offersDetail', 'InvoiceController@getOffersDetail');
        Route::post('getOfferById', 'InvoiceController@getOfferDetail');
        Route::post('getOffers', 'InvoiceController@getOffersByInvoice');

        Route::post('agreeOffer', 'InvoiceController@agreeOffer');
        Route::post('disagreeOffer', 'InvoiceController@disagreeOffer');

        Route::get('inBank', 'InvoiceController@getInBankView');
        Route::post('getState', 'InvoiceController@getInvoiceState');

        Route::get('bankPayments', 'InvoiceController@getPaymentsView');
        Route::post('getPayments', 'InvoiceController@getPayments');

    });

    Route::prefix('dashboard')->group(function() {
        Route::get('invoices-list', 'Dashboard@templateInvoiceList');
        Route::get('offer-add', 'Dashboard@templateEditOffer');

        Route::get('currencies-get', 'Dashboard@getCurrencies');

        Route::post('invoices-list-get', 'Dashboard@getAvailibleInvoices');
        Route::post('getInvoice', 'Dashboard@getInvoice');

        Route::post('getAcc', 'Dashboard@getAcc');
        Route::post('createOffer', 'Dashboard@saveOffer');

        Route::get('getOffers', 'Dashboard@getOffers');
        Route::get('getOffers-template', 'Dashboard@getOffersTemplate');


    });

});

Route::group(['as'=>'admin', 'middleware' => ['auth','admin']], function() {
    Route::get('/dashboard', 'HomeController@admin')->name('dashboard');

    Route::get('/users/list', 'AdminController@userList');
    Route::get('/users/get', 'AdminController@getUserList');
    Route::post('/user/add', 'AdminController@addUser');
    Route::post('/user/edit', 'AdminController@editUser');
    Route::post('/user/remove', 'AdminController@removeUser');
    Route::post('/user/reset', 'AdminController@resetPassword');
    Route::post('/user/block', 'AdminController@blockUser');

    Route::get('/currency/list', 'AdminController@currencyList');
    Route::get('/currency/get', 'AdminController@getCurrencyList');
    Route::post('/currency/add', 'AdminController@addNewCurrency');
    Route::post('/currency/edit', 'AdminController@editCurrency');
    Route::post('/currency/block', 'AdminController@blockCurrency');

    Route::get('/settings/list', 'AdminController@settingsList');
    Route::get('/settings/get', 'AdminController@getSeeting');
    Route::post('/settings/save', 'AdminController@saveSetting');
});

