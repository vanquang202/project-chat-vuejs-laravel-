<?php

use Illuminate\Support\Facades\Route;

// Auth::routes();
// Route::get('login', function () {
// });
Route::redirect('/home', '/chat');
Route::redirect('/', '/chat');

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/logout', function () {
    auth()->logout();
    return redirect('login');
});

Route::get('/run-optimize', function () {
    \Illuminate\Support\Facades\Artisan::call('  optimize');
});
Route::get('/run-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('  cache:clear');
    \Illuminate\Support\Facades\Artisan::call('  view:clear');
});

// Route::get('test', function () {
//     $comment = \App\Models\Comment::with('comment')->get()->toArray();
//     dd($comment);
// });

// Route::view('uploadS3','uploads3');
// Route::get('disk-s3' , function () {
//     // $disk = Storage::disk('s3') -> get('files/hihi.png');
//     $disk = Storage::disk('s3') -> url('files/hihi.png');
//     dd($disk);
// });
// Route::get('dowload-s3' , function ()
// {
//     return Storage::disk('s3') -> download('files/hihi.png');
// });
// Route::get('del-s3' , function () {
//     Storage::disk('s3') -> delete('files/hihi.png');
// });
// Route::post('uploadS3-load' , function () {
//     // $nameFile = request('file') -> getCLientOriginalName();
//     $nameFile = 'hihi.png';
//     // Storage::disk('s3')->put('files' , request('file'));
//     $links = request('file') -> storeAs('files/user1' , $nameFile , 's3');
//     dd($links);
// })-> name('upload.s3');


// Route::middleware(['authAccount'])->group(function () {

Route::group([
    'middleware' => 'loginUser'
], function () {
    Route::get('/{any?}', function () {
        return view('home');
    });

    Route::get('/{any?}/{id}', function ($id) {
        return view('home');
    });
    Route::get('/{any?}/{id}/{name}', function ($id, $name) {
        return view('home');
    });
});
// });