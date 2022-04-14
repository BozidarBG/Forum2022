<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserTopicController;
use App\Http\Controllers\UserCommentController;
use App\Http\Controllers\UserFavouritesController;
use App\Http\Controllers\UserLikeController;
use App\Http\Controllers\UserComplaintController;


use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminCategoriesController;
use App\Http\Controllers\AdminAppSettingController;
use App\Http\Controllers\AdminBannedUsersController;
use App\Http\Controllers\AdminCommentController;
use App\Http\Controllers\AdminComplaintController;
use App\Http\Controllers\AdminTagController;
use App\Http\Controllers\AdminTopicController;
use App\Http\Controllers\AdminUserController;





require __DIR__.'/auth.php';

Route::get('/', [PagesController::class, 'home'])->name('home');

//Route::get('/categories', [PagesController::class, 'allCategories'])->name('categories.all');
Route::get('/topics/category/{slug}', [PagesController::class, 'topicsByCategory'])->name('users.topics.by.category');
Route::get('/topics/tag/{slug}', [PagesController::class, 'topicsByTag'])->name('users.topics.by.tag');
Route::get('/single-topic/{slug}', [PagesController::class, 'showTopic'])->name('users.show.topic');
//Route::get('/topics-by-user/{slug}', [PagesController::class, 'topicsByUser'])->name('topics.by.user');
Route::get('/username/{slug}', [PagesController::class, 'seeUser'])->name('users.see.user');

Route::get('/search', [PagesController::class, 'search'])->name('search');
Route::get('/registration-completed', [PagesController::class, 'registrationCompleted'])->name('users.waiting.email.confirmation');


//Auth::routes();
Route::get('/banned-user', [ProfileController::class, 'redirectBanned'])->name('banned.user')->middleware(['auth']);
Route::get('/after-registration', function(){
    return view('user/after_registration');
})->name('after.registration')->middleware(['guest']);

//AUTHENTICATED USERS ROUTES
Route::middleware(['auth', 'XssSanitizer', 'banned' /*, 'verified'*/])->group(function (){

    Route::get('/my-profile', [ProfileController::class, 'myProfile'])->name('users.my-profile');
    Route::post('/update-profile-avatar', [ProfileController::class, 'updateAvatar'])->name('users.update.avatar');
    Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('users.update.password');
    Route::post('/update-profile-about', [ProfileController::class, 'updateAbout'])->name('users.update.about');
    Route::post('/delete-profile', [ProfileController::class, 'deleteProfile'])->name('users.delete.profile');
    Route::post('/delete-profile-avatar', [ProfileController::class, 'deleteProfileAvatar'])->name('users.delete.profile.avatar');


    Route::get('/create-new-topic', [UserTopicController::class, 'create'])->name('users.create-new-topic');
    Route::post('/store-new-topic', [UserTopicController::class, 'store'])->name('users.topic.store');
    Route::post('/edit-topic/{topic}', [UserTopicController::class, 'update'])->name('users.topic.update');
    Route::post('/comments', [UserCommentController::class, 'store'])->name('users.comment.store');

    Route::post('/complain', [UserComplaintController::class, 'store'])->name('users.complain.store');
    Route::post('/like', [UserLikeController::class, 'like'])->name('user.like');
    Route::post('/fav',  [UserFavouritesController::class, 'toggleFavourite'])->name('favourite.toggle');


    Route::get('/messages-count', [ChatController::class, 'getUnreadMessagesCount']);
    Route::get('/messenger', [ChatController::class, 'showMessengerPageWithContacts'])->name('show.messenger');
    Route::post('/find-user', [ChatController::class, 'findUser'])->name('find.user');

    Route::get('/get-contacts', [ChatController::class, 'showMessengerContacts']);
    Route::get('/get-messages/{id}', [ChatController::class, 'getMessages'])->name('get.messages');
    Route::post('/save-message', [ChatController::class, 'savePrivateMessage']);
});

//ADMIN ROUTES
Route::middleware(['admin','XssSanitizer'])->prefix('admin')->group(function (){
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/categories', [AdminCategoriesController::class, 'index'])->name('admin.categories');
    Route::post('/categories-store', [AdminCategoriesController::class, 'store'])->name('admin.categories.store');
    Route::post('/category-update/{id}', [AdminCategoriesController::class, 'update'])->name('admin.categories.update');
    Route::post('/category-destroy/{id}', [AdminCategoriesController::class, 'destroy'])->name('admin.categories.destroy');

    Route::get('/tags', [AdminTagController::class, 'index'])->name('admin.tags');
    Route::post('/tags-store', [AdminTagController::class, 'store'])->name('admin.tags.store');
    Route::post('/tag-update/{id}', [AdminTagController::class, 'update'])->name('admin.tags.update');
    Route::post('/tag-destroy/{id}', [AdminTagController::class, 'destroy'])->name('admin.tags.destroy');

    Route::get('/banned-users', [AdminBannedUsersController::class, 'index'])->name('admin.banned.users');
    //Route::get('/get-banned-users', [AdminBannedUsersController::class, 'getBannedUsers'])->name('admin.get.banned.users');
    Route::post('/ban', [AdminBannedUsersController::class, 'store'])->name('admin.banned.store');
    Route::post('/unban/{user}', [AdminBannedUsersController::class, 'destroy'])->name('admin.banned.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users');
    //Route::get('/get-users', [AdminUsersController::class, 'getUsers'])->name('admin.get.users');
    Route::post('/users', [AdminUserController::class, 'update'])->name('admin.update.users');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('admin.show.users');
    //Route::post('/users-update', [AdminUserController::class, 'update'])->name('admin.edit.user');

    Route::get('/comments', [AdminCommentController::class, 'index'])->name('admin.comments');
    Route::post('/comment-destroy/{comment}', [AdminCommentController::class, 'destroy'])->name('admin.comment.destroy');

    Route::get('/complaints', [AdminComplaintController::class, 'index'])->name('admin.complaints');
    Route::post('/complaint-delete/{complaint}', [AdminComplaintController::class, 'destroy'])->name('admin.complaint.destroy');

    Route::get('/topics', [AdminTopicController::class, 'index'])->name('admin.topics');
    Route::get('/topics-trashed', [AdminTopicController::class, 'onlyTrashed'])->name('admin.topics.trashed');
    Route::get('/topic-show/{topic}', [AdminTopicController::class, 'show'])->name('admin.show.topic');
    Route::post('/topic-update/{topic}', [AdminTopicController::class, 'update'])->name('admin.topic.update');
    Route::post('/topic-destroy/{topic}', [AdminTopicController::class, 'destroy'])->name('admin.topic.destroy');
    Route::post('/topic-restore/{id}', [AdminTopicController::class, 'restore'])->name('admin.topic.restore');
    Route::post('/topic-destroy-trashed/{id}', [AdminTopicController::class, 'forceDelete'])->name('admin.topic.destroy.trashed');

    Route::get('/app-settings', [AdminAppSettingController::class, 'index'])->name('admin.app.settings');
    Route::post('/app-settings-store', [AdminAppSettingController::class, 'store'])->name('admin.app.settings.store');
    Route::get('/app-settings-edit/{id}', [AdminAppSettingController::class, 'edit'])->name('admin.app.settings.edit');
    Route::post('/app-settings-update/{id}', [AdminAppSettingController::class, 'update'])->name('admin.app.settings.update');
    Route::post('/app-settings-destroy/{id}', [AdminAppSettingController::class, 'destroy'])->name('admin.app.settings.destroy');
});
