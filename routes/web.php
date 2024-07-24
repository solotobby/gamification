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

// use Illuminate\Support\Facades\App;

use App\Http\Controllers\Admin\PreferenceController;
use App\Http\Controllers\Admin\SafeLockController as AdminSafeLockController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ConversionRateController;
use App\Http\Controllers\KnowledgeBaseController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SafeLockController;
use Illuminate\Support\Facades\App;

Route::get('/', [\App\Http\Controllers\GeneralController::class, 'landingPage']);
Route::get('landing/api', [\App\Http\Controllers\GeneralController::class, 'ladingpageApi']);
Route::get('contact', [\App\Http\Controllers\GeneralController::class, 'contact'])->name('contact');
Route::get('goal', [\App\Http\Controllers\GeneralController::class, 'goal'])->name('goal');

Route::get('login/otp/{token}', [\App\Http\Controllers\GeneralController::class, 'otp'])->name('login.otp');

Route::post('fix/otp', [\App\Http\Controllers\GeneralController::class, 'fixOtp'])->name('fix.otp');

// Route::get('games', [\App\Http\Controllers\GeneralController::class, 'gamelist'])->name('game.list');
// Route::get('winner/list', [\App\Http\Controllers\GeneralController::class, 'winnerlist'])->name('winner.list');
Route::get('register/{referral_code}', [\App\Http\Controllers\Auth\RegisterController::class, 'referral_register']);
Route::get('affiliate', [\App\Http\Controllers\GeneralController::class, 'make_money']);
Route::get('terms', [\App\Http\Controllers\GeneralController::class, 'terms'])->name('terms');
Route::get('privacy', [\App\Http\Controllers\GeneralController::class, 'privacy'])->name('privacy');
Route::get('track-record', [\App\Http\Controllers\GeneralController::class, 'trackRecord'])->name('track.record');
Route::get('faq', [\App\Http\Controllers\GeneralController::class, 'faq'])->name('faq');
Route::get('about', [\App\Http\Controllers\GeneralController::class, 'about'])->name('about');
Route::get('download', [\App\Http\Controllers\GeneralController::class, 'download']);//->name('faq');
Route::post('download', [\App\Http\Controllers\GeneralController::class, 'download_url'])->name('download');

Route::get('wellahealth', [\App\Http\Controllers\GeneralController::class, 'wellaheathLanding']);
Route::get('wellahealthsuccess', [\App\Http\Controllers\GeneralController::class, 'wellahealthsuccess']);
Route::get('agent/wellahealth/payment', [\App\Http\Controllers\GeneralController::class, 'agentPayment']);



Route::post('virtual/account/webhook', [\App\Http\Controllers\WebhookController::class, 'handle']);
Route::get('promo', [\App\Http\Controllers\GeneralController::class, 'promo']);

Route::get('fix', [\App\Http\Controllers\GeneralController::class, 'fix']);
Route::get('marketplace/payment/callback', [\App\Http\Controllers\GeneralMarketplaceController::class, 'marketPlacePaymentCallBack']);
Route::get('marketplace/payment/completion', [\App\Http\Controllers\GeneralMarketplaceController::class, 'marketplaceCompletePayment']);

Route::get('marketplace/{referral_code}/{product_id}', [\App\Http\Controllers\GeneralMarketplaceController::class, 'index']);//->name('marketplace');
Route::get('marketplace/payment/{referral_code}/{product_id}/{ref}', [\App\Http\Controllers\GeneralMarketplaceController::class, 'processPayment']);//->name('marketplace');
Route::post('marketplace/proccess/payment', [\App\Http\Controllers\GeneralMarketplaceController::class, 'enter_info']);//->name('marketplace');
Route::get('resource/{url}', [\App\Http\Controllers\GeneralMarketplaceController::class, 'resourceDownload']);

Route::post('register/user', [\App\Http\Controllers\Auth\RegisterController::class, 'registerUser'])->name('register.user');
Route::post('login/user', [\App\Http\Controllers\Auth\RegisterController::class, 'loginUser'])->name('login.user');


///partner route
Route::get('agent/{ref}/wellahealth', [\App\Http\Controllers\GeneralController::class, 'wellahealth']);
Route::get('agent/{ref}/wellahealth/{planCode}/{numberOfPersons}/{amount}/{type}', [\App\Http\Controllers\GeneralController::class, 'processWellaHealth']);
Route::post('agent/store/wellahealth', [\App\Http\Controllers\GeneralController::class, 'storeWellaHealth']);
//test urls 
Route::get('test/api', [\App\Http\Controllers\GeneralController::class, 'testy']);


Auth::routes();
//GOOGLE AUTH
Route::get('auth/google', [\App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [\App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback']);
//FACEBOOK AUTH
Route::get('auth/facebook', [\App\Http\Controllers\Auth\FacebookController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [\App\Http\Controllers\Auth\FacebookController::class, 'handleFacebookCallback']);
//Take Quiz
Route::get('instruction', [\App\Http\Controllers\HomeController::class, 'instruction'])->name('instruction');
Route::get('take/quiz', [\App\Http\Controllers\HomeController::class, 'takeQuiz'])->name('take.quiz');
Route::get('next/question', [\App\Http\Controllers\HomeController::class, 'nextQuestion']);
Route::get('submit/answers', [\App\Http\Controllers\HomeController::class, 'submitAnswers']);
Route::post('store/asnwer', [\App\Http\Controllers\HomeController::class, 'storeAnswer'])->name('store.answer');
Route::get('score/list', [\App\Http\Controllers\HomeController::class, 'scores'])->name('score.list');
Route::get('redeem/reward/{id}', [\App\Http\Controllers\HomeController::class, 'redeemReward'])->name('redeem.reward');

Route::post('save/bank/information', [\App\Http\Controllers\HomeController::class, 'saveBankInformation'])->name('save.bank.information');

Route::get('bank/information', [\App\Http\Controllers\HomeController::class, 'selectBankInformation']);

Route::post('save/phone/information', [\App\Http\Controllers\HomeController::class, 'savePhoneInformation'])->name('save.phone.information');

Route::post('send/phone/otp', [\App\Http\Controllers\OTPController::class, 'sendPhoneOTP'])->name('send.phone.otp');

Route::post('verify/phone/otp', [\App\Http\Controllers\OTPController::class, 'verifyPhoneOTP'])->name('verify.phone.otp');


////Referral Routes
Route::get('referral/view/all', [\App\Http\Controllers\ReferralController::class, 'viewAll'])->name('ref.all');
Route::get('referral/view/usd', [\App\Http\Controllers\ReferralController::class, 'usdReferee'])->name('ref.usd');

////Campaign
Route::get('campaign/create', [\App\Http\Controllers\CampaignController::class, 'create'])->name('campaign.create');
Route::get('api/get/categories', [\App\Http\Controllers\CampaignController::class, 'getCategories']);
Route::get('api/get/sub/categories/{id}', [\App\Http\Controllers\CampaignController::class, 'getSubCategories']);
Route::get('api/get/sub/categories/info/{id}', [\App\Http\Controllers\CampaignController::class, 'getSubcategoriesInfo']);
Route::post('post/campaign', [\App\Http\Controllers\CampaignController::class, 'postCampaign'])->name('post.campaign');
Route::post('edit/campaign', [\App\Http\Controllers\CampaignController::class, 'update'])->name('edit.campaign');

Route::get('decline/campaign/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'declineCampaign']);

Route::get('extend/payment', [\App\Http\Controllers\CampaignController::class, 'campaign_extension_payment']);
Route::get('campaign/{job_id}', [\App\Http\Controllers\CampaignController::class, 'viewCampaign']);
Route::post('post/campaign/work', [\App\Http\Controllers\CampaignController::class, 'submitWork'])->name('post.campaign.work');
Route::get('my/jobs', [\App\Http\Controllers\JobsController::class, 'myJobs'])->name('my.jobs');
Route::get('my/campaigns', [\App\Http\Controllers\CampaignController::class, 'index'])->name('my.campaigns');
Route::get('campaign/my/submitted/{id}', [\App\Http\Controllers\CampaignController::class, 'mySubmittedCampaign']);
Route::get('campaign/activities/{id}', [\App\Http\Controllers\CampaignController::class, 'activities']);
Route::get('campaign/activities/{id}/response', [\App\Http\Controllers\CampaignController::class, 'activitiesResponse']);
Route::get('admin/campaign/activities/{id}', [\App\Http\Controllers\CampaignController::class, 'adminActivities']);
Route::get('campaign/activities/pause/{id}', [\App\Http\Controllers\CampaignController::class, 'pauseCampaign']);
Route::post('campaign/decision', [\App\Http\Controllers\CampaignController::class, 'campaignDecision'])->name('campaign.decision');
Route::get('campaign/{id}/edit', [\App\Http\Controllers\CampaignController::class, 'edit']);

Route::get('complete/welcome', [\App\Http\Controllers\ProfileController::class, 'welcomeUser']);

Route::post('job/rating', [\App\Http\Controllers\RatingController::class, 'jobRating'])->name('job.rating');

///paystack payment 
// Route::post('/pay', [App\Http\Controllers\PaymentController::class, 'redirectToGateway'])->name('pay');
// Route::get('/payment/callback', [\App\Http\Controllers\PaymentController::class, 'handleGatewayCallback']);
//paypal
Route::get('paypal/return', [\App\Http\Controllers\WalletController::class, 'capturePaypal']);
Route::get('capture/upgrade', [\App\Http\Controllers\UserController::class, 'captureUpgrade']);
///Points Routes
Route::get('points', [\App\Http\Controllers\LoginPointCountroller::class, 'index'])->name('points');
Route::get('points/redeem', [\App\Http\Controllers\LoginPointCountroller::class, 'redeemPoint'])->name('redeem.point');
///payment routes
Route::get('golive/{job_id}', [\App\Http\Controllers\PaystackPaymentController::class, 'goLive']);
Route::get('callback', [\App\Http\Controllers\PaystackPaymentController::class, 'paystackCallback']);
Route::get('upgrade', [\App\Http\Controllers\UserController::class, 'upgrade'])->name('upgrade');
Route::get('upgrade/part/{amount}', [\App\Http\Controllers\UserController::class, 'upgradePart']);
Route::get('upgrade/full/{amount}', [\App\Http\Controllers\UserController::class, 'upgradeFull']);

Route::get('complete/upgrade', [\App\Http\Controllers\UserController::class, 'completeUpgrade']);

Route::get('make/payment', [\App\Http\Controllers\UserController::class, 'makePayment'])->name('make.payment');
Route::get('upgrade/payment', [\App\Http\Controllers\UserController::class, 'upgradeCallback']);
Route::get('make/payment/wallet', [\App\Http\Controllers\UserController::class, 'makePaymentWallet'])->name('make.payment.wallet');
//otp
Route::get('resend/otp', [\App\Http\Controllers\OTPController::class, 'resendOTP']);
//wellahealth
Route::get('agent/wellahealth', [\App\Http\Controllers\WellaHealthController::class, 'index'])->name('agent.wellahealth');
Route::post('agent/create/wellahealth', [\App\Http\Controllers\WellaHealthController::class, 'create'])->name('agent.create.wellahealth');
// survey
Route::get('survey', [\App\Http\Controllers\SurveyController::class, 'survey'])->name('survey');
Route::post('survey', [\App\Http\Controllers\SurveyController::class, 'storeSurvey'])->name('store.survey');
Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'index']);

//virtual account routes
Route::get('assign/virtual/account', [\App\Http\Controllers\VirtualAccountController::class, 'index'])->name('assign.virtual.account');

Route::get('info', [\App\Http\Controllers\UserController::class, 'info']);
Route::get('conversion', [\App\Http\Controllers\UserController::class, 'conversion']);
Route::get('success', [\App\Http\Controllers\UserController::class, 'success']);
Route::get('error', [\App\Http\Controllers\UserController::class, 'error']);
Route::get('transactions', [\App\Http\Controllers\UserController::class, 'transactions'])->name('transactions');
Route::get('withrawal/requests', [\App\Http\Controllers\UserController::class, 'withdrawal_requests'])->name('withdraw.requests');

Route::get('approved/campaigns', [\App\Http\Controllers\CampaignController::class, 'approvedCampaigns']);
Route::get('denied/campaigns', [\App\Http\Controllers\CampaignController::class, 'deniedCampaigns']);
Route::get('completed/jobs', [\App\Http\Controllers\CampaignController::class, 'completedJobs']);
Route::get('disputed/jobs', [\App\Http\Controllers\CampaignController::class, 'disputedJobs']);
Route::post('process/disputed/jobs', [\App\Http\Controllers\CampaignController::class, 'processDisputedJobs'])->name('dispute.job');

Route::post('addmore/workers', [\App\Http\Controllers\CampaignController::class, 'addMoreWorkers'])->name('addmore.workers');


Route::get('campaign/approve/{id}', [\App\Http\Controllers\CampaignController::class, 'approveCampaign']);
Route::get('campaign/deny/{id}', [\App\Http\Controllers\CampaignController::class, 'denyCampaign']);

Route::get('wallet/fund', [\App\Http\Controllers\WalletController::class, 'fund'])->name('fund');
Route::get('wallet/withdraw', [\App\Http\Controllers\WalletController::class, 'withdraw'])->name('withdraw');
Route::post('store/fund', [\App\Http\Controllers\WalletController::class, 'storeFund'])->name('store.funds');
Route::get('cancel/transaction/{ref}', [\App\Http\Controllers\WalletController::class, 'cancelUrl']);//->name('store.funds');
Route::post('store/withdraw', [\App\Http\Controllers\WalletController::class, 'storeWithdraw'])->name('store.withdraw');
Route::get('wallet/topup', [\App\Http\Controllers\WalletController::class, 'walletTop']);
Route::post('switch/wallet', [\App\Http\Controllers\WalletController::class, 'switchWallet']);
Route::get('airtime', [\App\Http\Controllers\UserController::class, 'airtimePurchase'])->name('airtime');
Route::post('buy/airtime', [\App\Http\Controllers\UserController::class, 'buyAirtime'])->name('buy.airtime');
Route::get('databundle', [\App\Http\Controllers\UserController::class, 'databundlePurchase'])->name('databundle');
Route::post('buy/databundle', [\App\Http\Controllers\UserController::class, 'buyDatabundle'])->name('buy.databundle');

//databundle api
Route::get('load/network/{network}', [\App\Http\Controllers\UserController::class, 'loadData']);
//Marketplace
Route::get('marketplace', [\App\Http\Controllers\MarketplaceController::class, 'index'])->name('marketplace');
Route::get('marketplace/view', [\App\Http\Controllers\MarketplaceController::class, 'createProduct'])->name('create.marketplace');
Route::post('marketplace/store', [\App\Http\Controllers\MarketplaceController::class, 'storeProduct'])->name('store.marketplace.product');
Route::get('marketplace/list', [\App\Http\Controllers\MarketplaceController::class, 'myProduct'])->name('my.marketplace.products');

Route::get('feedback', [\App\Http\Controllers\FeedbackController::class, 'index'])->name('feedback');
Route::post('feedback', [\App\Http\Controllers\FeedbackController::class, 'store'])->name('store.feedback');
Route::post('feedback/reply', [\App\Http\Controllers\FeedbackController::class, 'reply'])->name('reply.feedback');

Route::get('feedback/create', [\App\Http\Controllers\FeedbackController::class, 'create']);
Route::get('feedback/view/{feedback_id}', [\App\Http\Controllers\FeedbackController::class, 'view']);
//notification
Route::resource('notifications', NotificationController::class);

// banner 
Route::resource('banner', BannerController::class);
Route::get('api/banner/resources', [\App\Http\Controllers\BannerController::class, 'bannerResources']);

Route::resource('safelock', SafeLockController::class);
Route::post('redeem/safelock', [\App\Http\Controllers\SafeLockController::class, 'redeemSafelock']);

Route::get('ad/{id}/view', [\App\Http\Controllers\BannerController::class, 'adView']);

Route::get('currency/converter', [\App\Http\Controllers\CurrencyConverterController::class, 'index'])->name('converter');
Route::get('naira/dollar', [\App\Http\Controllers\CurrencyConverterController::class, 'nairaDollar']);
Route::get('dollar/naira', [\App\Http\Controllers\CurrencyConverterController::class, 'dollarNaira']);
Route::post('make/conversion', [\App\Http\Controllers\CurrencyConverterController::class, 'makeConversion'])->name('make.conversion');

//stripe integration
Route::get('stripe/checkout/success', [\App\Http\Controllers\WalletController::class, 'stripeCheckoutSuccess'])->name('stripe.checkout.success');
//badge
Route::get('badge', [\App\Http\Controllers\BadgeController::class, 'index'])->name('badge');
Route::get('redeem/badge', [\App\Http\Controllers\BadgeController::class, 'redeemBadge'])->name('redeem.badge');
Route::get('christmas/bonus', [\App\Http\Controllers\UserController::class, 'christmasBundle']);
Route::post('christmas', [\App\Http\Controllers\UserController::class, 'storeChristmasBundle']);

Route::get('fastest/finger', [\App\Http\Controllers\FastestFingerController::class, 'index']);
Route::post('fastest/finger', [\App\Http\Controllers\FastestFingerController::class, 'declareInterest'])->name('fastest.finger');
Route::post('enter/pool', [\App\Http\Controllers\FastestFingerController::class, 'enterPool'])->name('enter.pool');

//webhook handling

//Flutterwave Top up
Route::get('flutterwave/wallet/top', [\App\Http\Controllers\WalletController::class, 'flutterwaveWalletTopUp']);

//Bloq
Route::get('setup/account', [\App\Http\Controllers\BloqController::class, 'setupAccount']);
Route::post('setup/account', [\App\Http\Controllers\BloqController::class, 'setupAccountProcess']);

//form builder

Route::get('create/survey', [\App\Http\Controllers\FormBuilderController::class, 'create']);
Route::post('store/form', [\App\Http\Controllers\FormBuilderController::class, 'storeForm']);
Route::post('build/form', [\App\Http\Controllers\FormBuilderController::class, 'buildForm']);
Route::get('survey/{survey_code}', [\App\Http\Controllers\FormBuilderController::class, 'survey']);

Route::get('preview/form/{survey_code}', [\App\Http\Controllers\FormBuilderController::class, 'previewForm']);
Route::get('list/survey', [\App\Http\Controllers\FormBuilderController::class, 'listSurvey']);

//achievers
Route::get('top/earners', [\App\Http\Controllers\AchieverController::class, 'topEarners']);



///filter
Route::get('available/jobs/{category_id}', [\App\Http\Controllers\HomeController::class, 'filterCampaignByCategories']);
// Route::get('api/flutterwave/list/banks/{countryCode}', [\App\Http\Controllers\WithdrawalController::class, 'listBanks']);
// Route::get('api/brail/rates', [\App\Http\Controllers\WithdrawalController::class, 'rates']);

// ------------------------------------ Admin Routes ------------------------------------------ 
//Admin Routes
Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index']);

Route::get('user/home', [\App\Http\Controllers\HomeController::class, 'userHome'])->name('user.home');
Route::get('admin/home', [\App\Http\Controllers\HomeController::class, 'adminHome'])->name('admin.home');
Route::get('staff/home', [\App\Http\Controllers\HomeController::class, 'staffHome'])->name('staff.home');

///staff Routes
Route::get('staff/payslip', [\App\Http\Controllers\Staff\StaffManagementController::class, 'payslip'])->name('staff.payslip');
Route::get('staff/create', [\App\Http\Controllers\Admin\StaffController::class, 'create'])->name('staff.create');
Route::get('staff/list', [\App\Http\Controllers\Admin\StaffController::class, 'index'])->name('staff.list');
Route::get('staff/salary', [\App\Http\Controllers\Admin\StaffController::class, 'salary'])->name('staff.salary');
Route::post('staff/salary', [\App\Http\Controllers\Admin\StaffController::class, 'processSalary'])->name('process.salary');
Route::post('staff/store', [\App\Http\Controllers\Admin\StaffController::class, 'store'])->name('staff.store');
Route::get('staff/{id}/info', [\App\Http\Controllers\Admin\StaffController::class, 'info']);
Route::post('edit/staff', [\App\Http\Controllers\Admin\StaffController::class, 'edit']);

Route::get('user/api', [\App\Http\Controllers\HomeController::class, 'userApi']);
Route::get('how/to', [\App\Http\Controllers\HomeController::class, 'howTo'])->name('howto');

Route::get('admin/notifications', [\App\Http\Controllers\NotificationController::class, 'adminNotifications']);
Route::post('store/notification', [\App\Http\Controllers\NotificationController::class, 'storeNotification']);
Route::get('change/notification/status/{id}', [\App\Http\Controllers\NotificationController::class, 'changeNotificationStatus']);
Route::get('/games/create/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'createGame']);
Route::get('question/create', [\App\Http\Controllers\Admin\AdminController::class, 'createQuestion'])->name('questions.create');
Route::post('question/store', [\App\Http\Controllers\Admin\AdminController::class, 'storeQuestion'])->name('questions.store');
Route::post('question/update', [\App\Http\Controllers\Admin\AdminController::class, 'updateQuestion'])->name('questions.update');
Route::get('question/list', [\App\Http\Controllers\Admin\AdminController::class, 'listQuestion'])->name('question.list');
//Game Routes
Route::get('game/status/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'gameStatus'])->name('game.status');
Route::get('view/activities/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'viewActivities'])->name('view.activities');
Route::post('assign/reward', [\App\Http\Controllers\Admin\AdminController::class, 'assignReward'])->name('assign.reward');

///knowledge_bases
Route::resource('knowledgebase', KnowledgeBaseController::class);


Route::get('game/create', [\App\Http\Controllers\Admin\AdminController::class, 'gameCreate'])->name('game.create');
Route::post('game/store', [\App\Http\Controllers\Admin\AdminController::class, 'gameStore'])->name('game.store');
Route::get('view/amount', [\App\Http\Controllers\Admin\AdminController::class, 'viewAmount'])->name('view.amount');
Route::post('update/amount', [\App\Http\Controllers\Admin\AdminController::class, 'updateAmount'])->name('update.amount');

//airtime mgt
//Route::get('airime', [App\Http\Controllers\Admin\AdminController::class, 'sendAirtime'])->name('airtime');

//category routes
Route::get('create/category', [\App\Http\Controllers\CategoryController::class, 'create'])->name('create.category');
Route::post('post/category', [\App\Http\Controllers\CategoryController::class, 'store'])->name('store');
Route::post('post/subcategory', [\App\Http\Controllers\CategoryController::class, 'storeSubcategory'])->name('store.subcategory');
Route::post('edit/subcategories', [\App\Http\Controllers\CategoryController::class, 'updateSubcategory']);
Route::post('edit/subcategories/naira', [\App\Http\Controllers\CategoryController::class, 'updateSubcategoryNaira']);

//User List
Route::get('users/search', [\App\Http\Controllers\Admin\AdminController::class, 'userSearch'])->name('user.search');
Route::get('users', [\App\Http\Controllers\Admin\AdminController::class, 'userList'])->name('user.list');
Route::get('verified/users', [\App\Http\Controllers\Admin\AdminController::class, 'verifiedUserList'])->name('verified.user.list');
Route::get('usd/verified/users', [\App\Http\Controllers\Admin\AdminController::class, 'usdVerifiedUserList'])->name('usd.verified.user.list');
Route::get('admin/transaction', [\App\Http\Controllers\Admin\AdminController::class, 'adminTransaction'])->name('admin.transaction');
Route::get('user/transaction', [\App\Http\Controllers\Admin\AdminController::class, 'userTransaction'])->name('user.transaction');
Route::get('admin/user/referral/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'adminUserReferrals']);

Route::get('user/{id}/info', [\App\Http\Controllers\Admin\AdminController::class, 'userInfo']);
Route::get('admin/withdrawal/request', [\App\Http\Controllers\Admin\AdminController::class, 'withdrawalRequest'])->name('admin.withdrawal');
Route::get('admin/withdrawal/request/queued', [\App\Http\Controllers\Admin\AdminController::class, 'withdrawalRequestQueued'])->name('admin.withdrawal.queued');
Route::get('admin/withdrawal/request/queued/current', [\App\Http\Controllers\Admin\AdminController::class, 'withdrawalRequestQueuedCurrent'])->name('admin.withdrawal.queued.current');

Route::get('update/withdrawal/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'updateWithdrawalRequest']);
Route::get('update/withdrawal/manual/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'updateWithdrawalRequestManual']);
Route::get('admin/upgrade/{id}/dollar', [\App\Http\Controllers\Admin\AdminController::class, 'upgradeUserDollar']);//->name('admin.withdrawal');
Route::get('admin/upgrade/{id}/naira', [\App\Http\Controllers\Admin\AdminController::class, 'upgradeUserNaira']);//->name('admin.withdrawal');

Route::get('campaigns', [\App\Http\Controllers\Admin\AdminController::class, 'campaignList'])->name('campaign.list');
Route::get('campaigns/pending', [\App\Http\Controllers\Admin\AdminController::class, 'campaignPending'])->name('campaign.pending');
Route::get('campaigns/completed', [\App\Http\Controllers\Admin\AdminController::class, 'campaignCompleted'])->name('campaign.completed');
Route::get('campaigns/denied', [\App\Http\Controllers\Admin\AdminController::class, 'deniedCampaigns']);
Route::get('campaign/info/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'campaignInfo']);
Route::get('campaign/delete/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'campaignDelete']);

// Route::get('campaign/status/{status}/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'campaignStatus']);
Route::post('campaign/status', [\App\Http\Controllers\Admin\AdminController::class, 'campaignStatus'])->name('campaign.status');
Route::get('mass/mail', [\App\Http\Controllers\Admin\AdminController::class, 'massMail'])->name('mass.mail');
Route::post('send/mass/mail', [\App\Http\Controllers\Admin\AdminController::class, 'sendMassMail'])->name('send.mass.email');
Route::get('mass/sms', [\App\Http\Controllers\Admin\SMSController::class, 'massSMS'])->name('mass.sms');
Route::get('mass/sms/preview', [\App\Http\Controllers\Admin\SMSController::class, 'massSMSPreview'])->name('mass.sms.preview');
Route::post('mass/sms', [\App\Http\Controllers\Admin\SMSController::class, 'send_massSMS'])->name('send.mass.sms');

Route::get('unapproved', [\App\Http\Controllers\Admin\AdminController::class, 'unapprovedJobs'])->name('unapproved');
Route::get('approved', [\App\Http\Controllers\Admin\AdminController::class, 'approvedJobs'])->name('approved');
Route::get('reverse/transaction/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'jobReversal']);
Route::post('mass/approval', [\App\Http\Controllers\Admin\AdminController::class, 'massApproval'])->name('mass.approval');

/////Market Place
Route::get('admin/marketplace/view', [\App\Http\Controllers\Admin\AdminController::class, 'viewMarketplace'])->name('view.admin.marketplace');
Route::get('admin/marketplace/create', [\App\Http\Controllers\Admin\AdminController::class, 'marketplaceCreateProduct'])->name('marketplace.create.product');
Route::post('admin/post/marketplace', [\App\Http\Controllers\Admin\AdminController::class, 'storeMarketplace'])->name('store.marketplace');
Route::get('admin/remove/marketplace/product/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'removeMarketplaceProduct']);

//Databundles
Route::get('create/databundles', [\App\Http\Controllers\Admin\AdminController::class, 'createDatabundles'])->name('create.databundles');
Route::post('store/databundles', [\App\Http\Controllers\Admin\AdminController::class, 'storeDatabundles'])->name('store.databundles');
Route::get('charts', [\App\Http\Controllers\Admin\AdminController::class, 'charts']);
Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
Route::post('store/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'store'])->name('store.settings');
Route::get('admin/settings/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'activate']);
Route::get('admin/feedback', [\App\Http\Controllers\Admin\FeedbackRepliesController::class, 'index'])->name('admin.feedback');
Route::get('admin/feedback/unread', [\App\Http\Controllers\Admin\FeedbackRepliesController::class, 'unread'])->name('admin.feedback.unread');
Route::get('admin/feedback/{id}', [\App\Http\Controllers\Admin\FeedbackRepliesController::class, 'view']);
Route::post('store/admin/feedback/', [\App\Http\Controllers\Admin\FeedbackRepliesController::class, 'store'])->name('store.admin.feedbackreplies');

Route::post('admin/store/fund', [\App\Http\Controllers\Admin\AdminController::class, 'adminWalletTopUp'])->name('admin.wallet.topup');
Route::post('admin/celebrity', [\App\Http\Controllers\Admin\AdminController::class, 'adminCelebrity'])->name('admin.celebrity');

///// External Fintech Api 
Route::get('flutterwave/trf/list', [\App\Http\Controllers\Admin\AdminController::class, 'listFlutterwaveTrf']);

///Accounts
Route::get('accounts', [\App\Http\Controllers\Admin\AccountController::class, 'view'])->name('account.view');
Route::post('accounts', [\App\Http\Controllers\Admin\AccountController::class, 'store'])->name('account.store');

///Points
// Route::get('admin/points', [App\Http\Controllers\Admin\PointController::class, 'index'])->name('admin.points'); 
// Route::get('admin/points/redeemed', [App\Http\Controllers\Admin\PointController::class, 'redeemed'])->name('admin.points.redeemed'); 
// Route::post('points', [App\Http\Controllers\Admin\PointController::class, 'store'])->name('points'); 

Route::resource('preferences', PreferenceController::class);

Route::resource('conversions', ConversionRateController::class);

Route::get('change/completed/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'changeCompleted']);
Route::get('priotize/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'priotize']);
Route::get('audit/trail', [\App\Http\Controllers\Admin\AuditTrailController::class, 'index']);


//Campaign metrics
Route::get('admin/campaign/metrics', [\App\Http\Controllers\Admin\AdminController::class, 'campaignMetrics']);
Route::get('admin/campaign/disputes', [\App\Http\Controllers\Admin\AdminController::class, 'campaignDisputes']);
Route::get('admin/campaign/disputes/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'campaignDisputesView']);
Route::post('admin/campaign/disputes/decision', [\App\Http\Controllers\Admin\AdminController::class, 'campaignDisputesDecision'])->name('dispute.decision');

Route::get('admin/blacklist/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'blacklist']);
Route::post('admin/switch/wallet', [\App\Http\Controllers\Admin\AdminController::class, 'switch']);

//User Activity
Route::get('user/tracker', [\App\Http\Controllers\Admin\AdminController::class, 'userlocation'])->name('user.tracker');
Route::get('admin/dashboard/api', [\App\Http\Controllers\HomeController::class, 'adminApi']);
Route::get('admin/dashboard/api/default', [\App\Http\Controllers\HomeController::class, 'adminApiDefault']);
Route::get('test', [\App\Http\Controllers\Admin\AdminController::class, 'test']);

//Banner Ad
Route::get('admin/banner/list', [\App\Http\Controllers\Admin\BannerController::class, 'index']);
Route::get('admin/banner/activate/{id}', [\App\Http\Controllers\Admin\BannerController::class, 'activateBanner']);
Route::resource('admin/safelock', AdminSafeLockController::class);
// Route::get('admin/safelock/{id}', [\App\Http\Controllers\Admin\SafeLockController::class, 'redeemSafeLock']);

//update users account details

Route::post('admin/update/account/details', [\App\Http\Controllers\Admin\AdminController::class, 'updateUserAccountDetails'])->name('admin.update.account.details');
Route::get('admin/virtual/list', [\App\Http\Controllers\Admin\AdminController::class, 'virtualAccountList']);
Route::get('reactivate/virtual/account/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'reactivateVA']);
Route::get('remove/virtual/account/{id}', [\App\Http\Controllers\Admin\AdminController::class, 'removeVirtualAccount']);

//Knowledge base
Route::get('admin/knowledgebase', [\App\Http\Controllers\KnowledgeBaseController::class, 'adminList']);
///Partnerships
Route::get('admin/partner', [\App\Http\Controllers\Admin\PartnershipController::class, 'list']);

Route::get('admin/finger', [\App\Http\Controllers\Admin\FastestFingerController::class, 'showPool'])->name('show.pool');
Route::get('admin/random/selection', [\App\Http\Controllers\Admin\FastestFingerController::class, 'randomSelection']);
