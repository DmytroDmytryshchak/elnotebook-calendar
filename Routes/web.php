<?php

// ── Auth ──────────────────────────────────────────────────────────────────────
$router->get('/login',     'AuthController@showLogin');
$router->post('/login',    'AuthController@login');
$router->get('/register',  'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->post('/logout',   'AuthController@logout');

// ── Calendar ──────────────────────────────────────────────────────────────────
$router->get('/calendar',  'CalendarController@index');

// ── Events (AJAX) ─────────────────────────────────────────────────────────────
$router->post(  '/events',       'EventController@store');
$router->get(   '/events/{id}',  'EventController@show');
$router->put(   '/events/{id}',  'EventController@update');
$router->delete('/events/{id}',  'EventController@destroy');

// ── Notifications (AJAX) ──────────────────────────────────────────────────────
$router->get( '/notifications/pending',       'NotificationController@pending');
$router->post('/notifications/seen-all',      'NotificationController@markAllSeen');
$router->post('/notifications/{id}/seen',     'NotificationController@markSeen');

// ── Root ──────────────────────────────────────────────────────────────────────
$router->get('/', 'AuthController@showLogin');
