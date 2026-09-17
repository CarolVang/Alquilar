<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('login', 'Auth::login');
$routes->get('registro', 'Auth::registro');
$routes->get('publicar-herramienta', 'Herramientas::publicar');
$routes->get('mis-herramientas', 'Herramientas::mis');