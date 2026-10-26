<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Catalogo::index');

$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attemptLogin');

$routes->get('registro', 'Auth::registro');
$routes->post('registro', 'Auth::attemptRegistro');

$routes->get('logout', 'Auth::logout');

$routes->get('publicar-herramienta', 'Herramientas::publicar');
$routes->post('publicar-herramienta', 'Herramientas::crear');

$routes->get('mis-herramientas', 'Herramientas::mis');
$routes->get('catalogo', 'Catalogo::index');
$routes->get('confirmacion', 'Reserva::confirmacion');
   $routes->get('pago', 'Pago::index');

$routes->get('detalle', 'Herramientas::detalle');
$routes->get('mis-reservas', 'Alquileres::misReservas');
$routes->get('mis-alquileres', 'Alquileres::misAlquileres');
