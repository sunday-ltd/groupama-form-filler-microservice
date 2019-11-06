<?php
use Fpdf\Fpdf;
use Laravel\Lumen\Http\Request;

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->get("render", function (Request $request) use ($router) {
    return $request->get("ad");
});

$router->get('render/zalogkotelezettinyilatkozat', [
    'as' => 'render_zalog', 'uses' => 'ZalogRenderController@render'
]);
