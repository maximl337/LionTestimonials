<?php

namespace App\Utilities\States;

class Canada {

	protected static $states = [

	        "BC" => "British Columbia", 
            "ON" => "Ontario", 
            "NL" => "Newfoundland and Labrador", 
            "NS" => "Nova Scotia", 
            "PE" => "Prince Edward Island", 
            "NB" => "New Brunswick", 
            "QC" => "Quebec", 
            "MB" => "Manitoba", 
            "SK" => "Saskatchewan", 
            "AB" => "Alberta", 
            "NT" => "Northwest Territories", 
            "NU" => "Nunavut",
            "YT" => "Yukon Territory"
    ];

    /**
     * return all countries
     * 
     * @return array
     */
    public static function all()
    {
        return static::$states;
    }
}