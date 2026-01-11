<?php
session_start();

// Load app files
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/core.php';
require_once __DIR__ . '/../app/models.php';
require_once __DIR__ . '/../app/controllers.php';
require_once __DIR__ . '/../app/router.php';

// Define routes
$router = new Router;

// Public routes
$router->get('/', 'home');
$router->get('/login', 'login');
$router->post('/login', 'login');
$router->get('/register', 'register');
$router->post('/register', 'register');
$router->get('/logout', 'logout');

// Protected routes
$router->get('/dashboard', 'dashboard');
$router->get('/booking/{code}', 'bookingDetail');
$router->get('/profile', 'profile');
$router->post('/profile/update', 'profileUpdate');
$router->get('/rooms', 'rooms');
$router->get('/schedule/{id}', 'schedule');
$router->post('/book', 'book');
$router->post('/cancel/{code}', 'cancelBooking');

// Run
$router->run();
