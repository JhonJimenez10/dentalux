    <?php

    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\PatientController;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\BudgetController;
    use App\Http\Controllers\PaymentController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\DashboardReportController;
    use App\Http\Controllers\FinanceController;
    use App\Http\Controllers\CashClosingController;
    use App\Http\Controllers\BankAccountController;
    use App\Http\Controllers\PatientImageController;
    use App\Http\Controllers\ReportController;
    use App\Http\Controllers\PendingPaymentsController;



    // ── Servir archivos de storage en Windows ──
    // ── Servir/descargar archivos de storage ──
    Route::get('/files/{path}', function (string $path) {
        $fullPath = storage_path('app/public/' . $path);

        if (!file_exists($fullPath)) {
            abort(404);
        }

        return response()->download($fullPath);

    })->where('path', '.*')->name('storage.serve');


    // Raíz → redirige al dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    // Perfil (viene con Breeze, lo dejamos)
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::resource('patients', PatientController::class);
            Route::prefix('patients/{patient}/budgets')->name('patients.budgets.')->group(function () {
            Route::get('/',              [BudgetController::class, 'index'])  ->name('index');
            Route::get('/create',        [BudgetController::class, 'create']) ->name('create');
            Route::post('/',             [BudgetController::class, 'store'])  ->name('store');
            Route::get('/{budget}',      [BudgetController::class, 'show'])   ->name('show');
            Route::get('/{budget}/edit', [BudgetController::class, 'edit'])   ->name('edit');
            Route::put('/{budget}',      [BudgetController::class, 'update']) ->name('update');
            Route::delete('/{budget}',   [BudgetController::class, 'destroy'])->name('destroy');
            Route::get('/{budget}/print',[BudgetController::class, 'print'])  ->name('print');
        });
        // Control de pagos (anidado bajo paciente)
        Route::prefix('patients/{patient}/payments')->name('patients.payments.')->group(function () {
            Route::get('/create',                              [PaymentController::class, 'create'])       ->name('create');
            Route::post('/',                                   [PaymentController::class, 'store'])        ->name('store');
            Route::get('/{paymentControl}',                    [PaymentController::class, 'show'])         ->name('show');
            Route::get('/{paymentControl}/add',                [PaymentController::class, 'addPayment'])   ->name('add');
            Route::post('/{paymentControl}/add',               [PaymentController::class, 'storePayment']) ->name('store_payment');
            Route::delete('/{paymentControl}/payments/{payment}', [PaymentController::class, 'destroyPayment'])->name('destroy_payment');
            Route::get('/{paymentControl}/print',              [PaymentController::class, 'print'])        ->name('print');
        });
        Route::resource('users', UserController::class)->except(['show']);
        // Reportes de ingresos
        Route::get('/reports/income', [DashboardReportController::class, 'index'])->name('reports.income');
        Route::prefix('finance')->name('finance.')->group(function () {
            Route::get('/',                        [FinanceController::class, 'index'])          ->name('index');
            Route::get('/export/excel',            [FinanceController::class, 'exportExcel'])    ->name('export.excel');
            Route::get('/export/ingresos',         [FinanceController::class, 'exportIngresos']) ->name('export.ingresos');
            Route::get('/export/egresos',          [FinanceController::class, 'exportEgresos'])  ->name('export.egresos');
            Route::get('/expenses/create',         [FinanceController::class, 'createExpense'])  ->name('expenses.create');
            Route::post('/expenses',               [FinanceController::class, 'storeExpense'])   ->name('expenses.store');
            Route::get('/expenses/{expense}/edit', [FinanceController::class, 'editExpense'])    ->name('expenses.edit');
            Route::put('/expenses/{expense}',      [FinanceController::class, 'updateExpense'])  ->name('expenses.update');
            Route::delete('/expenses/{expense}',   [FinanceController::class, 'destroyExpense']) ->name('expenses.destroy');
        });
        // Cierre de caja
        Route::prefix('cash-closing')->name('cash-closing.')->group(function () {
            Route::get('/',                        [CashClosingController::class, 'index'])  ->name('index');
            Route::get('/create',                  [CashClosingController::class, 'create']) ->name('create');
            Route::post('/',                       [CashClosingController::class, 'store'])  ->name('store');
            Route::get('/{cashClosing}',           [CashClosingController::class, 'show'])   ->name('show');
            Route::post('/{cashClosing}/reopen',   [CashClosingController::class, 'reopen']) ->name('reopen');
        });
        // Cuentas bancarias
        Route::resource('bank-accounts', BankAccountController::class)
            ->except(['show']);
        Route::prefix('patients/{patient}/images')->name('patients.images.')->group(function(){
            Route::get('/',              [PatientImageController::class, 'index'])  ->name('index');
            Route::get('/create',        [PatientImageController::class, 'create']) ->name('create');
            Route::post('/',             [PatientImageController::class, 'store'])  ->name('store');
            Route::get('/{image}',       [PatientImageController::class, 'show'])   ->name('show');
            Route::delete('/{image}',    [PatientImageController::class, 'destroy'])->name('destroy');
            Route::get('/api/data',      [PatientImageController::class, 'data'])   ->name('data');
        });
        // Reportes
        Route::prefix('reports')->name('reports.')->group(function(){
            Route::get('/income',       [DashboardReportController::class, 'index'])->name('income');
            Route::get('/daily',        [ReportController::class, 'daily'])         ->name('daily');
            Route::get('/daily/export', [ReportController::class, 'exportDaily'])   ->name('daily.export');
            Route::get('/pending',          [PendingPaymentsController::class, 'index'])           ->name('pending');
            Route::get('/pending/export',   [PendingPaymentsController::class, 'export'])          ->name('pending.export');
        });
        Route::get('/patients/{patient}/payments/{paymentControl}/voucher/{payment}',
            [PaymentController::class, 'voucher'])
            ->name('patients.payments.voucher');
        Route::post(
                '/patients/{patient}/payments/{paymentControl}/send-voucher/{payment}',
                [PaymentController::class, 'sendVoucher']
            )->name('patients.payments.send-voucher');
    });

    require __DIR__.'/auth.php';