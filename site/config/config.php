<?php

return [

	// setting up
	'panel' =>[
		'css' => 'assets/css/panel.css'
	],

	// development
	'debug'  => false,

	// languages
	'languages' => true,
	'languages.detect' => true,

	// cache
	// https://getkirby.com/docs/guide/cache
	'cache-expires' => 1440,
	'cache.abstract' => true,
	'cache.jsonRequest' => true,
	'cache.worlditems' => true,

	// other configuration
	'thumbs' => [
		'srcsets' => [
			'mini' =>  [ 40, 80, 120],
			'small' =>     [ 80, 120, 240, 360],
			'medium' =>                   [360, 480, 640, 880, 1200],
			'large' =>                              [640, 880, 1200, 1600, 2000],
			'full' =>                               [640, 880, 1200, 1600, 2000, 2500, 3000, 3500],
			'all' =>       [ 80, 120, 240, 360, 480, 640, 880, 1200, 1600, 2000, 2500, 3000, 3500],
		]
	],

	// archive shall render all 404, using the request path as query input
	'error' => 'archive',

	'smartypants' => true,

	'cdn-host' => 	'https://documentary-architecture.fra1.digitaloceanspaces.com',
	'cdn' => 		'https://documentary-architecture.fra1.digitaloceanspaces.com/cda/',
	//				'https://documentary-architecture.fra1.cdn.digitaloceanspaces.com'

];
