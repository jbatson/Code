<?php
/*
 * Class Location 
 * @version 1.0
 * @date 07/15/10
 * @compatibility: PHP 4, PHP 5
 */
 
class Location
{
	public $mapCoordinates;
	
	function Location($map_key, $location_address)
	{
		$ch = curl_init("http://maps.google.com/maps/geo?key=".$map_key."&output=xml&q=".urlencode($location_address)); 
		
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$resp = curl_exec($ch);
		curl_close ($ch);
		
		$pattern = '/<coordinates>?.*<\/coordinates>/is';
		@preg_match($pattern, $resp, $matched_content); 
		$get_coords = str_replace("<coordinates>","",$matched_content[0]);
		$get_coords = str_replace("</coordinates>","",$get_coords);
		
		$get_coords = explode(",", $get_coords);
		$longitude = $get_coords[0];
		$latitude = $get_coords[1];
		
		$coordinates = array('latitude' => $latitude, 'longitude' => $longitude);
		$this->setMapCoordinates($coordinates);
								
		return $this->getMapCoordinates();
	}	
	
    /**
     * Returns $mapCoordinates.
     * @see Location::$mapCoordinates
     */
     
    function getMapCoordinates()
    {
        return $this->mapCoordinates;
    }
    
    /**
     * Sets $mapCoordinates.
     * @param object $mapCoordinates
     * @see Location::$mapCoordinates
     */
     
    function setMapCoordinates($mapCoordinates)
    {
        $this->mapCoordinates = $mapCoordinates;
    }
}
?>