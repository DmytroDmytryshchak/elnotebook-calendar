<?php
// маршрути додатку

// Auth routes
$router->get('/login',    'AuthController@showLogin');
$router->post('/login',   'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register','AuthController@register');
$router->post('/logout',  'AuthController@logout');

// Event routes (AJAX JSON) ──────────────────────────────────────────────────
$router->post(  '/events',       'EventController@store');
$router->get(   '/events/{id}',  'EventController@show');
$router->put(   '/events/{id}',  'EventController@update');
$router->delete('/events/{id}',  'EventController@destroy');

// ── Root → login ──────────────────────────────────────────────────────────────
$router->get('/', 'AuthController@showLogin');