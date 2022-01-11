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
    return view('landingPage');
});

Auth::routes();

Route::get('auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);

//Take Quiz
Route::get('instruction', [App\Http\Controllers\HomeController::class, 'instruction'])->name('instruction');
Route::get('take/quiz', [App\Http\Controllers\HomeController::class, 'takeQuiz'])->name('take.quiz');
Route::get('next/question', [App\Http\Controllers\HomeController::class, 'nextQuestion']);
Route::get('submit/answers', [App\Http\Controllers\HomeController::class, 'submitAnswers']);
Route::post('store/asnwer', [App\Http\Controllers\HomeController::class, 'storeAnswer'])->name('store.answer');
Route::get('score/list', [App\Http\Controllers\HomeController::class, 'scores'])->name('score.list');
Route::get('redeem/reward/{id}', [App\Http\Controllers\HomeController::class, 'redeemReward'])->name('redeem.reward');
Route::post('save/bank/information', [App\Http\Controllers\HomeController::class, 'saveBankInformation'])->name('save.bank.information');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/games/create/{id}', [App\Http\Controllers\Admin\AdminController::class, 'createGame']);
Route::get('question/create', [App\Http\Controllers\Admin\AdminController::class, 'createQuestion'])->name('questions.create');
Route::post('question/store', [App\Http\Controllers\Admin\AdminController::class, 'storeQuestion'])->name('questions.store');
//Game Routes
Route::get('game/status/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'gameStatus'])->name('game.status');

Route::get('game/create', [\App\Http\Controllers\Admin\AdminController::class, 'gameCreate'])->name('game.create');
Route::post('game/store', [App\Http\Controllers\Admin\AdminController::class, 'gameStore'])->name('game.store');
