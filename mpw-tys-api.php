<?php

/**
 * Plugin Name:       MPW To Your Success API integration
 * Description:       Provide shgortcodes for working with the To Your Success API
 * Version:           0.2.0
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
class tys_api {
function get_tys_api ( $tys_query_string ) {

	if ( false === ( $api_response = get_transient( $tys_query_string ) ) ) { //check if api result is stored in transient

// set up basic info
$api_url = 'https://api.toyoursuccess.com/1/reviews/';


//set up url

$url = $api_url . '?' .  $tys_query_string ;

//use wordpress http api to get results;

$response = wp_remote_get( $url );
$api_reponse =  '';
if( !is_wp_error( $response ) ) { //if not an error store results in transient, expires in 1 hour
	$api_response = json_decode( wp_remote_retrieve_body( $response ), true );
	set_transient( $tys_query_string, $api_response, 1 * HOUR_IN_SECONDS );
}
}

return $api_response;
}	
}




function print_testimonials ( $atts ) {
	  extract(shortcode_atts(array(
		'client_id' 		=> '',
		'client_location' 		=> '',
	  	'client_department'   	=> '',
	  	'limit_reviews'			=> '10',
	  	'minimum_rating'		=> '60'
		), $atts));

if ( $client_id == '' ) {
  return;
}

$tys_data = array();
$tys_data['client'] = $client_id;
$tys_data['limit_reviews'] = $limit_reviews;
$tys_data['minimum_rating'] = $minimum_rating;
$tys_data['location'] = $client_location;
$tys_data['department'] = $client_department;

$tys_query =  http_build_query($tys_data);

$api_response = get_tys_api($tys_query);

$loc = $api_response['reviews']['locations'];
$cont = '';
foreach ($loc as $key => $value) {
	$dep = $loc[$key]['departments'];
	
	foreach ($dep as $dep_key => $dep_value) {
		$dep_reviews = $dep[$dep_key]['reviews'];
		
		foreach ($dep_reviews as $rev_key => $rev_value) {
			$rev_name = $dep_reviews[$rev_key]['name'];
			$rev_city = $dep_reviews[$rev_key]['city'];
			$rev_state = $dep_reviews[$rev_key]['state'];
			$cont .= '<p><strong>"'.$dep_reviews[$rev_key]['comment'].'"</strong><br />';
			$cont .= '<em>' . $dep_reviews[$rev_key]['name'] . ' - ' . $dep_reviews[$rev_key]['city'] . ', ' . $dep_reviews[$rev_key]['state'] . '</em></p><hr />';
		}
	}
}

return $cont;



}

add_shortcode('tys_api' , 'print_testimonials');
