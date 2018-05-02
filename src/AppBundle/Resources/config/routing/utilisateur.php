<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();


$collection->add('_show', new Route(
    '/show',
    array('_controller' => 'AppBundle:Utilisateur:show'),
    array(),
    array(),
    '',
    array(),
    array('GET')
));

$collection->add('_new', new Route(
    '/inscription',
    array('_controller' => 'AppBundle:Utilisateur:new'),
    array(),
    array(),
    '',
    array(),
    array('GET', 'POST')
));

$collection->add('_edit', new Route(
    '/{id}/edit',
    array('_controller' => 'AppBundle:Utilisateur:edit'),
    array(),
    array(),
    '',
    array(),
    array('GET', 'POST')
));

$collection->add('_delete', new Route(
    '/{id}/delete',
    array('_controller' => 'AppBundle:Utilisateur:delete'),
    array(),
    array(),
    '',
    array(),
    array('GET', 'DELETE')
));

return $collection;
