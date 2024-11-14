<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DynamicFormController;

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

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dynamic-form', [DynamicFormController::class, 'index'])->name('dynamic-form');
    Route::get('/dynamic-form/get-data', [DynamicFormController::class, 'getData'])->name('dynamic-form.get-data');
    Route::post('/dynamic-form/save', [DynamicFormController::class, 'store'])->name('dynamic-form.store');
    Route::get('dynamic-form/{id}/edit', [DynamicFormController::class, 'edit'])->name('dynamic-form.edit');
    Route::post('dynamic-form/update', [DynamicFormController::class, 'update'])->name('dynamic-form.update');
    Route::get('dynamic-form/{id}/delete', [DynamicFormController::class, 'delete'])->name('dynamic-form.delete');
    Route::get('/dynamic-form/preview/{id}', [DynamicFormController::class, 'previewForm'])->name('dynamic-form.preview');
    Route::get('/form-asnwer', [DynamicFormController::class, 'formAnswer'])->name('form-answer');
    Route::get('/form-asnwer/get-data', [DynamicFormController::class, 'formAnswerGetData'])->name('form-answer.get-data');
    Route::get('/form-asnwer/details/{user_id}/{form_id}', [DynamicFormController::class, 'formAnswerDetails'])->name('form-answer.details');
});

Route::get('user-ui/{slug}', [DynamicFormController::class, 'dynamicForm'])->name('user-ui.dynamic');
Route::post('user-ui/store', [DynamicFormController::class, 'saveFormData'])->name('user-ui.dynamic.store');
Route::get('greeting-page', [DynamicFormController::class, 'greetingPage'])->name('greeting');







