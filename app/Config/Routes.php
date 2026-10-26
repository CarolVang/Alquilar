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
$routes->post('publicar-herramienta', 'Herramientas::crear');

$routes->get('mis-herramientas', 'Herramientas::mis');
$routes->get('catalogo', 'Catalogo::index');

$routes->get('detalle/(:num)', 'Herramientas::detalle/$1');

$routes->get('confirmacion', 'Reserva::confirmacion');
$routes->post('reservar', 'Reserva::confirmar');

$routes->get('mis-reservas', 'Alquileres::misReservas');
$routes->get('mis-alquileres', 'Alquileres::misAlquileres');
$routes->post('alquileres/cancelar/(:num)', 'Alquileres::cancelar/$1');
$routes->post('alquileres/avanzar-estado/(:num)', 'Alquileres::avanzarEstado/$1');

$routes->get('pago/(:num)', 'Pago::index/$1');

