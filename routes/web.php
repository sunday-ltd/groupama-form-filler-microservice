<?php
	use Fpdf\Fpdf;
	use Laravel\Lumen\Http\Request;

	$router->get('/phpinfo', function() {
		phpinfo();
	});
	$router->get('render/zalogkotelezettinyilatkozat', ['as' => 'render_zalog_get', 'uses' => 'ZalogRenderController@render']);
	$router->post('render/zalogkotelezettinyilatkozat', ['as' => 'render_zalog_post', 'uses' => 'ZalogRenderController@render']);
