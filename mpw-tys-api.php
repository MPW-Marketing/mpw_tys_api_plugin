<?php

/**
 * Plugin Name:       MPW To Your Success API integration
 * Description:       Provide shgortcodes for working with the To Your Success API
 * Version:           0.1.0
 * Author:            dmm
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mpw-tys-api
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function get_tys_api ( $atts ) {
  extract(shortcode_atts(array(
		'client_id' 		=> '',
		'client_location' 		=> '',
	  	'client_department'   	=> '',
		), $atts));

if ( $client_id == '' ) {
  return;
}
// set up basic info
$api_url = 'https://api.toyoursuccess.com/1/reviews/';
$client_query_key = '?client=';
$location_query_key = '&location=';
$department_query_key = '&department=';

//set up url

$url = $api_url . $client_query_key . $client_id;
//use wordpress http api to get results;

$response = wp_remote_get( $url, );

print_r($response);

}

add_shortcode('tys_api' , 'get_tys_api');
