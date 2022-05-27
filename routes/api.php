<?php

use App\Http\Controllers\Api\Follower\FollowerController;
use App\Http\Controllers\Api\Google\GoogleController;
use App\Http\Controllers\Api\Room\RoomController;
use App\Http\Controllers\Api\Storage\StorageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
/** */
Route::post('request-login', [GoogleController::class, 'requestLogin']);

Route::get('event-chat-input/{id}', function ($id) {
    event(new \App\Events\UserInput($id));
});


//Profile
Route::get('get-me', [\App\Http\Controllers\Api\User\UserController::class, 'getMe']);
Route::get('get-you/{id}', [\App\Http\Controllers\Api\User\UserController::class, 'getYou']);

//Follower
Route::get('add-follower/{id}', [FollowerController::class, 'addFollower']);
Route::get('un-follower/{id}', [FollowerController::class, 'unFollower']);
/** */
Route::get('all-user-deloy-me', [\App\Http\Controllers\Api\User\UserController::class, 'getAllUSersDeloyMe']);
Route::match(['get', 'post'], 'all-user-deloy-friend', [\App\Http\Controllers\Api\User\UserController::class, 'getAllUSersDeloyFriend']);
Route::post('add-user-deloy-friend', [\App\Http\Controllers\Api\User\UserController::class, 'addAllUSersDeloyFriend']);
Route::post('add-user-deloy-me', [\App\Http\Controllers\Api\User\UserController::class, 'addAllUSersDeloyMe']);
Route::post('remove-user-deloy-me', [\App\Http\Controllers\Api\User\UserController::class, 'removeAllUSersDeloyMe']);

//Room
Route::post('create-room', [\App\Http\Controllers\Api\Room\RoomController::class, 'createRoom']);
Route::get('get-room-details/{code}', [\App\Http\Controllers\Api\Room\RoomController::class, 'getRoom']);
Route::post('chat-room-send-data', [\App\Http\Controllers\Api\Room\RoomController::class, 'chatRoomSendData']);
Route::post('change-pass-room', [\App\Http\Controllers\Api\Room\RoomController::class, 'changePassRoom']);
Route::post('searh-room', [\App\Http\Controllers\Api\Room\RoomController::class, 'searchRoom']);
Route::post('add-people-room', [\App\Http\Controllers\Api\Room\RoomController::class, 'addPeopleRoom']);
Route::post('remove-room', [\App\Http\Controllers\Api\Room\RoomController::class, 'removeRoom']);
Route::post('view-people-room', [\App\Http\Controllers\Api\Room\RoomController::class, 'viewPeopleRoom']);
Route::post('out-room', [\App\Http\Controllers\Api\Room\RoomController::class, 'outRoom']);
/** */
Route::post('save-me', [\App\Http\Controllers\Api\User\UserController::class, 'setMe']);
Route::resource('user', \App\Http\Controllers\Api\User\UserController::class);
Route::resource('room', \App\Http\Controllers\Api\Room\RoomController::class);
Route::post('file-send-chat', [RoomController::class, 'file_send_chat']);
Route::get('dowload-file', [RoomController::class, 'dowload_file']);

//Post
Route::resource('post',  \App\Http\Controllers\Api\Post\PostController::class);
Route::get('post-me', [\App\Http\Controllers\Api\Post\PostController::class, 'getPostMe']);
Route::get('post-you/{id}', [\App\Http\Controllers\Api\Post\PostController::class, 'getPostYou']);
Route::get('post-increment/{id}', [\App\Http\Controllers\Api\Post\PostController::class, 'increment']);
Route::get('post-decrement/{id}', [\App\Http\Controllers\Api\Post\PostController::class, 'decrement']);

//Seri
Route::resource('seri', \App\Http\Controllers\Api\Seri\SeriController::class);
Route::get('get-seri-me', [\App\Http\Controllers\Api\Seri\SeriController::class, 'getSeriMe']);
Route::get('get-seri-you/{id}', [\App\Http\Controllers\Api\Seri\SeriController::class, 'getSeriYou']);

//Comment
Route::resource('comment', \App\Http\Controllers\Api\Comment\CommentController::class);
Route::get('comment-increment/{id}', [\App\Http\Controllers\Api\Comment\CommentController::class, 'increment']);
Route::get('comment-decrement/{id}', [\App\Http\Controllers\Api\Comment\CommentController::class, 'decrement']);
Route::delete('comment/{id}', [\App\Http\Controllers\Api\Comment\CommentController::class, 'destroy']);
Route::get('comment/{id}', [\App\Http\Controllers\Api\Comment\CommentController::class, 'show']);
Route::put('comment/{id}', [\App\Http\Controllers\Api\Comment\CommentController::class, 'update']);

//Storage
Route::resource('storage', StorageController::class);
Route::post('upload-storage', [StorageController::class, 'upload']);
Route::get('remove-storage/{id}', [StorageController::class, 'remove']);
Route::get('download-storage', [StorageController::class, 'download']);

//Notification
Route::get('notifications', function () {
    try {
        return response()->json([
            'data' => auth()->user()->notifications,
            'count_unread' => count(auth()->user()->unreadNotifications),
            'code' => 200,
        ]);
    } catch (\Throwable $th) {
        return response()->json([
            'code' => 504,
        ]);
    }
});
Route::get('read-notifications', function () {
    try {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json([
            'code' => 200,
        ]);
    } catch (\Throwable $th) {
        return response()->json([
            'code' => 504,
        ]);
    }
});