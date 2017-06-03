<?php

namespace App;

use Illuminate\Validation\Validator;


class CustomValidator extends Validator {

    protected function validateExt($attribute, $file, $allowExtension)
    {
       // var_dump(mb_strtolower($file->getClientOriginalExtension()));
        //var_dump($file->getMimeType());
        //die();

        return in_array(mb_strtolower($file->getClientOriginalExtension()), $allowExtension) &&
            in_array($file->getMimeType(), $allowExtension);

    }

    protected function replaceExt($message, $attribute, $rule, $parameters)
    {
        return str_replace(':values', implode(', ', $parameters), $message);
    }

    function validatePhone ($attribute, $value, $parameters, $validator) {
        return preg_match('%^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d?)\)?)?[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$%i', $value);
    }

    function replacePhone($message, $attribute, $rule, $parameters) {
        return str_replace(':attribute',$attribute, $message);
    }

}