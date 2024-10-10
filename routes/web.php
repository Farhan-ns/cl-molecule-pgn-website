<?php

use App\Http\Controllers\Admin\BlastController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\NametagController;
use App\Http\Controllers\Admin\QrController;
use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Controllers\Admin\RegistrationExportController;
use App\Http\Controllers\Admin\TwilioController;
use App\Services\PhoneNumberParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Models\Registration;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

Route::get('/test', function () {
    return response()->json(Registration::find(86));

    $r = [1,2,3,4,5];
    unset($r[0]);
    return $r;
    return response()->streamDownload(
        function () {
            echo QrCode::size(1000)
                ->format('png')
                ->errorCorrection('M')
                ->margin(1)
                ->generate('https://linktr.ee/perqara');
        },
        "perqara.png",
        ['Content-Type' => 'image/png'],
    );
    // $num = '+6282351609292';
    // return PhoneNumberParserService::parseToInternational($num);
    // return "Hola $num";

    // Get the current hour
    $currentHour = 19;
    // $currentHour = now()->setTimezone('Asia/Jakarta')->hour;

    // Determine the time text based on the current hour
    if ($currentHour >= 5 && $currentHour < 11) {
        $timeText = 'Pagi';
    } elseif ($currentHour >= 11 && $currentHour < 15) {
        $timeText = 'Siang';
    } elseif ($currentHour >= 15 && $currentHour < 19) {
        $timeText = 'Sore';
    } else {
        $timeText = 'Malam';
    }

    // Return the time text
    // return $timeText;
    // return view('user.nametag');
});

Route::get('/', [RegisterController::class, 'index']);
Route::get('/rsvp', [RegisterController::class, 'showRsvp'])->name('rsvp');
Route::post('/', [RegisterController::class, 'register'])->name('register');

Route::name('admin.')->prefix('admin')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('auth');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::resource('registration', RegistrationController::class)->only(['index', 'show', 'edit', 'update']);
        Route::get('/registration/delete/{registration}', [RegistrationController::class, 'destroy'])->name('registration.destroy');
        Route::get('/export/registration', [RegistrationController::class, 'export'])->name('registration.export');
        Route::put('/registration/{registration}/attendance', [RegistrationController::class, 'setAttendance'])->name('registration.set-attendance');

        Route::get('/qr/{registration}/download', [QrController::class, 'downloadQr'])->name('registration.downloadQr');
        // Route::get('/qr/{registration}/link', [QrController::class, 'previewQr'])->name('registration.previewQr');
        Route::get('/nametag/{registration}', NametagController::class)->name('registration.nametag');

        Route::resource('blast', BlastController::class)->only(['index', 'create', 'store']);

        // Route::post('/wa/{registration}', [TwilioController::class, 'sendMessage'])->name('twilio.sendMessage');
    });
});