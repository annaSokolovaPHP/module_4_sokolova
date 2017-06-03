<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModelController  extends Eloquent
{
    /*
    public static function getPossibleEnumValues($name, $model){
        $newModel = new $model;
        $instance = new static; // create an instance of the model to be able to get the table name
        $type = DB::select( DB::raw('SHOW COLUMNS FROM '.$newModel->getTable().' WHERE Field = "'.$name.'"') )[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        foreach(explode(',', $matches[1]) as $value){
            $v = trim( $value, "'" );
            $enum[] = $v;
        }
        return $enum;

    }
    */
}
