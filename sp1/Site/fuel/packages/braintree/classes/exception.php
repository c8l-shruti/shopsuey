<?php

namespace Braintree;

class Exception extends \FuelException {
    
    private $errors;

    public function setErrorsObject($errors) {
    	return $this->errors = $errors;
    }
    
    public function getErrorsObject() {
        return $this->errors;
    }
}
