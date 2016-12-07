#!/usr/bin/php
<?php

$CONSUMER_KEY = 'bIHyzYcRoVRkVOPPumBEiA';
$CONSUMER_SECRET = 'aWQ0KpLc-yb8GE1CCX7ea5RUjBg';
$TOKEN = 'NAs3ZljezJoWEOTTt1ObXYVpSw1IJGaF';
$TOKEN_SECRET = '6d_Bhislpna35_XCryc_ZyQAlb4';

$API_HOST = 'api.yelp.com';
$DEFAULT_TERM = '';
$DEFAULT_LOCATION = 'Madrid';
$SEARCH_LIMIT = 10000;
$SEARCH_PATH = '/v2/search/';
$BUSINESS_PATH = '/v2/business/';
$OFFSET = 20;
$SORT_MODE = 0;


/** 
 * Makes a request to the Yelp API and returns the response
 * 
 * @param    $host    The domain host of the API 
 * @param    $path    The path of the APi after the domain
 * @return   The JSON response from the request      
 */
function request_address_details_gapi($address)
{
	$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&sensor=false"
    $ch = curl_init($url);    
    $data = curl_exec($ch);
    curl_close($ch);
    
	$response = json_decode($data);
	foreach($response->results as $add)
	{
		echo "lat: ".$add->geometry->lat."\nlng: ".$add->geometry->lng."";
	}
}

/**
 * Query the Search API by a search term and location 
 * 
 * @param    $term        The search term passed to the API 
 * @param    $location    The search location passed to the API 
 * @return   The JSON response from the request 
 */
function search($term, $location, $offset) {
    $url_params = array();
    
    //$url_params['term'] = $term ?: $GLOBALS['DEFAULT_TERM'];
    $url_params['location'] = $location?: $GLOBALS['DEFAULT_LOCATION'];
    //$url_params['limit'] = $GLOBALS['SEARCH_LIMIT'];
	$url_params['offset'] = $offset; 
	$url_params['sort'] = $GLOBALS['SORT_MODE'];
    $search_path = $GLOBALS['SEARCH_PATH'] . "?" . http_build_query($url_params);
	return request($GLOBALS['API_HOST'], $search_path);
}

/**
 * Query the Business API by business_id
 * 
 * @param    $business_id    The ID of the business to query
 * @return   The JSON response from the request 
 */
function get_business($business_id) {
    $business_path = $GLOBALS['BUSINESS_PATH'] . $business_id;
    
    return request($GLOBALS['API_HOST'], $business_path);
}

/**
 * Queries the API by the input values from the user 
 * 
 * @param    $term        The search term to query
 * @param    $location    The location of the business to query
 */
function query_api($term, $location) {     
    for($i = 20; $i<=1000; $i+=20)
	{
		$response = json_decode(search($term, $location, $i));
		foreach($response->businesses as $bus)
		{
			echo $bus->name.",".$bus->phone.",";
			foreach($bus->categories as $catg)
			{
				foreach($catg as $cat)
				{				
					echo $cat." ";
				}
			}
			echo ",".$bus->display_phone.",";
			foreach($bus->location->display_address as $dir_data)
			{
				echo $dir_data.",";
			}
			echo "\n";
		}
		//$response = search($term, $location, $i);
		//print "$response\n";
    }
	//$business_id = $response->businesses[0]->id;
    
   /* print sprintf(
        "%d businesses found, querying business info for the top result \"%s\"\n\n",         
        count($response->businesses),
        $business_id
    );*/
    
    //$response = get_business($business_id);
    
    print sprintf("Result for business \"%s\" found:\n", $business_id);
    print "$response\n";
}

?>
