<?php

use Illuminate\Support\Facades\Route;

// Temporary landing page while the public routes of Task 5 are unbuilt. Task 5
// replaces the view; the route name stays. Until then, `/` serves the design
// system instead of a bare 404.
Route::view('/', 'coming-soon')->name('home');

// Public routes will be added in Task 5.
