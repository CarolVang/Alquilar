<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('registro', 'Auth::registro');
$routes->post('registro', 'Auth::attemptRegistro');
$routes->get('logout', 'Auth::logout');
$routes->get('publicar-herramienta', 'Herramientas::publicar');
$routes->get('mis-herramientas', 'Herramientas::mis');