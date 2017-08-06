<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return redirect('/admin/login'); })->name('home');
Auth::routes();
Route::get('/email/activation/{token}', 'Auth\ActivateEmailController@activate')->name('email.activate');

Route::get('/blank', 'Admin\DashboardController@blank')->name('blank');
Route::get('/manual', 'Admin\DashboardController@manual')->name('manual');
Route::get('/test', 'Admin\DashboardController@test');
Route::get('/materials', 'Admin\DashboardController@materials')->name('materials');
Route::get('/form/create', 'Admin\DashboardController@createForm')->name('form.create');
Route::post('/form', 'Admin\DashboardController@storeForm')->name('form.store');

Route::prefix('vote')->group(function () {
  Route::get('/', 'Admin\DashboardController@vote')->name('vote');
  Route::post('/','Admin\DashboardController@voteStore')->name('vote.store');
});

Route::prefix('dashboard')->group(function () {
  Route::get('/', 'Admin\DashboardController@dashboard')->name('dashboard');
  Route::get('/count', 'Admin\DashboardController@count')->name('dashboard.count');
  Route::get('/data', 'Admin\DashboardController@data')->name('dashboard.data');
});


Route::prefix('statistic')->as('statistics.')->group(function () {
  Route::get('volunteer', 'Admin\StatisticController@volunteer')->name('volunteer');
  Route::get('volunteer/messages', 'Admin\StatisticController@volunteersAndMessages')->name('volunteer.messages');

  Route::get('faculty', 'Admin\StatisticController@faculty')->name('faculty');

  Route::get('social/facebook', 'Admin\StatisticController@facebook')->name('facebook');
  Route::get('social/facebook/{id}', 'Admin\StatisticController@facebookPost')->name('facebook.post');

  Route::prefix('website')->group(function () {
    Route::get('/', 'Admin\StatisticController@website')->name('website');
    Route::get('/visitors', 'Admin\StatisticController@websiteVisitors')->name('website.visitors');
    Route::get('/active', 'Admin\StatisticController@websiteActive')->name('website.active');
  });


  Route::get('child', 'Admin\StatisticController@child')->name('child');
  Route::get('child/department', 'Admin\StatisticController@childDepartment' )->name('child.department');

  Route::prefix('blood')->group(function () {
    Route::get('/', 'Admin\StatisticController@blood')->name('blood');
    Route::get('/rh', 'Admin\StatisticController@bloodRh' )->name('blood.rh');
    Route::get('/type', 'Admin\StatisticController@bloodType' )->name('blood.type');
  });


  Route::get('user', 'Admin\StatisticController@user')->name('user');
  Route::get('user/horoscope', 'Admin\StatisticController@userHoroscope' )->name('user.horoscope');

  Route::get('children/count/general', 'Admin\StatisticController@children_by_general')->name('children.count.general');
  Route::get('children/count/faculty/{id}', 'Admin\StatisticController@children_by_faculty')->name('children.count.faculty');
});

Route::resource('emailsample', 'Admin\EmailSampleController');

Route::prefix('post')->as('post.')->group(function () {
  Route::get('/data', 'Admin\PostController@indexData')->name('index.data');
  Route::get('unapprovedCount', 'Admin\PostController@unapprovedCount')->name('unapprovedCount');
  Route::post('approve', 'Admin\PostController@approve')->name('approve');
});
Route::resource('post', 'Admin\PostController');


Route::prefix('user')->as('user.')->group(function () {
  Route::prefix('{id}')->group(function () {
    Route::get('children', 'Admin\UserController@children')->name('children');
    Route::get('children/data', 'Admin\UserController@childrenData')->name('children.data');
  });
  Route::get('/data', 'Admin\UserController@indexData')->name('index.data');
  Route::post('approve', 'Admin\UserController@approve')->name('approve');
});
Route::resource('user', 'Admin\UserController');

Route::prefix('child')->as('child.')->group(function () {
  Route::get('/data', 'Admin\ChildController@indexData')->name('index.data');
  Route::prefix('{id}')->group(function () {
    Route::put('volunteer', 'Admin\ChildController@volunteered')->name('volunteered');
    Route::get('chats', 'Admin\ChildController@chats')->name('chats');
    Route::get('chat/{chatID}', 'Admin\ChildController@chat')->name('chat');
    Route::get('chats/opens', 'Admin\ChildController@chatsOpens')->name('chats.opens');
  });

});
Route::resource('child', 'Admin\ChildController');
Route::resource('diagnosis', 'Admin\DiagnosisController');
Route::resource('department', 'Admin\DepartmentController');

Route::prefix('faculty')->as('faculty.')->group(function () {
  Route::prefix('{id}')->group(function () {
    Route::get('messages', 'Admin\FacultyController@messages')->name('messages');
    Route::get('messages/unanswered', 'Admin\FacultyController@messagesUnanswered')->name('messages.unanswered');
    Route::get('children', 'Admin\FacultyController@children')->name('children');
    Route::get('children/data', 'Admin\FacultyController@childrenData')->name('children.data');
    Route::get('posts', 'Admin\FacultyController@posts')->name('posts');
    Route::get('posts/data', 'Admin\FacultyController@postsData')->name('posts.data');
    Route::get('posts/unapproved', 'Admin\FacultyController@postsUnapproved')->name('posts.unapproved');
    Route::get('posts/unapproved/data', 'Admin\FacultyController@postsUnapprovedData')->name('posts.unapproved.data');
    Route::get('posts/unapproved/count', 'Admin\FacultyController@postsUnapprovedCount')->name('posts.unapproved.count');
    Route::get('profiles', 'Admin\FacultyController@profiles')->name('profiles');
    Route::get('users', 'Admin\FacultyController@users')->name('users');
    Route::get('users/data', 'Admin\FacultyController@usersData')->name('users.data');
    Route::get('users/unapproved', 'Admin\FacultyController@unapproved')->name('users.unapproved');
    Route::get('users/unapproved/data', 'Admin\FacultyController@unapprovedData')->name('users.unapproved.data');
    Route::get('users/unapproved/count', 'Admin\FacultyController@unapprovedCount')->name('users.unapproved.count');
    Route::get('sendmail', 'Admin\FacultyController@createMail')->name('mail.create');
    Route::post('sendmail', 'Admin\FacultyController@sendMail')->name('mail.send');
  });
  Route::get('cities', 'Admin\FacultyController@cities')->name('cities');
  Route::get('city/{code}', 'Admin\FacultyController@city')->name('city');
});
Route::resource('faculty', 'Admin\FacultyController');

Route::prefix('chat')->as('chat.')->group(function () {
  Route::prefix('{id}')->group(function () {
    Route::put('close', 'Admin\ChatController@close')->name('close');
  });
});
Route::resource('chat', 'Admin\ChatController');

Route::prefix('message')->as('message.')->group(function () {
  Route::prefix('{id}')->group(function () {
    Route::put('answered', 'Admin\MessageController@answered')->name('answered');
  });
});
Route::resource('message', 'Admin\MessageController');


Route::prefix('volunteer')->as('volunteer.')->group(function () {
  Route::get('unanswered', 'Admin\VolunteerController@unanswered')->name('unanswered');
  Route::post('unanswered', 'Admin\VolunteerController@childUnanswered')->name('unanswered');
  Route::get('data', 'Admin\VolunteerController@indexData')->name('index.data');
});
Route::resource('volunteer', 'Admin\VolunteerController');

Route::post('/process', 'Admin\ChildController@createProcess')->name('process.store');

Route::prefix('blood')->as('blood.')->group(function () {
  Route::get('/people', 'Admin\BloodController@editPeople')->name('people.edit');
  Route::post('/people', 'Admin\BloodController@updatePeople')->name('people.update');
  Route::get('/sms', 'Admin\BloodController@showSMS')->name('sms.show');
  Route::get('/sms/balance', 'Admin\BloodController@checkBalance')->name('sms.balance');
  Route::post('/sms/preview', 'Admin\BloodController@previewSMS')->name('sms.preview');
  Route::post('/sms/test', 'Admin\BloodController@testSMS')->name('sms.test');
  Route::post('/sms', 'Admin\BloodController@sendSMS')->name('sms.send');
});
Route::resource('blood', 'Admin\BloodController');

Route::prefix('new')->as('new.')->group(function () {
  Route::get('/channel', 'Admin\NewController@channelsData')->name('channels.data');
});
Route::resource('new', 'Admin\NewController');

Route::resource('sponsor', 'Admin\SponsorController');

Route::prefix('testimonial')->as('testimonial.')->group(function () {
  Route::get('/data', 'Admin\TestimonialController@indexData')->name('index.data');
});
Route::resource('testimonial', 'Admin\TestimonialController');

Route::resource('mobile-notification', 'Admin\MobileNotificationController');
Route::post('mobile-notification/{id}/send', 'Admin\MobileNotificationController@send')->name('mobile-notification.send');

Route::resource('blog', 'Admin\BlogController');

Route::prefix('log')->middleware('auth')->group(function() {
  Route::get('/', [ 'as'        => 'log-viewer::dashboard', 'uses'   => 'Admin\LogController@index',]);
  Route::get('/lists', [ 'as'   => 'log-viewer::logs.list', 'uses'   => 'Admin\LogController@listLogs',]);
  Route::delete('delete', ['as' => 'log-viewer::logs.delete', 'uses' => 'Admin\LogController@delete',]);
  Route::group([ 'prefix'    => '{date}',], function() {
    Route::get('/', ['as'        => 'log-viewer::logs.show', 'uses'     => 'Admin\LogController@show',]);
    Route::get('download', ['as' => 'log-viewer::logs.download', 'uses' => 'Admin\LogController@download',]);
    Route::get('{level}', ['as'  => 'log-viewer::logs.filter', 'uses'   => 'Admin\LogController@showByLevel',]);
  });
});
