<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/scan', function () {
    return view('scan');
})->name('scan');

Route::get('/map', function () {
    return view('map');
})->name('map');

Route::get('/rates', function () {
    return view('rates');
})->name('rates');

Route::get('/result', function () {
    return view('result');
})->name('result');
