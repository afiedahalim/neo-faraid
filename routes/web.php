<?php

// routes/web.php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\CalculationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\FaraidController;
use App\Http\Controllers\FaraidCaseController;
use App\Http\Controllers\InstantEstateController;
use App\Http\Controllers\EstateSetupController;
use App\Http\Controllers\Admin\EstateSetupController as AdminEstateSetupController;
use App\Http\Controllers\Admin\InstantEstateAdminController;
use App\Http\Controllers\Admin\InstantEstateNotificationController;
use App\Http\Controllers\Admin\InstantEstateApprovalController;
use App\Http\Controllers\InheritanceNotificationController;
use App\Http\Controllers\BeneficiaryAccessController;
use App\Models\Calculation;

/*
|--------------------------------------------------------------------------
| DEBUG ROUTES (AUTHENTICATED ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('debug')->group(function () {
    Route::get('/sessions', function () {
        $sessions = App\Models\InstantEstateSession::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['session_id', 'original_filename', 'status', 'created_at']);

        return response()->json($sessions);
    });

    Route::get('/ocr/{sessionId}', function ($sessionId) {
        $session = App\Models\InstantEstateSession::where('session_id', $sessionId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$session) {
            return response()->json(['error' => 'Session not found', 'session_id' => $sessionId], 404);
        }

        $ocrText = '';
        try {
            $filePath = $session->file_path;
            $txtPath = str_replace(['.jpg', '.jpeg', '.png', '.webp', '.pdf'], '.txt', $filePath);
            if (Storage::disk('private')->exists($txtPath . '.ocr.txt')) {
                $ocrText = Storage::disk('private')->get($txtPath . '.ocr.txt');
            }
        } catch (\Exception $e) {
            $ocrText = 'Error reading OCR text: ' . $e->getMessage();
        }

        return response()->json([
            'session_id' => $session->session_id,
            'original_filename' => $session->original_filename,
            'status' => $session->status,
            'extracted_data' => $session->extracted_data,
            'raw_ocr_text' => $ocrText,
            'file_path' => $session->file_path,
        ]);
    });

    Route::get('/instant-estate/{sessionId}', function ($sessionId) {
        $session = App\Models\InstantEstateSession::where('session_id', $sessionId)->first();
        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        return response()->json([
            'session_id' => $session->session_id,
            'status' => $session->status,
            'has_report_data' => !is_null($session->report_data),
            'has_report_pdf' => $session->has_pdf_report ?? false,
            'matched_record_type' => $session->matched_record_type,
            'matched_record_id' => $session->matched_record_id,
            'notification_requested' => $session->notification_requested,
            'report_pdf_path' => $session->report_pdf_path,
            'extracted_data' => $session->extracted_data,
        ]);
    });

    Route::get('/beneficiary-links', function () {
        $links = App\Models\BeneficiaryAccessLink::with('estate')->latest()->limit(20)->get();
        return response()->json($links);
    });
});

/*
|--------------------------------------------------------------------------
| OCR TEST ROUTE (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/test-ocr-simple', function () {
    $results = [];

    $tesseractPaths = [
        'C:\PROGRA~1\Tesseract-OCR\tesseract.exe',
        'C:\Program Files\Tesseract-OCR\tesseract.exe',
        'C:\Program Files (x86)\Tesseract-OCR\tesseract.exe',
    ];

    $tesseractExists = false;
    $foundPath = null;
    foreach ($tesseractPaths as $path) {
        if (file_exists($path)) {
            $tesseractExists = true;
            $foundPath = $path;
            break;
        }
    }

    $results['tesseract_exists'] = $tesseractExists ? 'Yes' : 'No';
    $results['tesseract_path_checked'] = $foundPath ?: 'Not found in common locations';

    if ($tesseractExists && $foundPath) {
        $shortPath = str_replace('Program Files', 'PROGRA~1', $foundPath);
        $shortPath = str_replace('Program Files (x86)', 'PROGRA~2', $foundPath);
        $cmd = '"' . $shortPath . '" --version 2>&1';
        $output = shell_exec($cmd);
        $results['tesseract_version'] = $output ? explode("\n", $output)[0] : 'Failed to run';
        $results['tesseract_command_used'] = $cmd;
    }

    $results['shell_exec_enabled'] = function_exists('shell_exec') ? 'Yes' : 'No';
    $results['exec_enabled'] = function_exists('exec') ? 'Yes' : 'No';
    $results['gd_extension'] = extension_loaded('gd') ? 'Yes' : 'No';
    $results['storage_writable'] = is_writable(storage_path('app')) ? 'Yes' : 'No';

    try {
        $testImage = storage_path('app/test_' . time() . '.png');

        $img = imagecreate(800, 250);
        $bg = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        $red = imagecolorallocate($img, 255, 0, 0);

        imagerectangle($img, 0, 0, 799, 249, $red);
        imagestring($img, 5, 10, 30, "SIJIL KEMATIAN", $black);
        imagestring($img, 5, 10, 70, "NAMA: MOHAMMAD BIN ABDULLAH", $black);
        imagestring($img, 5, 10, 100, "NO. KP: 800101-10-5678", $black);
        imagestring($img, 5, 10, 130, "TARIKH KEMATIAN: 15 MEI 2024", $black);
        imagestring($img, 5, 10, 160, "JANTINA: LELAKI", $black);
        imagestring($img, 5, 10, 190, "UMUR: 45 TAHUN", $black);

        imagepng($img, $testImage);
        imagedestroy($img);

        if (file_exists($testImage)) {
            $results['test_image_created'] = 'Yes at: ' . $testImage;

            if ($tesseractExists && $foundPath) {
                $shortPath = str_replace('Program Files', 'PROGRA~1', $foundPath);
                $shortPath = str_replace('Program Files (x86)', 'PROGRA~2', $foundPath);
                $cmd = '"' . $shortPath . '" "' . $testImage . '" stdout -l msa+eng --psm 6 2>&1';
                $ocrResult = shell_exec($cmd);
                $results['ocr_test_run'] = 'Completed';
                $results['ocr_output'] = $ocrResult ? nl2br(htmlspecialchars($ocrResult)) : 'No output from OCR';
            } else {
                $results['ocr_test_run'] = 'Skipped - Tesseract not found';
            }

            @unlink($testImage);
        } else {
            $results['test_image_created'] = 'Failed to create test image';
        }
    } catch (\Exception $e) {
        $results['ocr_error'] = 'Exception: ' . $e->getMessage();
    }

    try {
        $ocr = new \App\Services\OCRService();
        $results['ocr_service'] = 'Initialized successfully';
    } catch (\Exception $e) {
        $results['ocr_service'] = 'Failed to initialize: ' . $e->getMessage();
    }

    $results['test_command'] = shell_exec('echo "Testing shell_exec" 2>&1');

    return response()->json($results);
});

/*
|--------------------------------------------------------------------------
| TELEGRAM WEBHOOK (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::post('/telegram/webhook', [TelegramController::class, 'webhook'])
    ->withoutMiddleware(['web', 'csrf'])
    ->name('telegram.webhook');

Route::get('/telegram/webhook', function () {
    return response()->json(['ok' => true, 'message' => 'Telegram webhook endpoint is ready']);
});

Route::get('/telegram/test', [TelegramController::class, 'testBot']);

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (NO LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Static Pages
Route::controller(HomeController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit')->name('contact.submit');
});

// FAQ Public View
Route::get('/faq', [FAQController::class, 'index'])->name('faq.index');

// Feedback Public View
Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');

// Faraid Cases Public View
Route::get('/faraid-cases', [FaraidCaseController::class, 'index'])->name('faraid-cases.index');
Route::get('/faraid-cases/{id}', [FaraidCaseController::class, 'show'])->name('faraid-cases.show');

// Legal Pages
Route::view('/privacy', 'pages.privacy')->name('privacy');
Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/cookie-policy', 'pages.cookies')->name('cookies');

// Public Shared Calculation Routes
Route::get('/calculator/share/{token}', [CalculationController::class, 'viewShared'])->name('calculator.shared.view');
Route::get('/calculator/share/{token}/print', [CalculationController::class, 'sharedPrint'])->name('calculator.shared.print');

/*
|--------------------------------------------------------------------------
| CALCULATOR ROUTES (PUBLIC ACCESS - NO LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/

Route::prefix('calculator')->name('calculator.')->controller(CalculationController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/calculate', 'calculate')->name('calculate');
    Route::get('/preview', 'preview')->name('preview');

    // These routes require login for saving/editing (handled in controller)
    Route::post('/', 'store')->name('store');
    Route::post('/save-pending', 'savePending')->name('save-pending');
    Route::get('/history', 'history')->name('history');
    Route::get('/{calculation}', 'show')->name('show');
    Route::get('/{calculation}/edit', 'edit')->name('edit');
    Route::put('/{calculation}', 'update')->name('update');
    Route::patch('/{calculation}', 'update');
    Route::delete('/{calculation}', 'destroy')->name('destroy');
    Route::get('/{calculation}/check-data', 'checkData')->name('check-data');
    Route::get('/{calculation}/fix', 'fixCalculationData')->name('fix');
    Route::get('/{calculation}/print', 'print')->name('print');
    Route::get('/{calculation}/export', 'export')->name('export');
    Route::post('/{calculation}/generate-tree', 'generateTree')->name('generate-tree');
    Route::get('/{calculation}/download-tree/{format?}', 'downloadTree')->name('download-tree');
    Route::post('/{calculation}/generate-graphviz', 'generateGraphvizTree')->name('generate-graphviz');
    Route::get('/{calculation}/download-graphviz/{format?}', 'downloadGraphvizTree')->name('download-graphviz');
    Route::post('/{calculation}/clone', 'clone')->name('clone');
    Route::post('/{calculation}/share', 'share')->name('share');
    Route::delete('/{calculation}/share', 'revokeShare')->name('revoke-share');
    Route::get('/{calculation}/validate', 'validateData')->name('validate');
});

Route::get('/calculator/scenario-rules', [FaraidController::class, 'getScenarioRules'])->name('calculator.getScenarioRules');
Route::post('/calculator/match-scenario', [FaraidController::class, 'matchScenario'])->name('calculator.matchScenario');

/*
|--------------------------------------------------------------------------
| INSTANT ESTATE PUBLIC POLLING ROUTE (NO AUTH REQUIRED)
|--------------------------------------------------------------------------
*/

Route::get('/instant-estate/status/{sessionId}', [InstantEstateController::class, 'getAdminStatus'])
    ->name('instant-estate.admin-status');

/*
|--------------------------------------------------------------------------
| INSTANT ESTATE ROUTES (PUBLIC ACCESS - NO LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/

Route::prefix('instant-estate')->name('instant-estate.')->controller(InstantEstateController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/upload', 'upload')->name('upload');
    Route::post('/process-ocr/{sessionId}', 'processOCR')->name('process-ocr');
    Route::post('/validate-ocr/{sessionId}', 'validateOCRData')->name('validate-ocr');
    Route::get('/ocr-status/{sessionId}', 'getOCRStatus')->name('ocr-status');
    Route::post('/search-database/{sessionId}', 'searchDatabase')->name('search-database');
    Route::post('/edit-and-continue/{sessionId}', 'editAndContinue')->name('edit-and-continue');
    Route::post('/save-data/{sessionId}', 'saveEditedData')->name('save-data');
    Route::post('/verify-captcha/{sessionId}', 'verifyCaptcha')->name('verify-captcha');
    Route::post('/request-notification/{sessionId}', 'requestNotification')->name('request-notification');
    Route::get('/notification-status/{sessionId}', 'getNotificationStatus')->name('notification-status');
    Route::get('/result/{sessionId}', 'result')->name('result');
    Route::get('/session/{sessionId}', 'viewSession')->name('view-session');
    // DELETE route with UUID constraint to prevent invalid session IDs (e.g., 'null')
    Route::delete('/session/{sessionId}', [InstantEstateController::class, 'deleteSession'])
        ->name('delete-session')
        ->where('sessionId', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
    Route::post('/session/{sessionId}/retry', 'retryProcessing')->name('retry-processing');
    Route::post('/session/{sessionId}/cancel', 'cancelProcessing')->name('cancel-processing');
    Route::get('/view-report/{sessionId}', 'viewReport')->name('view-report');
    Route::get('/download-report/{sessionId}', 'downloadReport')->name('download-report');
    Route::get('/extracted-data/{sessionId}', 'getExtractedData')->name('extracted-data');
    Route::post('/auto-fill-calculator/{sessionId}', 'autoFillCalculator')->name('auto-fill-calculator');
    Route::get('/manual-entry/{sessionId}', 'redirectToManualCalculator')->name('manual-entry');
    Route::get('/download/{sessionId}/extracted-data', 'downloadExtractedData')->name('download-extracted');
    Route::get('/download/{sessionId}/death-certificate', 'downloadDeathCertificate')->name('download-certificate');
    Route::get('/user-statistics', 'getUserStatistics')->name('user-statistics');
    Route::get('/test-session/{sessionId}', 'testSession')->name('test-session');
    Route::get('/get-token/{sessionId}', 'getPublicAccessToken')->name('get-token');
    Route::post('/send-report-link/{sessionId}', 'sendReportLink')->name('send-report-link');

    // These routes require login for history/statistics (handled in controller)
    Route::get('/history', 'history')->name('history');
    Route::get('/statistics', 'statistics')->name('statistics');

    // Debt management - public access via session
    Route::get('/{sessionId}/debts', 'getDebts')->name('debts');
    Route::post('/{sessionId}/debts', 'addDebt')->name('debts.store');
    Route::post('/{sessionId}/debts/{debtId}/pay', 'recordDebtPayment')->name('debts.pay');
    Route::post('/{sessionId}/notify-beneficiaries', 'sendBeneficiaryNotifications')->name('notify-beneficiaries');
    Route::get('/{sessionId}/will', 'viewWill')->name('view-will');
    Route::post('/{sessionId}/will/access', 'recordWillAccess')->name('record-will-access');
});

/*
|--------------------------------------------------------------------------
| INSTANT ESTATE PUBLIC VIEW ROUTES (NO AUTH REQUIRED)
|--------------------------------------------------------------------------
*/

Route::get('/instant-estate/report/{token}', [InstantEstateController::class, 'publicView'])->name('instant-estate.public-view');
Route::get('/instant-estate/download-report/{token}', [InstantEstateController::class, 'downloadReportFromToken'])->name('instant-estate.download-report-token');

// Public routes for beneficiaries (estate-setup)
Route::get('/estate/secure-view/{token}', [InstantEstateController::class, 'secureBeneficiaryView'])
    ->middleware('verify.beneficiary')
    ->name('estate.secure-view');

Route::post('/estate/verify-token', [InstantEstateController::class, 'verifyBeneficiaryToken'])->name('estate.verify-token');

// Beneficiary access route (estate-setup)
Route::get('/beneficiary/access/{token}', [BeneficiaryAccessController::class, 'show'])
    ->middleware('verify.beneficiary')
    ->name('beneficiary.access');

// Public route for viewing will (with token)
Route::get('/view-will/{token}', [EstateSetupController::class, 'viewWill'])->name('estate.view-will');
Route::get('/estate/view-will/{token}', [EstateSetupController::class, 'viewWill'])->name('estate.view-will.alt');

// Public inheritance result viewing
Route::get('/inheritance/view-result/{token}', [InheritanceNotificationController::class, 'viewResult'])->name('inheritance.view-result');

/*
|--------------------------------------------------------------------------
| TELEGRAM LINKING (PUBLIC)
|--------------------------------------------------------------------------
*/

Route::get('/telegram/link', function (Request $request) {
    $token = $request->get('token');

    if (!$token) {
        return redirect('/login')->with('error', 'Invalid Telegram link token');
    }

    session(['telegram_link_token' => $token]);

    if (Auth::check()) {
        $user = Auth::user();
        $user->telegram_session = $token;
        $user->telegram_session_expires_at = now()->addMinutes(30);
        $user->save();

        return view('telegram.link', [
            'token' => $token,
            'bot_url' => 'https://t.me/FaraidCalculatorBot?start=' . $token
        ]);
    }

    return redirect('/login?telegram_token=' . $token);
})->name('telegram.link');

// Telegram callback route
Route::get('/telegram/callback', [LoginController::class, 'telegramWebCallback'])->name('telegram.web.callback');

/*
|--------------------------------------------------------------------------
| GUEST-ONLY ROUTES (NO AUTHENTICATION REQUIRED)
|--------------------------------------------------------------------------
*/

Route::middleware(['guest'])->group(function () {
    // Authentication Routes
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login')->name('login.submit');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'showRegistrationForm')->name('register');
        Route::post('/register', 'register')->name('register.submit');
    });

    // Password Reset Routes
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('/forgot-password', 'showLinkRequestForm')->name('password.request');
        Route::post('/forgot-password', 'sendResetLinkEmail')->name('password.email');
    });

    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
        Route::post('/reset-password', 'reset')->name('password.update');
    });
});

/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION ROUTES
|--------------------------------------------------------------------------
*/

// The "notice" and "send" routes still need authentication
Route::middleware(['auth'])->group(function () {
    Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
        ->name('verification.notice');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');
});

// The verification action must be public (only signed)
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USER ROUTES (EMAIL VERIFIED REQUIRED)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::patch('/', 'update');
        Route::get('/security', 'showSecurity')->name('security');
        Route::put('/password', 'updatePassword')->name('password.update');
        Route::delete('/', 'destroy')->name('destroy');
        Route::post('/avatar', 'updateAvatar')->name('avatar.update');
    });

    // User Feedback (create feedback - requires login)
    Route::prefix('feedback')->name('feedback.')->controller(FeedbackController::class)->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/my', 'myFeedback')->name('my');
        Route::get('/{feedback}/edit', 'edit')->name('edit');
        Route::put('/{feedback}', 'update')->name('update');
        Route::patch('/{feedback}', 'update');
        Route::delete('/{feedback}', 'destroy')->name('destroy');
    });

    // Faraid Cases Management (create/edit cases - requires login)
    Route::prefix('faraid-cases')->name('faraid-cases.')->controller(FaraidCaseController::class)->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/my-cases', 'myCases')->name('my-cases');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::patch('/{id}', 'update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::post('/{id}/toggle-visibility', 'toggleVisibility')->name('toggle-visibility');
        Route::post('/{id}/add-comment', 'addComment')->name('add-comment');
        Route::post('/{id}/like', 'like')->name('like');
    });

    // Telegram linking routes (requires login)
    Route::prefix('telegram')->name('telegram.')->group(function () {
        Route::get('/link-page', [TelegramController::class, 'showLinkPage'])->name('link-page');
        Route::post('/generate-token', [TelegramController::class, 'generateSessionToken'])->name('generate-token');
        Route::get('/check-status', [TelegramController::class, 'checkLinkedStatus'])->name('check-status');
        Route::post('/unlink', [TelegramController::class, 'unlinkUser'])->name('unlink');
        Route::get('/link/success', [LoginController::class, 'telegramLinkSuccess'])->name('link.success');
        Route::get('/calculations', [TelegramController::class, 'getUserCalculations'])->name('calculations');
        Route::post('/send-test', [TelegramController::class, 'sendTestMessage'])->name('send-test');
    });

    /*
    |--------------------------------------------------------------------------
    | ESTATE SETUP ROUTES (PRE-DEATH REGISTRATION) - REQUIRES LOGIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('estate-setup')->name('estate-setup.')->controller(EstateSetupController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/dashboard', 'dashboard')->name('dashboard');

        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');

        Route::get('/{uniqueId}', 'show')->name('show');

        Route::get('/{uniqueId}/edit', 'edit')->name('edit');
        Route::put('/{uniqueId}', 'update')->name('update');

        Route::delete('/{uniqueId}', 'destroy')->name('destroy');

        Route::get('/{uniqueId}/heirs', 'heirs')->name('heirs');
        Route::get('/{uniqueId}/assets', 'assets')->name('assets');
        Route::get('/{uniqueId}/debts', 'debts')->name('debts');
        Route::get('/{uniqueId}/wasiyyah', 'wasiyyah')->name('wasiyyah');
        Route::get('/{uniqueId}/will-video', 'willVideo')->name('will-video');
        Route::get('/{uniqueId}/trustee', 'trustee')->name('trustee');
        Route::get('/{uniqueId}/review', 'review')->name('review');

        Route::post('/{uniqueId}/heirs/store', 'storeHeir')->name('heirs.store');
        Route::put('/{uniqueId}/heirs/{heirId}', 'updateHeir')->name('heirs.update');
        Route::delete('/{uniqueId}/heirs/{heirId}', 'deleteHeir')->name('heirs.delete');

        Route::post('/{uniqueId}/assets/store', 'storeAsset')->name('assets.store');
        Route::put('/{uniqueId}/assets/{assetId}', 'updateAsset')->name('assets.update');
        Route::delete('/{uniqueId}/assets/{assetId}', 'deleteAsset')->name('assets.delete');

        Route::post('/{uniqueId}/debts/store', 'storeDebt')->name('debts.store');
        Route::put('/{uniqueId}/debts/{debtId}', 'updateDebt')->name('debts.update');
        Route::delete('/{uniqueId}/debts/{debtId}', 'deleteDebt')->name('debts.delete');

        Route::post('/{uniqueId}/wasiyyah/store', 'storeWasiyyah')->name('wasiyyah.store');
        Route::put('/{uniqueId}/wasiyyah/{wasiyyahId}', 'updateWasiyyah')->name('wasiyyah.update');
        Route::delete('/{uniqueId}/wasiyyah/{wasiyyahId}', 'deleteWasiyyah')->name('wasiyyah.delete');

        Route::post('/{uniqueId}/trustee/store', 'storeTrustee')->name('trustee.store');
        Route::put('/{uniqueId}/trustee/update', 'updateTrustee')->name('trustee.update');

        Route::post('/{uniqueId}/will-video/save', 'saveWillVideo')->name('will-video.save');
        Route::delete('/{uniqueId}/will-video/delete', 'deleteWillVideo')->name('will-video.delete');

        Route::post('/{uniqueId}/upload-death-certificate', 'uploadDeathCertificate')->name('upload-death-certificate');

        Route::post('/{uniqueId}/activate', 'activate')->name('activate');
        Route::post('/{uniqueId}/deactivate', 'deactivate')->name('deactivate');
        Route::post('/{uniqueId}/complete', 'complete')->name('complete.with-id');
        Route::post('/complete', 'complete')->name('complete');

        Route::get('/{uniqueId}/export', 'export')->name('export');
        Route::get('/{uniqueId}/print', 'print')->name('print');

        Route::post('/calculate-faraid', 'calculateFaraid')->name('calculate-faraid');

        Route::get('/{uniqueId}/summary', 'getSummary')->name('api.summary');
        Route::get('/{uniqueId}/progress', 'getProgress')->name('api.progress');
        Route::post('/{uniqueId}/validate', 'validateData')->name('api.validate');
    });

    /*
    |--------------------------------------------------------------------------
    | INHERITANCE NOTIFICATION ROUTES
    |--------------------------------------------------------------------------
    */
    Route::prefix('inheritance')->name('inheritance.')->controller(InheritanceNotificationController::class)->group(function () {
        Route::post('/request-notification/{uniqueId}', 'requestNotification')->name('request-notification');
    });
});

/*
|--------------------------------------------------------------------------
| ESTATE PLAN ACTIVATION SUCCESS PAGE - OUTSIDE AUTH GROUP
|--------------------------------------------------------------------------
*/

Route::get('/estate-setup/activated', function () {
    return view('estate-setup.index');
})->name('estate-setup.activated');

Route::get('/estate-setup/activated/{token?}', function ($token = null) {
    return view('estate-setup.index', ['activation_token' => $token]);
})->name('estate-setup.activated-with-token');

/*
|--------------------------------------------------------------------------
| API ROUTES FOR AJAX CALLS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->prefix('api')->name('api.')->group(function () {
    Route::get('/calculation/{id}/tree-status', function ($id) {
        $calculation = Calculation::where('user_id', auth()->id())->findOrFail($id);

        return response()->json([
            'status' => $calculation->tree_generation_status,
            'tree_url' => $calculation->family_tree_image ? Storage::disk('public')->url($calculation->family_tree_image) : null,
            'error' => $calculation->tree_generation_error,
            'can_regenerate' => $calculation->tree_generation_status === 'failed'
        ]);
    })->name('calculation.tree-status');

    Route::get('/calculation/{id}/validate', [CalculationController::class, 'validateData'])->name('calculation.validate');
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');
    Route::get('/feedback/stats', [FeedbackController::class, 'stats'])->name('feedback.stats');

    Route::prefix('instant-estate')->name('instant-estate.')->controller(InstantEstateController::class)->group(function () {
        Route::get('/session/{sessionId}/status', 'getOCRStatus');
        Route::post('/session/{sessionId}/cancel', 'cancelProcessing');
        Route::get('/user/sessions', 'getUserSessions');
        Route::get('/statistics', 'getStatistics');
        Route::post('/session/{sessionId}/retry', 'retryProcessing');
        Route::delete('/session/{sessionId}', 'deleteSession');
        Route::post('/verify-captcha/{sessionId}', 'verifyCaptcha');
        Route::post('/request-notification/{sessionId}', 'requestNotification');
        Route::get('/notification-status/{sessionId}', 'getNotificationStatus');
        Route::get('/test-session/{sessionId}', 'testSession');
    });

    Route::prefix('estate-setup')->name('estate-setup.')->controller(EstateSetupController::class)->group(function () {
        Route::get('/{uniqueId}/summary', 'getSummary');
        Route::post('/{uniqueId}/validate', 'validateData');
        Route::get('/{uniqueId}/progress', 'getProgress');
    });

    Route::prefix('faraid-cases')->name('faraid-cases.')->controller(FaraidCaseController::class)->group(function () {
        Route::get('/recent', 'recentCases')->name('recent');
        Route::get('/popular', 'popularCases')->name('popular');
        Route::get('/search', 'search')->name('search');
        Route::get('/categories', 'categories')->name('categories');
        Route::get('/{id}/comments', 'comments')->name('comments');
        Route::get('/{id}/similar', 'similarCases')->name('similar');
    });

    Route::prefix('telegram')->name('telegram.')->group(function () {
        Route::get('/link-status', [TelegramController::class, 'checkLinkedStatus']);
        Route::post('/generate-link', [TelegramController::class, 'generateSessionToken']);
        Route::post('/send-notification', function () {
            $user = auth()->user();
            if ($user && $user->telegram_chat_id) {
                $controller = app(TelegramController::class);
                $result = $controller->sendMessage(
                    $user->telegram_chat_id,
                    "Test Notification\n\nThis is a test message from Neo Faraid!"
                );

                return response()->json([
                    'success' => (bool)$result,
                    'message' => $result ? 'Test message sent successfully!' : 'Failed to send test message'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Telegram account not linked'
            ], 400);
        });
    });
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Real-time stats
    Route::get('/realtime-stats', [AdminController::class, 'getRealtimeStats'])->name('realtime-stats');

    // ================================================================
    // USER MANAGEMENT – now using dedicated UserController
    // ================================================================
    Route::prefix('users')->name('users.')->controller(\App\Http\Controllers\Admin\UserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{user}', 'show')->name('show');
        Route::get('/{user}/edit', 'edit')->name('edit');
        Route::put('/{user}', 'update')->name('update');
        Route::patch('/{user}', 'update');
        Route::delete('/{user}', 'destroy')->name('destroy');
        Route::post('/{user}/toggle-status', 'toggleStatus')->name('toggle-status');
        Route::post('/{user}/reset-password', 'resetPassword')->name('reset-password');
        Route::get('/export', 'export')->name('export');
    });

    // Feedback Management
    Route::prefix('feedback')->name('feedback.')->controller(AdminController::class)->group(function () {
        Route::get('/', 'feedbackIndex')->name('index');
        Route::get('/{id}', 'feedbackShow')->name('show');
        Route::put('/{id}', 'feedbackUpdate')->name('update');
        Route::patch('/{id}', 'feedbackUpdate');
        Route::delete('/{id}', 'feedbackDestroy')->name('destroy');
        Route::post('/{id}/approve', 'feedbackApprove')->name('approve');
        Route::post('/{id}/reject', 'feedbackReject')->name('reject');
        Route::post('/{id}/toggle-visibility', 'toggleVisibility')->name('toggle-visibility');
    });

    // Legacy Feedback routes (via FeedbackController)
    Route::prefix('feedback-legacy')->name('feedback-legacy.')->controller(FeedbackController::class)->group(function () {
        Route::get('/', 'adminIndex')->name('index');
        Route::get('/{id}', 'adminShow')->name('show');
        Route::put('/{id}', 'adminUpdate')->name('update');
        Route::patch('/{id}', 'adminUpdate');
        Route::delete('/{id}', 'adminDestroy')->name('destroy');
        Route::post('/{id}/approve', 'approve')->name('approve');
        Route::post('/{id}/reject', 'reject')->name('reject');
        Route::post('/{id}/toggle-visibility', 'toggleVisibility')->name('toggle-visibility');
    });

    // FAQ Management
    Route::prefix('faq')->name('faq.')->controller(AdminController::class)->group(function () {
        Route::get('/', 'faqIndex')->name('index');
        Route::get('/create', 'faqCreate')->name('create');
        Route::post('/', 'faqStore')->name('store');
        Route::get('/{faq}/edit', 'faqEdit')->name('edit');
        Route::put('/{faq}', 'faqUpdate')->name('update');
        Route::patch('/{faq}', 'faqUpdate');
        Route::delete('/{faq}', 'faqDestroy')->name('destroy');
        Route::post('/{faq}/toggle-status', 'faqToggleStatus')->name('toggle-status');
    });

    // Legacy FAQ routes (via FAQController)
    Route::prefix('faq-legacy')->name('faq-legacy.')->controller(FAQController::class)->group(function () {
        Route::get('/', 'adminIndex')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{faq}/edit', 'edit')->name('edit');
        Route::put('/{faq}', 'update')->name('update');
        Route::patch('/{faq}', 'update');
        Route::delete('/{faq}', 'destroy')->name('destroy');
        Route::post('/{faq}/toggle-status', 'toggleStatus')->name('toggle-status');
    });

    // Faraid Cases Management
    Route::prefix('faraid-cases')->name('faraid-cases.')->controller(FaraidCaseController::class)->group(function () {
        Route::get('/', 'adminIndex')->name('index');
        Route::get('/{id}', 'adminShow')->name('show');
        Route::get('/{id}/edit', 'adminEdit')->name('edit');
        Route::put('/{id}', 'adminUpdate')->name('update');
        Route::patch('/{id}', 'adminUpdate');
        Route::delete('/{id}', 'adminDestroy')->name('destroy');
        Route::post('/{id}/toggle-status', 'adminToggleStatus')->name('toggle-status');
        Route::post('/{id}/toggle-featured', 'adminToggleFeatured')->name('toggle-featured');
        Route::post('/{id}/approve', 'adminApprove')->name('approve');
        Route::post('/{id}/reject', 'adminReject')->name('reject');
        Route::get('/categories', 'adminCategories')->name('categories');
        Route::post('/categories', 'adminStoreCategory')->name('categories.store');
        Route::put('/categories/{id}', 'adminUpdateCategory')->name('categories.update');
        Route::delete('/categories/{id}', 'adminDestroyCategory')->name('categories.destroy');
        Route::get('/export', 'adminExport')->name('export');
        Route::get('/stats', 'adminStats')->name('stats');
    });

    // Calculations Management
    Route::prefix('calculations')->name('calculations.')->controller(AdminController::class)->group(function () {
        Route::get('/', 'calculationIndex')->name('index');
        Route::get('/export', 'exportCalculations')->name('export');
        Route::get('/stats', 'calculationStats')->name('stats');
        Route::post('/search-nric', 'searchByNric')->name('search-nric');
        Route::post('/bulk-delete', 'calculationBulkDelete')->name('bulk-delete');
        Route::get('/{id}', 'calculationShow')->name('show');
        Route::get('/{id}/edit', 'calculationEdit')->name('edit');
        Route::put('/{id}', 'calculationUpdate')->name('update');
        Route::patch('/{id}', 'calculationUpdate');
        Route::delete('/{id}', 'calculationDestroy')->name('destroy');
        Route::get('/{id}/pdf', 'calculationGeneratePdf')->name('pdf');
    });

    // Instant Estate Admin Routes
    Route::prefix('instant-estate')->name('instant-estate.')->group(function () {
        // Main management
        Route::controller(InstantEstateAdminController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/sessions', 'sessions')->name('sessions');
            Route::get('/session/{sessionId}', 'show')->name('show');
            Route::get('/session/{sessionId}/report', 'viewReport')->name('report');
            Route::get('/session/{sessionId}/download-report', 'downloadReport')->name('download-report');
            Route::get('/session/{sessionId}/thumbnail', 'thumbnail')->name('thumbnail');
            Route::delete('/session/{sessionId}', 'deleteSession')->name('delete-session');
            Route::get('/session/{sessionId}/download-file', 'downloadFile')->name('download-file');
            Route::post('/session/{sessionId}/resend', 'resendNotificationForSession')->name('resend');
            Route::post('/session/{sessionId}/reprocess', 'reprocess')->name('reprocess');
            Route::post('/session/{sessionId}/reprocess-ocr', 'reprocessOCR')->name('reprocess-ocr');
            Route::get('/statistics', 'statistics')->name('statistics');
            Route::get('/export', 'export')->name('export');
            Route::post('/bulk-delete', 'bulkDelete')->name('bulk-delete');
            Route::get('/settings', 'settings')->name('settings');
            Route::put('/settings', 'updateSettings')->name('update-settings');
            Route::get('/system-health', 'systemHealth')->name('system-health');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });

        // Notification Management
        Route::prefix('notifications')->name('notifications.')->controller(InstantEstateNotificationController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export', 'export')->name('export');
            Route::get('/stats', 'stats')->name('stats');
            Route::post('/bulk-approve', 'bulkApprove')->name('bulk-approve');
            Route::post('/bulk-reject', 'bulkReject')->name('bulk-reject');
            Route::get('/{requestId}', 'show')->name('show');
            Route::post('/{requestId}/approve', 'approve')->name('approve');
            Route::post('/{requestId}/reject', 'reject')->name('reject');
            Route::post('/{requestId}/resend', 'resend')->name('resend');
        });

        // Approval Management
        Route::prefix('approval')->name('approval.')->controller(InstantEstateApprovalController::class)->group(function () {
            Route::get('/pending-reviews', 'index')->name('pending');
            Route::post('/{sessionId}/approve', 'approve')->name('approve');
            Route::post('/{sessionId}/reject', 'reject')->name('reject');
        });
    });

    // Estate Setup Admin Routes
    Route::prefix('estate-setup')->name('estate-setup.')->controller(AdminEstateSetupController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/statistics', 'statistics')->name('statistics');
        Route::get('/export', 'export')->name('export');
        Route::get('/system-health', 'systemHealth')->name('system-health');
        Route::post('/clean-orphaned', 'cleanOrphanedRecords')->name('clean-orphaned');
        Route::post('/bulk-delete', 'bulkDelete')->name('bulk-delete');

        Route::get('/{uniqueId}', 'show')->name('show');
        Route::post('/{uniqueId}/approve', 'approve')->name('approve');
        Route::post('/{uniqueId}/reject', 'reject')->name('reject');
        Route::post('/{uniqueId}/toggle-status', 'toggleStatus')->name('toggle-status');
        Route::delete('/{uniqueId}', 'destroy')->name('destroy');

        Route::post('/{uniqueId}/debts/{debtId}/settle', 'markDebtSettled')->name('debts.settle');
        Route::get('/{uniqueId}/debts/status', 'getDebtStatus')->name('debts.status');

        Route::get('/{uniqueId}/access-links', 'getAccessLinks')->name('access-links');
        Route::post('/{uniqueId}/access-links/{linkId}/resend', 'resendNotification')->name('access-links.resend');
        Route::delete('/{uniqueId}/access-links/{linkId}', 'revokeAccessLink')->name('access-links.revoke');

        Route::get('/notifications', 'notifications')->name('notifications.index');
        Route::post('/notifications/{requestId}/approve', 'approveNotification')->name('notifications.approve');
        Route::post('/notifications/{requestId}/reject', 'rejectNotification')->name('notifications.reject');
        Route::post('/notifications/{requestId}/resend', 'resendNotification')->name('notifications.resend');

        Route::get('/inheritance-notifications', 'inheritanceNotifications')->name('inheritance-notifications.index');
        Route::post('/inheritance-notifications/{id}/approve', 'approveInheritanceNotification')->name('inheritance-notifications.approve');
        Route::post('/inheritance-notifications/{id}/reject', 'rejectInheritanceNotification')->name('inheritance-notifications.reject');
    });

    // Faraid Rules Management
    Route::prefix('faraid-rules')->name('faraid-rules.')->controller(FaraidController::class)->group(function () {
        Route::get('/', 'adminIndex')->name('index');
        Route::get('/create', 'adminCreate')->name('create');
        Route::post('/', 'adminStore')->name('store');
        Route::get('/{id}/edit', 'adminEdit')->name('edit');
        Route::put('/{id}', 'adminUpdate')->name('update');
        Route::delete('/{id}', 'adminDestroy')->name('destroy');
        Route::post('/{id}/toggle-active', 'adminToggleActive')->name('toggle-active');
    });

    // Telegram Management
    Route::prefix('telegram')->name('telegram.')->controller(TelegramController::class)->group(function () {
        Route::get('/set-webhook', 'setWebhook')->name('set-webhook');
        Route::get('/remove-webhook', 'removeWebhook')->name('remove-webhook');
        Route::get('/test-webhook', 'testWebhook')->name('test-webhook');
        Route::get('/bot-info', 'getBotInfo')->name('bot-info');
        Route::get('/stats', 'getBotStats')->name('stats');
        Route::post('/broadcast', 'broadcastMessage')->name('broadcast');
        Route::post('/send-test', 'sendTestMessage')->name('send-test');
        Route::get('/calculations', 'adminTelegramCalculations')->name('calculations');
        Route::get('/users', 'getTelegramUsers')->name('users');
        Route::post('/user/{chatId}/message', 'sendMessageToUser')->name('send-user-message');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->controller(AdminController::class)->group(function () {
        Route::get('/', 'settingsIndex')->name('index');
        Route::put('/', 'settingsUpdate')->name('update');
        Route::get('/maintenance', 'maintenance')->name('maintenance');
        Route::post('/maintenance/toggle', 'toggleMaintenance')->name('toggle-maintenance');
        Route::get('/backups', 'backups')->name('backups');
        Route::post('/backups/create', 'createBackup')->name('backup.create');
        Route::post('/backups/{backup}/restore', 'restoreBackup')->name('backup.restore');
        Route::delete('/backups/{backup}', 'deleteBackup')->name('backup.delete');
        Route::get('/cache', 'cacheManagement')->name('cache');
        Route::post('/cache/clear', 'clearCache')->name('cache.clear');
        Route::get('/environment', 'environmentSettings')->name('environment');
        Route::post('/environment/update', 'updateEnvironment')->name('environment.update');
    });

    // System & Logs
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
    Route::get('/logs/{file}', [AdminController::class, 'viewLog'])->name('logs.view');
    Route::delete('/logs/{file}', [AdminController::class, 'deleteLog'])->name('logs.delete');
    Route::get('/system-health', [AdminController::class, 'systemHealth'])->name('system-health');
    Route::post('/impersonate/{user}', [AdminController::class, 'impersonate'])->name('impersonate');
    Route::post('/run-backup', [AdminController::class, 'runBackup'])->name('run-backup');
    Route::get('/queue-monitor', [AdminController::class, 'queueMonitor'])->name('queue-monitor');
    Route::post('/queue/retry/{id}', [AdminController::class, 'retryFailedJob'])->name('queue.retry');
    Route::delete('/queue/flush', [AdminController::class, 'flushFailedJobs'])->name('queue.flush');
});

// Stop Impersonation (outside admin group so non-admin users can access it)
Route::post('/admin/stop-impersonate', [AdminController::class, 'stopImpersonate'])
    ->middleware(['auth'])
    ->name('admin.stop-impersonate');

/*
|--------------------------------------------------------------------------
| DEVELOPMENT & UTILITY ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/test-family-tree/{id}', function ($id) {
    $calculation = Calculation::find($id);

    if (!$calculation) {
        return response()->json(['error' => 'Calculation not found'], 404);
    }

    if ($calculation->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
        abort(403, 'Unauthorized');
    }

    $controller = app()->make(CalculationController::class);
    $result = $controller->generateFamilyTreeForCalculation($calculation);

    return response()->json([
        'success' => $result,
        'calculation' => [
            'id' => $calculation->id,
            'name' => $calculation->deceased_name,
            'tree_status' => $calculation->tree_generation_status,
            'tree_image' => $calculation->family_tree_image,
            'tree_url' => $calculation->family_tree_image ? Storage::disk('public')->url($calculation->family_tree_image) : null
        ]
    ]);
})->middleware(['auth'])->name('test.family-tree');

Route::get('/graphviz/test', function () {
    if (app()->environment('local')) {
        return Artisan::call('graphviz:test');
    }
    abort(404);
})->middleware(['web'])->name('graphviz.test');

// Development routes only available in local environment
if (app()->environment('local')) {
    Route::middleware(['web'])->group(function () {
        Route::view('/dev/routes', 'dev.routes', [
            'routes' => collect(Route::getRoutes())->map(function ($route) {
                return [
                    'method' => implode('|', $route->methods()),
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'action' => $route->getActionName(),
                    'middleware' => $route->middleware(),
                ];
            })
        ])->name('dev.routes');

        Route::get('/dev/phpinfo', function () { phpinfo(); })->name('dev.phpinfo');

        Route::get('/dev/clear-cache', function () {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            return 'Cache cleared successfully!';
        })->name('dev.clear-cache');

        Route::get('/dev/generate-test-data', function () {
            Artisan::call('db:seed --class=TestDataSeeder');
            return 'Test data generated successfully!';
        })->name('dev.generate-test-data');

        Route::get('/dev/migrate-seed', function () {
            Artisan::call('migrate:fresh --seed');
            return 'Database migrated and seeded successfully!';
        })->name('dev.migrate-seed');

        Route::get('/dev/storage-link', function () {
            Artisan::call('storage:link');
            return 'Storage linked successfully!';
        })->name('dev.storage-link');

        Route::get('/dev/instant-estate/test-ocr', [InstantEstateController::class, 'testOCR'])->name('dev.instant-estate.test-ocr');
        Route::get('/dev/instant-estate/clear-sessions', [InstantEstateController::class, 'clearTestSessions'])->name('dev.instant-estate.clear-sessions');

        Route::prefix('dev/telegram')->name('dev.telegram.')->group(function () {
            Route::get('/webhook-status', [TelegramController::class, 'testWebhook'])->name('webhook-status');
            Route::get('/bot-info', [TelegramController::class, 'getBotInfo'])->name('bot-info');
            Route::post('/test-send', [TelegramController::class, 'sendTestMessage'])->name('test-send');
            Route::view('/simulate-webhook', 'dev.telegram-simulate')->name('simulate-webhook');
        });

        Route::get('/dev/debug', function () {
            return response()->json([
                'app_env' => app()->environment(),
                'app_debug' => config('app.debug'),
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            ]);
        })->name('dev.debug');
    });
}

/*
|--------------------------------------------------------------------------
| FALLBACK ROUTE (404)
|--------------------------------------------------------------------------
*/

Route::fallback(function () {
    if (request()->expectsJson()) {
        return response()->json(['error' => 'Page not found'], 404);
    }
    return response()->view('errors.404', [], 404);
})->name('fallback');