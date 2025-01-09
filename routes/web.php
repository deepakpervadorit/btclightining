<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Merchant\DashboardController as MerchantDashboardController;
use App\Http\Controllers\Merchant\UserController as MerchantUserController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\ServiceProviderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SquareController;
use App\Http\Controllers\CheckbookController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\CheckController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotifyPaymentController;
use App\Http\Controllers\thankyouController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Deposit and Payment Form
    Route::get('/deposit/form', [PaymentController::class, 'showForm'])
        ->name('show.deposit.form');
    Route::post('/deposit/invoice', [PaymentController::class, 'showInvoice'])
    ->name('show.deposit.invoice');
        // Withdrawal Form
        Route::get('/withdrawal/form', [PaymentController::class, 'withdrawalForm'])
        ->name('show.withdrawal.form');
    Route::get('/payments', [PaymentController::class, 'payments'])
        ->name('show.payments');
    Route::post('/webhook', 'PaymentController@handleWebhook')
        ->name('webhook');
        Route::post('/withdrawal/{id}/update-status', [PaymentController::class, 'updateStatus'])->name('withdrawal.updateStatus');
        Route::put('/withdrawal/reject/{id}', [PaymentController::class, 'rejectStatus'])->name('withdrawal.rejectStatus');

        // Withdrawal Link
        Route::post('/withdrawal/link', [PaymentController::class, 'withdrawalLink'])
        ->name('withdrawal.link');
    Route::post('/deposit/link', [PaymentController::class, 'depositLink'])
        ->name('deposit.link');
    Route::post('/process-deposit/s/{id}', [PaymentController::class, 'processDeposit'])
        ->name('process.deposit');
    Route::get('/deposit/s/success', [PaymentController::class, 'depositStripeSuccess'])
        ->name('deposit.stripe.success');

    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
    Route::post('/process-deposit/sq/{id}', [PaymentController::class, 'squareProcessDeposit'])
        ->name('square.process.deposit');
        Route::post('/squareup_qr', [PaymentController::class, 'squareup_qr'])
        ->name('squareup_qr');
Route::post('/make-payment', [PaymentController::class, 'makePayment'])->name('process.checkbook');
Route::get('/payment-result', [PaymentController::class, 'paymentResult'])->name('payment.result');


Route::get('/unauthorized', function () {
    return view('unauthorized');
})->name('unauthorized');
// Route::group(['middleware' => ['staffAuth:Superadmin']], function () {
//     // Route::group(['middleware' => ['permission:create_user']], function () {

//     // // Roles CRUD
//     // Route::resource('roles', RolesController::class);

//     // // Permissions CRUD
//     // Route::resource('permissions', PermissionsController::class);

//     Route::post('/send-check', [CheckController::class, 'sendCheck'])->name('check.send');
//     Route::get('/send-check', [CheckController::class, 'showForm'])->name('check.form');
//     // });

// });

// Route::post('/process-deposit/sq/{id}', [PaymentController::class, 'squareProcessDeposit'])->name('square.process.deposit');
// Roles CRUD
// Route::resource('roles', RolesController::class);

// Permissions CRUD
Route::resource('permissions', PermissionsController::class);
Route::post('/generate-invoice-qr', [PaymentController::class, 'generateInvoiceQr']);
// Route::get('/deposit/form', [PaymentController::class, 'showForm'])->name('show.deposit.form');
// Route::get('/payments', [PaymentController::class, 'payments'])->name('show.payments');
// Route::post('/webhook', 'PaymentController@handleWebhook')->name('webhook');
// Route::post('/deposit/link', [PaymentController::class, 'depositLink'])->name('deposit.link');
// Route::post('/process-deposit/s/{id}', [PaymentController::class, 'processDeposit'])->name('process.deposit');
// Route::get('/deposit/s/success', [PaymentController::class, 'depositStripeSuccess'])->name('deposit.stripe.success');
    // NotifyController & Thank You
Route::post('/thankyou', [thankyouController::class, 'thankyou'])
     ->name('thankyou');
     Route::post('/notify-payment', [NotifyPaymentController::class, 'notify'])->name('notify');
Route::post('/payout-notify-payment', [NotifyPaymentController::class, 'PayoutNotify'])->name('notify.payout');

// Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
Route::get('/{merchantid}/register', [LoginController::class, 'showRegisterForm'])
        ->name('show.Register.form');
Route::post('/merchant/register', [LoginController::class, 'register'])
        ->name('merchant.register');

Auth::routes();


Route::middleware('guest')->group(function () {
    Route::name('admin.')->prefix('admin')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showAdminLoginForm'])->name('login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
    });
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});
Route::name('admin.')->prefix('admin')->group(function () {
    Route::get('/merchant/create', [StoreController::class, 'create'])->name('merchant.create');
    Route::post('/merchant/add', [StoreController::class, 'store'])->name('merchant.add');
    Route::get('/merchant/list', [StoreController::class, 'index'])->name('merchant.list');
    Route::get('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware(['staffAuth'])->group(function () {

    //Merchant Routes
    Route::name('merchant.')->prefix('merchant')->group(function () {
        Route::get('/user', [MerchantUserController::class, 'index'])->name('users.index');
        Route::get('/user/edit', [MerchantUserController::class, 'index'])->name('user.edit');
    });


    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('permission:Dashboard');
    Route::get('/merchant/dashboard', [MerchantDashboardController::class, 'index'])
        ->name('merchant.dashboard')
        ->middleware('permission:Dashboard');

    // Deposit
    Route::get('/deposit', [DepositController::class, 'index'])
        ->name('deposit')
        ->middleware('permission:Deposit');
    Route::delete('/deposits/{id}', [DepositController::class, 'destroy'])
        ->name('deposits.destroy')
        ->middleware('permission:Deposit');
        Route::post('/store', [DepositController::class, 'storeDepositAmount'])->name('deposit.store.amount');


    // Withdrawal
    Route::get('/withdrawal', [WithdrawalController::class, 'index'])
        ->name('withdrawal')
        ->middleware('permission:Withdrawal');
        Route::delete('/withdrawal/{id}', [WithdrawalController::class, 'destroy'])
        ->name('withdrawal.destroy')
        ->middleware('permission:Withdrawal');
    // Staff Management
    Route::resource('staff', StaffController::class)->middleware('permission:Staff');
    // User Management
    Route::resource('user', UserController::class)->middleware('permission:Users')->except(['show']);
    Route::put('/user/{id}/toggle-verify', [UserController::class, 'toggleVerify'])->name('user.toggleVerify');
    Route::get('/user/virtualcard', [UserController::class, 'virtualcard'])->name('user.virtualcard');
        Route::get('/user/virtualcard/callback', [UserController::class, 'virtualcard_callback'])->name('user.virtualcard.callback');
    Route::get('/user/checkbook_users', [UserController::class, 'checkbook_users'])->name('user.checkbookusers')->middleware('permission:checkbook_users');
    Route::get('/user/checkbook_usersbyid', [UserController::class, 'checkbook_usersbyid'])->name('user.checkbook_usersbyid')->middleware('permission:checkbook_usersbyid');
    // Disputes
    Route::get('/disputes', [DisputeController::class, 'index'])
        ->name('disputes')
        ->middleware('permission:Disputes');

    // Service Provider
    Route::get('/service_provider', [ServiceProviderController::class, 'index'])
        ->name('service_provider')
        ->middleware('permission:Service Provider');
    Route::post('/games/add', [ServiceProviderController::class, 'addGame'])
        ->name('addgame')
        ->middleware('permission:Service Provider');
    Route::put('/games/update', [ServiceProviderController::class, 'updateGame'])
        ->name('updategame')
        ->middleware('permission:Service Provider');
    Route::delete('/games/delete/{id}', [ServiceProviderController::class, 'deleteGame'])
        ->name('deletegame')
        ->middleware('permission:Service Provider');

    // Stripe Settings
    Route::get('/stripe-settings', [StripeController::class, 'index'])
        ->name('admin.stripe.keys')
        ->middleware('permission:Stripe Setting');
    Route::post('/stripe-keys/update', [StripeController::class, 'updateStripe'])
        ->name('admin.stripe.keys.update')
        ->middleware('permission:Stripe Setting');

    // Square Settings
    Route::get('/square-settings', [SquareController::class, 'index'])
        ->name('admin.square.keys')
        ->middleware('permission:Square Settings');
    Route::post('/square-keys/update', [SquareController::class, 'updateSquare'])
        ->name('admin.square.keys.update')
        ->middleware('permission:Square Settings');

    // Checkbook Settings
    Route::get('/checkbook-settings', [CheckbookController::class, 'index'])
        ->name('admin.checkbook.keys')
        ->middleware('permission:Checkbook Settings');
    Route::post('/checkbook-keys/update', [CheckbookController::class, 'updateCheckbook'])
        ->name('admin.checkbook.keys.update')
        ->middleware('permission:Checkbook Settings');



    // Roles CRUD
    Route::resource('roles', RolesController::class)->middleware('permission:Roles and Permissions');
    Route::group(['middleware' => ['staffAuth:Superadmin']], function () {

        Route::post('/send-check', [CheckController::class, 'sendCheck'])->name('check.send')->middleware('permission:Roles and Permissions');
        Route::get('/send-check', [CheckController::class, 'showForm'])->name('check.form')->middleware('roles:Roles and Permissions');



    });
});
