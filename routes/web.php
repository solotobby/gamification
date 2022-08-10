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


Route::get('/', [App\Http\Controllers\GeneralController::class, 'landingPage']);
Route::get('contact', [App\Http\Controllers\GeneralController::class, 'contact'])->name('contact');
Route::get('goal', [App\Http\Controllers\GeneralController::class, 'goal'])->name('goal');
Route::get('games', [App\Http\Controllers\GeneralController::class, 'gamelist'])->name('game.list');
Route::get('winner/list', [App\Http\Controllers\GeneralController::class, 'winnerlist'])->name('winner.list');
Route::get('register/{referral_code}', [\App\Http\Controllers\Auth\RegisterController::class, 'referral_register']);


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
Route::post('save/phone/information', [App\Http\Controllers\HomeController::class, 'savePhoneInformation'])->name('save.phone.information');


////Referral Routes
Route::get('referral/view/all', [\App\Http\Controllers\ReferralController::class, 'viewAll'])->name('ref.all');
////Campaign
Route::get('campaign/create', [\App\Http\Controllers\CampaignController::class, 'create'])->name('campaign.create');
Route::get('api/get/categories', [\App\Http\Controllers\CampaignController::class, 'getCategories']);
Route::get('api/get/sub/categories/{id}', [\App\Http\Controllers\CampaignController::class, 'getSubCategories']);
Route::get('api/get/sub/categories/info/{id}', [\App\Http\Controllers\CampaignController::class, 'getSubcategoriesInfo']);
Route::post('post/campaign', [\App\Http\Controllers\CampaignController::class, 'postCampaign'])->name('post.campaign');
Route::get('campaign/{job_id}', [\App\Http\Controllers\CampaignController::class, 'viewCampaign']);
Route::post('post/campaign/work', [\App\Http\Controllers\CampaignController::class, 'postCampaignWork'])->name('post.campaign.work');
Route::get('my/jobs', [\App\Http\Controllers\JobsController::class, 'myJobs'])->name('my.jobs');
Route::get('my/campaigns', [\App\Http\Controllers\CampaignController::class, 'index'])->name('my.campaigns');
Route::get('campaign/my/submitted/{id}', [\App\Http\Controllers\CampaignController::class, 'mySubmittedCampaign']);





//Admin Routes
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

Route::get('user/home', [App\Http\Controllers\HomeController::class, 'userHome'])->name('user.home');
Route::get('admin/home', [App\Http\Controllers\HomeController::class, 'adminHome'])->name('admin.home');

Route::get('/games/create/{id}', [App\Http\Controllers\Admin\AdminController::class, 'createGame']);
Route::get('question/create', [App\Http\Controllers\Admin\AdminController::class, 'createQuestion'])->name('questions.create');
Route::post('question/store', [App\Http\Controllers\Admin\AdminController::class, 'storeQuestion'])->name('questions.store');
Route::post('question/update', [App\Http\Controllers\Admin\AdminController::class, 'updateQuestion'])->name('questions.update');
Route::get('question/list', [App\Http\Controllers\Admin\AdminController::class, 'listQuestion'])->name('question.list');
//Game Routes
Route::get('game/status/{id}', [App\Http\Controllers\Admin\AdminController::class, 'gameStatus'])->name('game.status');
Route::get('view/activities/{id}', [App\Http\Controllers\Admin\AdminController::class, 'viewActivities'])->name('view.activities');
Route::post('assign/reward', [App\Http\Controllers\Admin\AdminController::class, 'assignReward'])->name('assign.reward');



Route::get('game/create', [App\Http\Controllers\Admin\AdminController::class, 'gameCreate'])->name('game.create');
Route::post('game/store', [App\Http\Controllers\Admin\AdminController::class, 'gameStore'])->name('game.store');
Route::get('view/amount', [App\Http\Controllers\Admin\AdminController::class, 'viewAmount'])->name('view.amount');
Route::post('update/amount', [App\Http\Controllers\Admin\AdminController::class, 'updateAmount'])->name('update.amount');

//airtime mgt
//Route::get('airime', [App\Http\Controllers\Admin\AdminController::class, 'sendAirtime'])->name('airtime');

//category routes
Route::get('create/category', [\App\Http\Controllers\CategoryController::class, 'create'])->name('create.category');
Route::post('post/category', [\App\Http\Controllers\CategoryController::class, 'store'])->name('store');
Route::post('post/subcategory', [\App\Http\Controllers\CategoryController::class, 'storeSubcategory'])->name('store.subcategory');