<?php
namespace App\Providers;

use Illuminate\Validation\Validator;

class ValidRules extends Validator {

    public function validateEmptyField($attribute, $value, $parameters)
    {
//        return ($value != '' && $this->getValue($parameters[0]) == '') ? false : true;
        return ( $value == 1 && $this->getValue($parameters[0]) == null) ? false : true;
    }

}

