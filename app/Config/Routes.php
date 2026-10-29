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
$routes->get('mis-herramientas/editar/(:num)', 'Herramientas::editar/$1');
$routes->post('mis-herramientas/editar/(:num)', 'Herramientas::actualizar/$1');
$routes->post('mis-herramientas/cambiar-estado/(:num)', 'Herramientas::cambiarEstado/$1');
$routes->get('catalogo', 'Catalogo::index'); // alias historico de "/", se mantiene por los redirect() que ya usan esta ruta

$routes->get('perfil', 'Perfil::index');

$routes->get('detalle/(:num)', 'Herramientas::detalle/$1');

$routes->get('confirmacion', 'Reserva::confirmacion');
$routes->post('reservar', 'Reserva::confirmar');

$routes->get('mis-reservas', 'Alquileres::misReservas');
$routes->get('mis-alquileres', 'Alquileres::misAlquileres');
$routes->post('alquileres/cancelar/(:num)', 'Alquileres::cancelar/$1');
$routes->post('alquileres/avanzar-estado/(:num)', 'Alquileres::avanzarEstado/$1');

$routes->get('pago/(:num)', 'Pago::index/$1');

// La palabra "chat" no puede aparecer en ninguna URL de este proyecto:
// InfinityFree la bloquea con un 403 (ver el comentario en Mensajes.php).
$routes->get('mensajes/(:num)', 'Mensajes::index/$1');
$routes->get('mensajes/(:num)/listar', 'Mensajes::listar/$1');
$routes->post('mensajes/(:num)/enviar', 'Mensajes::enviar/$1');

