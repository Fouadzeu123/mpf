<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\MemberAuthController;
use App\Http\Controllers\CommunionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MemberPortalController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\ScannerController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/membre/connexion')->name('home');
Route::redirect('/register', '/login');

Route::prefix('membre')->name('member.')->group(function () {
    Route::get('connexion', [MemberAuthController::class, 'create'])->name('login');
    Route::post('connexion', [MemberAuthController::class, 'store'])->name('login.store');
    Route::post('deconnexion', [MemberAuthController::class, 'destroy'])->name('logout');

    Route::middleware('member.auth')->group(function () {
        Route::get('espace', [MemberPortalController::class, 'index'])->name('portal');
        Route::get('paiement', [PaymentController::class, 'create'])->name('payment');
        Route::post('paiement', [PaymentController::class, 'initiate'])->name('payment.initiate');
        Route::get('flux', [\App\Http\Controllers\FeedController::class, 'index'])->name('feed');
        Route::post('evenements/{event}/contribuer', [PaymentController::class, 'initiateContribution'])->name('events.contribute');
    });
});

Route::get('paiements/callback', [PaymentController::class, 'callback'])->name('payments.callback');
Route::get('paiements/verifier/{reference}', [PaymentController::class, 'verify'])->name('payments.verify');

// Webhooks (not protected - verify signature instead)
Route::post('webhooks/notchpay', [WebhookController::class, 'notchpay'])->name('webhooks.notchpay');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::get('members', [MemberController::class, 'index'])->middleware('role:secretaire,ancienne')->name('members.index');
    Route::get('members/create', [MemberController::class, 'create'])->middleware('role:secretaire')->name('members.create');
    Route::post('members', [MemberController::class, 'store'])->middleware('role:secretaire')->name('members.store');
    Route::post('members/upload-photo', [MemberController::class, 'uploadPhoto'])->middleware('role:secretaire')->name('members.upload-photo');
    Route::post('members/reorder-codes', [MemberController::class, 'reorderCodes'])->middleware('role:secretaire')->name('members.reorder-codes');
    Route::get('members/{member}', [MemberController::class, 'show'])->middleware('role:secretaire,ancienne')->name('members.show');
    Route::get('members/{member}/edit', [MemberController::class, 'edit'])->middleware('role:secretaire')->name('members.edit');
    Route::put('members/{member}', [MemberController::class, 'update'])->middleware('role:secretaire')->name('members.update');
    Route::delete('members/{member}', [MemberController::class, 'destroy'])->middleware('role:secretaire')->name('members.destroy');
    Route::post('members/{member}/gps', [MemberController::class, 'updateGps'])->middleware('role:secretaire')->name('members.gps');
    Route::get('members/{member}/carte', [MemberController::class, 'card'])->middleware('role:secretaire')->name('members.card');

    Route::resource('visitors', VisitorController::class)->except(['edit', 'update', 'destroy'])->middleware('role:secretaire');
    Route::post('visitors/{visitor}/convert', [VisitorController::class, 'convert'])->middleware('role:secretaire')->name('visitors.convert');

    Route::get('scanner', [ScannerController::class, 'index'])->middleware('role:protocole,ancienne,secretaire')->name('scanner.index');
    Route::post('scanner/scan', [ScannerController::class, 'scan'])->middleware('role:protocole,ancienne,secretaire')->name('scanner.scan');

    Route::get('presences', [AttendanceController::class, 'index'])->middleware('role:admin')->name('attendances.index');
    Route::delete('presences/{attendance}', [AttendanceController::class, 'destroy'])->middleware('role:admin')->name('attendances.destroy');
    Route::get('sainte-cene', [CommunionController::class, 'index'])->middleware('role:admin')->name('communion.index');
    Route::post('sainte-cene/configurer', [CommunionController::class, 'updateSettings'])->middleware('role:admin')->name('communion.settings.update');

    Route::get('impression', [PrintController::class, 'index'])->middleware('role:secretaire')->name('print.index');
    Route::post('impression/membres', [PrintController::class, 'members'])->middleware('role:secretaire')->name('print.members');
    Route::post('impression/visiteurs', [PrintController::class, 'visitors'])->middleware('role:secretaire')->name('print.visitors');
    Route::get('impression/liste-membres', [PrintController::class, 'printMembersList'])->middleware('role:secretaire')->name('print.members-list');
    Route::get('impression/presences-mois', [PrintController::class, 'printMonthlyAttendances'])->middleware('role:secretaire')->name('print.monthly-attendances');
    Route::get('impression/communion-prepares', [PrintController::class, 'printCommunionPrepared'])->middleware('role:secretaire')->name('print.communion-prepared');

    Route::get('paiements', [PaymentController::class, 'index'])->middleware('role:admin')->name('payments.index');
    Route::get('flux', [\App\Http\Controllers\FeedController::class, 'index'])->name('admin.feed');
    Route::resource('admin-videos', \App\Http\Controllers\VideoController::class)->middleware('role:admin');
    Route::resource('admin-events', \App\Http\Controllers\EventController::class)->middleware('role:admin');
    Route::post('admin-events/{event}/contributions', [\App\Http\Controllers\EventController::class, 'storeContribution'])->middleware('role:admin')->name('admin-events.contributions.store');
});

require __DIR__.'/settings.php';
