<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/all-reminders', 'Backend\API\ReminderController@allReminders');


Route::post('login', 'API\authController@login');
Route::post('register', 'API\authController@register');
Route::post('logout', 'API\authController@logout');
Route::post('refresh', 'API\authController@refresh');
Route::get('user-profile', 'API\authController@userProfile');
Route::get('IstokenValid', 'API\authController@isValidToken');

Route::group(['middleware' => ['jwt.verify'], 'prefix'=>'auth' ], function() {
    Route::get('user', 'API\authController@getAuthenticatedUser');
    Route::get('users', 'API\usersController@fetchAllUsers');
    Route::get('reminders', 'API\dashboardController@fetchReminders');


    Route::get('invoices', 'API\dashboardController@fetchInvoices');
    Route::get('invoicesdetails', 'API\dashboardController@fetchInvoiceDetails');

    Route::get('bills', 'API\dashboardController@fetchBills');
    Route::get('billsdetails', 'API\dashboardController@fetchBillsDetails');

    Route::get('payments', 'API\dashboardController@fetchPayments');
    Route::get('paymentsdetails', 'API\dashboardController@fetchPaymentsDetails');


    Route::get('receipts', 'API\dashboardController@fetchReceipts');
    Route::get('receiptsdetails', 'API\dashboardController@fetchReceiptsDetails');

    Route::get('services', 'API\dashboardController@fetchServices');

    Route::get('contacts', 'API\dashboardController@fetchContacts');

    Route::get('leads', 'API\dashboardController@fetchLeads');

    Route::get('deals', 'API\dashboardController@fetchDeals');

    Route::get('currencies', 'API\dashboardController@fetchCurrencies');

    Route::post('addcontact', 'API\contactController@addContact' );

    Route::post('createinvoice', 'API\invoiceController@createInvoice' );

    Route::post('createbill', 'API\billController@createBill');

    Route::post('createreceipt', 'API\receiptController@createReceipt');

    Route::post('createpayment', 'API\billsPaymentController@createPayment');







});
