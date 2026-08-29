<?php

use Illuminate\Support\Facades\Route;

// Task 4 bunu PageController@home ile değiştirecek.
Route::view('/', 'public.home')->name('home');
