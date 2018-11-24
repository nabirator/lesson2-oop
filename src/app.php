<?php
use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();
$routes->add('start', new Routing\Route('/start', array(
    '_controller' => 'Gymkhana\Controller\GymkhanaController::start',
)));
$routes->add('finish', new Routing\Route('/finish', array(
    '_controller' => 'Gymkhana\Controller\GymkhanaController::finish',
)));

return $routes;
