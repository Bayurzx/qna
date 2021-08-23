<?php
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\AnswersController;
use App\Http\Controllers\AcceptedAnswerController;

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

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('questions', QuestionsController::class)->except('show');

Route::resource('questions.answers', AnswersController::class)->only('store', 'edit', 'update', 'destroy');
// or
// Route::resource('questions.answers', AnswersController::class)->except('index', 'create', 'show');

Route::get('/questions/{slug}', [QuestionsController::class, 'show'])->name('questions.show');
// Route::get('questions/{slug}', "QuestionsController@show")->name('questions.show');

Route::post('/answers/{answer}/accept', AcceptedAnswerController::class)->name('answers.accept');