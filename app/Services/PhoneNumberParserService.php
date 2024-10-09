<?php

namespace App\Services;

class PhoneNumberParserService {
    
    static public function parseToInternational($phoneNumber)
    {
        if (preg_match("/^\+\d+$/", $phoneNumber)) {
            return $phoneNumber;
        } else if (preg_match("/^(08)\d{2,}$/", $phoneNumber)) { // If starts with 08
            return substr_replace($phoneNumber, '+62', 0, 1);
        } else if (preg_match("/^(8)\d{2,}$/", $phoneNumber)) { // If starts with 8
            return substr_replace($phoneNumber, '+62', 0, 0);
        } else { 
            // if no match, assumed to be international number without +, so append +
            return substr_replace($phoneNumber, '+', 0, 0);
        }
    }
}