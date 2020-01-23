<?php

register_meta( 'post', 'bgseo_title', array(
	'type' => 'string',
	'single' => true,
	'show_in_rest' => true,
) );
register_meta( 'post', 'bgseo_description', array(
	'type' => 'string',
	'single' => true,
	'show_in_rest' => true,
) );
register_meta( 'post', 'bgseo_robots_index', array(
	'type' => 'string',
	'single' => true,
	'show_in_rest' => true,
) );
register_meta( 'post', 'bgseo_robots_follow', array(
	'type' => 'string',
	'single' => true,
	'show_in_rest' => true,
) );