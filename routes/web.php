<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\GstController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\CommisionController;
use App\Http\Controllers\Admin\AdminDoctorPayoutController;

use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\DoctorScheduleController;
use App\Http\Controllers\Seller\PrescriptionController;
use App\Http\Controllers\Seller\SellerWalletController;
use App\Http\Controllers\Seller\DoctorPayoutController;

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\PrescriptionController as UserPrescription;
use App\Http\Controllers\User\UserWalletController;


Route::post('/gst/fetch', [GstController::class, 'getGstDetails']);
// login start 
Route::get('/admin', function () { return view('admin.ad.login'); })->name('admin.login');
Route::get('/doctor', function () {  return view('seller.ad.login'); })->name('seller.login');

Route::post('/admin/check', [AuthController::class, 'admin_check'])->name('admin.check');
Route::post('/seller/check', [AuthController::class, 'seller_check'])->name('seller.check');
// login end 

// singup route start
Route::get('/demo/singup', function () { return view('admin.ad.reg2'); });
 
Route::get('/admin/singup', function () {  return view('admin.ad.reg1'); });
Route::post('/admin/register', [AuthController::class, 'admin_register'])->name('admin.register');
Route::get('/admin/logout', [AuthController::class, 'admin_logout'])->name('admin.logout');

Route::get('/doctor/singup/{level?}', function ($level = 1) { return view('seller.ad.reg1', compact('level')); })->name('seller.singup');
Route::post('/doctor/register/{level?}', [AuthController::class, 'seller_register'])->name('seller.register');
Route::get('/doctor/verify-otp', [AuthController::class, 'otpForm'])->name('seller.otp.verify.form');
Route::post('/doctor/verify-otp', [AuthController::class, 'verifyOtp'])->name('seller.otp.verify');
Route::get('/doctor/logout', [AuthController::class, 'seller_logout'])->name('seller.logout');
// end singup route 

Route::post('/user/notification/read/{id}', [NotificationController::class, 'userRead'])->name('user.read');
Route::post('/doctor/notification/read/{id}', [NotificationController::class, 'doctorRead'])->name('doctor.read');
Route::post('/admin/notification/read/{id}', [NotificationController::class, 'adminRead'])->name('admin.read');
Route::post('/all/notification/read', [NotificationController::class, 'allRead'])->name('notifications.readAll');
Route::get('/notification/doctor', [NotificationController::class, 'doctor'])->name('notification.doctor');
Route::get('/notification/user', [NotificationController::class, 'user'])->name('notification.user');
Route::get('/notification/admin', [NotificationController::class, 'admin'])->name('notification.admin');

// admin Routes
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    
    Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
    Route::get('/auditlogging', [HomeController::class, 'AuditLogController'])->name('admin.auditlogging');

    Route::get('/account-settings', [HomeController::class, 'accountSettings'])->name('ad.account.settings');
    Route::post('/account-settings', [HomeController::class, 'updateAccountSettings'])->name('ad.account.update');
    
    Route::get('/user', [HomeController::class, 'userlist'])->name('user.list');
    Route::get('/user/{id}/edit', [HomeController::class, 'user_edit'])->name('user.edit');
    Route::put('/user/{id}', [HomeController::class, 'user_update'])->name('users.update');

    Route::get('/serviceprovider', [HomeController::class, 'sellerlist'])->name('seller.list');
    Route::get('/serviceprovider/{id}/edit', [HomeController::class, 'seller_edit'])->name('seller.edit');
    Route::put('/serviceprovider/{id}', [HomeController::class, 'seller_update'])->name('seller.update');

    Route::get('/drop', [HomeController::class, 'droplist'])->name('drop.list');
    Route::get('/drop/{id}/edit', [HomeController::class, 'drop_edit'])->name('drop.edit');
    Route::put('/drop/{id}', [HomeController::class, 'drop_update'])->name('drop.update');

    Route::post('/user/{id}/update-status', [HomeController::class, 'userupdateStatus']);
    Route::post('/seller/{id}/update-status', [HomeController::class, 'sellerupdateStatus']);
    Route::post('/drop/{id}/update-status', [HomeController::class, 'dropupdateStatus']);

    Route::get('/subscription-plans', [SubscriptionPlanController::class, 'index'])->name('admin.subscription.plans');
    Route::get('/subscription-plans/create', [SubscriptionPlanController::class, 'create'])->name('admin.subscription.plans.create');
    Route::post('/subscription-plans/store', [SubscriptionPlanController::class, 'store'])->name('admin.subscription.plans.store');
    Route::get('/subscription-plans/{id}/edit', [SubscriptionPlanController::class, 'edit'])->name('admin.subscription.plans.edit');
    Route::post('/subscription-plans/{id}/update', [SubscriptionPlanController::class, 'update'])->name('admin.subscription.plans.update');

    Route::get('/commission-plans', [CommisionController::class, 'index'])->name('admin.commission.plans');
    Route::get('/commission-plans/create', [CommisionController::class, 'create'])->name('admin.commission.plans.create');
    Route::post('/commission-plans/store', [CommisionController::class, 'store'])->name('admin.commission.plans.store');
    Route::get('/commission-plans/{id}/edit', [CommisionController::class, 'edit'])->name('admin.commission.plans.edit');
    Route::post('/commission-plans/{id}/update', [CommisionController::class, 'update'])->name('admin.commission.plans.update');

    Route::get('/appointments', [HomeController::class, 'appointments'])->name('admin.appointments');
    Route::post('/appointments/{id}/status', [HomeController::class, 'updateStatus'])->name('admin.availability.status');

    Route::get('/prescriptions', [HomeController::class, 'prescription'])->name('admin.prescriptions');
    Route::get('/prescription/{id}/pdf', [HomeController::class, 'prescription_pdf'])->name('admin.prescription.pdf');

    Route::get('/doctor-payouts', [AdminDoctorPayoutController::class, 'index'])->name('admin.doctor.payouts');
    Route::get('/payouts/history', [AdminDoctorPayoutController::class, 'payout_history'])->name('admin.payout_history');
    Route::post('/doctor-payouts/{id}/approve', [AdminDoctorPayoutController::class, 'approve'])->name('admin.doctor.payout.approve');
    Route::post('/doctor-payouts/{id}/reject', [AdminDoctorPayoutController::class, 'reject'])->name('admin.doctor.payout.reject');


});

// doctor Routes
Route::middleware(['auth:seller'])->prefix('doctor')->group(function () {
    
    Route::get('/dashboard', [SellerController::class, 'index'])->name('seller.dashboard');
    Route::get('/account-settings', [SellerController::class, 'accountSettings'])->name('seller.account.settings');
    Route::post('/account-settings', [SellerController::class, 'updateAccountSettings'])->name('seller.account.update');

    Route::get('/wallet', [SellerWalletController::class, 'index'])->name('doctor.wallet');
    Route::get('/wallet/transactions', [SellerWalletController::class, 'transactions'])->name('doctor.wallet.transactions');
    Route::post('/wallet/add-money', [SellerWalletController::class, 'addMoney'])->name('doctor.wallet.add');

    Route::get('/availability', [DoctorScheduleController::class, 'index'])->name('doctor.availability.index');
    Route::get('/add-availability', [DoctorScheduleController::class, 'add_availability'])->name('doctor.add.availability');
    Route::post('/availability', [DoctorScheduleController::class, 'store'])->name('doctor.availability.store');
    Route::post('/availability/{id}/status', [DoctorScheduleController::class, 'updateAvailabilityStatus'])->name('doctor.availability.status');
    Route::get('/appointments', [DoctorScheduleController::class, 'appointments'])->name('doctor.appointments');
    Route::post('/appointments/{id}/status', [DoctorScheduleController::class, 'updateStatus'])->name('doctor.appointments.status');

    Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('doctor.prescriptions');
    Route::get('/prescription/create/{appointment}', [PrescriptionController::class, 'create'])->name('doctor.prescription.create');
    Route::post('/prescription/store', [PrescriptionController::class, 'store'])->name('doctor.prescription.store');
    Route::get('/prescription/{id}/pdf', [PrescriptionController::class, 'pdf'])->name('doctor.prescription.pdf');
    Route::post('/prescription/{id}/status', [PrescriptionController::class, 'updateAvailabilityStatus'])->name('doctor.prescription.status');

    Route::get('/payouts', [DoctorPayoutController::class, 'index'])->name('doctor.payouts');
    Route::get('/payouts/history', [DoctorPayoutController::class, 'payout_history'])->name('doctor.payout_history');
    Route::post('/payout-request', [DoctorPayoutController::class, 'request'])->name('doctor.payout.request');

});

// user patient
Route::middleware(['web', 'auth:web'])->prefix('user')->group(function () {
    
    Route::get('/logout/user', [AuthController::class, 'user_logout'])->name('user.logout');
    
    Route::get('/dashboard-overview', [UserController::class, 'dashboard_overview'])->name('user.desh');
    Route::get('/account-settings', [UserController::class, 'accountSettings'])->name('user.account.settings');
    Route::post('/account-settings', [UserController::class, 'updateAccountSettings'])->name('user.account.update');

    Route::get('/wallet', [UserWalletController::class, 'index'])->name('user.wallet');
    Route::get('/wallet/transactions', [UserWalletController::class, 'transactions'])->name('user.wallet.transactions');
    Route::post('/wallet/add-money', [UserWalletController::class, 'addMoney'])->name('user.wallet.add');

    Route::get('/doctors', [BookingController::class, 'doctorList'])->name('user.doctors');
    Route::get('/doctor/{id}/book', [BookingController::class, 'bookForm'])->name('user.book.form');
    Route::post('/doctor/book', [BookingController::class, 'store'])->name('user.book.store');    
    Route::get('/appointments', [BookingController::class, 'myAppointments'])->name('user.appointments');

    Route::get('/prescriptions', [UserPrescription::class, 'index'])->name('user.prescriptions');
    Route::get('/prescription/{id}/pdf', [UserPrescription::class, 'pdf'])->name('user.prescription.pdf');

});


Route::get('/user/singup', function () { return view('front.sign_up'); })->name('user.singup');
Route::post('/register/user', [UserController::class, 'register'])->name('user.register');

Route::get('/verify-otp', [UserController::class, 'otpForm'])->name('otp.verify.form');
Route::post('/verify-otp', [UserController::class, 'verifyOtp'])->name('otp.verify');
    
Route::get('/password/forget', function () { return view('front.forgot_password'); })->name('user.forget');
Route::get('/user', function () { return view('front.sign_in'); })->name('user.login');
Route::post('/user/check', [AuthController::class, 'user_check'])->name('user.check');

Route::get('/', [UserController::class, 'index'])->name('home');
