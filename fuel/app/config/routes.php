<?php
return array(
	'_root_'  => 'welcome/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route
	'dashboard' => 'dashboard/index',
	'login' => 'auth/login',
	
	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);
