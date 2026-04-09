<?php

use Illuminate\Support\Facades\Route;

// Halaman Landing Page (Beranda)
Route::view('/', 'welcome')->name('home');

// Auth Routes
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');
    Route::view('/pengguna', 'admin.pengguna')->name('admin.pengguna');
    Route::view('/menu', 'admin.menu')->name('admin.menu');
    Route::view('/distribusi', 'admin.distribusi')->name('admin.distribusi');
    Route::view('/laporan', 'admin.laporan')->name('admin.laporan');
});

// User Routes
Route::prefix('user')->group(function () {
    Route::view('/dashboard', 'user.dashboard')->name('user.dashboard');
    Route::view('/pelacak', 'user.pelacak')->name('user.pelacak');
    Route::view('/menu', 'user.menu')->name('user.menu');
    Route::view('/riwayat', 'user.history')->name('user.history');
    Route::view('/pelacak', 'user.tracker')->name('user.tracker');
});