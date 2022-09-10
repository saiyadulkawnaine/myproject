<?php

return [
	'mode'                  => 'utf-8',
	'format'                => 'A4',
	'author'                => '',
	'subject'               => '',
	'keywords'              => '',
	'creator'               => 'Laravel Pdf',
	'display_mode'          => 'fullpage',
	//'tempDir'               => base_path('../temp/'),//original
	'tempDir'               => public_path('temp/'),//modify by me
	'font_path' => base_path('resources/fonts/'),
	'font_data' => [
		'siyambangla' => [
			'R'  => 'Siyamrupali.ttf',    // regular font
			//'B'  => 'ExampleFont-Bold.ttf',       // optional: bold font
			//'I'  => 'ExampleFont-Italic.ttf',     // optional: italic font
			//'BI' => 'ExampleFont-Bold-Italic.ttf' // optional: bold-italic font
			'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
			'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
		]
		// ...add as many as you want.
	]
];
