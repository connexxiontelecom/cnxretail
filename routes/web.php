<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/','HomeController@index');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Auth::routes();
Auth::routes(['register' => false]);
#Dashboard routes
Route::get('/dashboard', [App\Http\Controllers\Backend\DashboardController::class, 'dashboard'])->name('dashboard');

#Contact routes
Route::get('/add-new-contact', 'Backend\ContactController@showAddNewContactForm')->name('add-new-contact');
Route::post('/add-new-contact', 'Backend\ContactController@storeNewContact');
Route::get('/all-contacts', 'Backend\ContactController@allContacts')->name('all-contacts');
Route::get('/view-contact/{slug}', 'Backend\ContactController@viewContact')->name('view-contact');
Route::post('/contact/conversation', 'Backend\ContactController@storeConversation');
Route::get('/contact/convert-to-lead/{slug}', 'Backend\ContactController@convertToLead')->name('convert-to-lead');
Route::post('/contact/invoice', 'Backend\ContactController@storeInvoice');
Route::get('/invoices', 'Backend\ContactController@invoices')->name('invoices');
Route::post('/contact/prospecting', 'Backend\ContactController@prospecting');
Route::post('/get-contact', 'Backend\ContactController@getContact');

#Users routes
Route::get('/users', 'Backend\UserController@allUsers')->name('users');
Route::get('/add-new-user', 'Backend\UserController@showAddUserForm')->name('show-user-form');
Route::post('/add-new-user', 'Backend\UserController@storeNewUser');
Route::get('/my-profile', 'Backend\UserController@myProfile')->name('my-profile');
Route::get('/edit-profile', 'Backend\UserController@editProfile')->name('edit-profile');
Route::post('/edit-profile', 'Backend\UserController@saveProfileChanges');
Route::post('/upload/avatar', 'Backend\UserController@uploadAvatar');
#Role routes
Route::get('/roles', 'Backend\UserController@roles')->name('roles');
Route::post('/add-new-role', 'Backend\UserController@storeRole');
Route::get('/role/{id}/assign-permissions', 'Backend\UserController@assignPermissionToRole')->name('assign-permissions');
Route::post('/assign-role-permissions', 'Backend\UserController@assignRolePermissions')->name('assign-role-permissions');
# Permission routes
Route::get('/permissions', 'Backend\UserController@permissions')->name('permissions');
Route::post('/add-new-permission', 'Backend\UserController@storePermission');
#Activity log
Route::get('/activity-log', 'Backend\UserController@activityLog')->name('activity-log');

#Service route
Route::get('/services', 'Backend\ServiceController@services')->name('services');
Route::post('/add-new-service', 'Backend\ServiceController@addNewService');

#Sales-invoice route
Route::get('/view-invoice/{slug}', 'Backend\SalesInvoiceController@viewInvoice')->name('view-invoice');
Route::get('/invoice/payment/history/{slug}', 'Backend\SalesInvoiceController@invoicePaymentHistory')->name('invoice-payment-history');
Route::get('/decline-invoice/{slug}', 'Backend\SalesInvoiceController@declineInvoice')->name('decline-invoice');
Route::get('/receive-payment/{slug}', 'Backend\SalesInvoiceController@receivePayment')->name('receive-payment');
Route::post('/invoice/receive-payment', 'Backend\SalesInvoiceController@storeNewReceipt');
Route::get('/receipts', 'Backend\SalesInvoiceController@receipts')->name('receipts');
Route::get('/view-receipt/{slug}', 'Backend\SalesInvoiceController@viewReceipt')->name('view-receipt');
Route::get('/new-invoice', 'Backend\SalesInvoiceController@newInvoice')->name('new-invoice');
Route::post('/send-invoice/mail', 'Backend\SalesInvoiceController@sendInvoiceAsEmail');

#Report routes
Route::get('/sales-report', 'Backend\ReportController@salesReport')->name('sales-report');
Route::post('/filter-sales-report', 'Backend\ReportController@filterSalesReport')->name('filter-sales-report');
Route::get('/payment-report', 'Backend\ReportController@paymentReport')->name('payment-report');
Route::post('/filter-payment-report', 'Backend\ReportController@filterPaymentReport')->name('filter-payment-report');
Route::get('/customer-sales-report-statement', 'Backend\ReportController@customerSalesReportStatement')
->name('customer-sales-report-statement');
Route::post('/customer-sales-report-statement', 'Backend\ReportController@filterCustomerSalesReportStatement');
#Imprest report route
Route::post('/filter-imprest-report', 'Backend\ReportController@filterImprestReport')->name('filter-imprest-report');
Route::get('/imprest-report', 'Backend\ReportController@imprestReport')->name('imprest-report');
#Quotation routes
Route::get('/quotations', 'Backend\QuotationController@quotations')->name('quotations');
Route::get('/quotation/add-new-quotation', 'Backend\QuotationController@newQuotation')->name('add-new-quotation');
Route::post('/quotation/store', 'Backend\QuotationController@storeQuotation');
Route::get('/view-quotation/{slug}', 'Backend\QuotationController@viewQuotation')->name('view-quotation');
#Bill & payment routes
Route::get('/bills', 'Backend\BillPaymentController@bills')->name('bills');
Route::get('/new-bill', 'Backend\BillPaymentController@newBill')->name('new-bill');
Route::post('/contact/bill', 'Backend\BillPaymentController@storeBill');
Route::get('/payments', 'Backend\BillPaymentController@payments')->name('vendor-payments');
Route::get('/make-payment', 'Backend\BillPaymentController@makePayment')->name('make-payment');
Route::get('/view-payment/{slug}', 'Backend\BillPaymentController@viewPayment')->name('view-payment');
Route::post('/make-payment', 'Backend\BillPaymentController@postMakePayment');
Route::post('/get-vendor', 'Backend\BillPaymentController@getVendor');
Route::post('/tenant/bank', 'Backend\BillPaymentController@addNewBank');
Route::get('/view-bill/{slug}', 'Backend\BillPaymentController@viewBill')->name('view-bill');
Route::get('/decline-bill/{slug}', 'Backend\BillPaymentController@declineBill')->name('decline-bill');


#Leads route
Route::get('/leads', 'Backend\LeadController@index')->name('leads');
Route::get('/view-lead/{slug}', 'Backend\LeadController@viewLead')->name('view-lead');
Route::post('/lead/score', 'Backend\LeadController@scoreLead');
Route::post('/lead/assign', 'Backend\LeadController@assignLead');
#Deals route
Route::get('/deals', 'Backend\DealController@index')->name('deals');
#Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

#Email & SMS routes
Route::get('/mailbox', 'Backend\EmailSMSController@mailbox')->name('mailbox');
Route::get('/mailbox/{slug}', 'Backend\EmailSMSController@readMail')->name('read-mail');
Route::get('/mail/compose', 'Backend\EmailSMSController@composeEmail')->name('compose-email');
Route::post('/compose/email', 'Backend\EmailSMSController@storeEmail');
Route::get('/email-templates', 'Backend\EmailSMSController@emailTemplates')->name('email-templates');
Route::post('/add-new-email-template', 'Backend\EmailSMSController@addNewEmailTemplate');
#Bulk sms
Route::get('/bulksms', 'Backend\EmailSMSController@bulksms')->name('bulksms');
Route::get('/compose-sms', 'Backend\EmailSMSController@composeSms')->name('compose-sms');
Route::post('/compose-sms', 'Backend\EmailSMSController@storeSMS');
Route::get('/sms/{slug}', 'Backend\EmailSMSController@readSMS')->name('read-sms');
Route::get('/phonegroup', 'Backend\EmailSMSController@phonegroup')->name('phonegroup');
Route::post('/phonegroup', 'Backend\EmailSMSController@storeNewPhoneGroup');
Route::get('/update/phonegroup/{slug}', 'Backend\EmailSMSController@updatePhoneGroup')->name('update-phonegroup');
Route::post('/update-phonegroup', 'Backend\EmailSMSController@editPhoneGroup');
Route::get('/bulksms-balance', 'Backend\EmailSMSController@bulksmsBalance')->name('bulksms-balance');
Route::get('/bulksms/buy-units', 'Backend\EmailSMSController@buyUnits')->name('buy-units');
Route::post('/bulksms/transaction', 'Backend\EmailSMSController@buyUnitsTransaction');
//Route::post('/sms/balance', 'Backend\BBNSMSCallsController@getBalance');
Route::post('/sms/balance', 'Backend\BBNSMSCallsController@sendMessage');
Route::post('/send-sms', 'Backend\EmailSMSController@sendSMS')->name('send-sms');

#Imprest routes
Route::get('/my-imprest', 'Backend\ImprestController@myImprest')->name('my-imprest');
Route::post('/post-imprest', 'Backend\ImprestController@postImprest')->name('post-imprest');
Route::post('/approve-imprest', 'Backend\ImprestController@approveImprest');
Route::get('/all-imprest', 'Backend\ImprestController@allImprest')->name('all-imprest');
#Reminder routes
Route::get('/reminders', 'Backend\ExtraController@reminders')->name('reminders');
Route::post('/new-reminder', 'Backend\ExtraController@storeReminder');
Route::get('/reminder-listview', 'Backend\ExtraController@reminderListview')->name('reminder-listview');


#Appointment route
Route::get('/appointments', 'Backend\AppointmentController@appointments')->name('appointments');
Route::get('/appointment/new', 'Backend\AppointmentController@addNewAppointment')->name('new-appointment');

#CNXDrive routes
Route::get('/cnxdrive', 'Backend\CNXDriveController@index')->name('cnxdrive');
Route::post('/cnxdrive/create-folder', 'Backend\CNXDriveController@createFolder');
Route::get('/open-folder/{slug}', 'Backend\CNXDriveController@openFolder')->name('open-folder');
Route::post('/cnxdrive/upload-file', 'Backend\CNXDriveController@uploadFile');
Route::get('/download/{slug}', 'Backend\CNXDriveController@downloadFile')->name('download-file');

#Settings route
Route::get('/general-settings', 'Backend\GeneralSettingsController@generalSettings')->name('general-settings');
Route::post('/tenant/general-settings', 'Backend\GeneralSettingsController@storeGeneralSettings');
Route::get('/email-settings', 'Backend\GeneralSettingsController@emailSettings')->name('email-settings');
