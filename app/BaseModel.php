<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {
    public static function getModelByTableName($name)
    {
        $modelName = __NAMESPACE__.'\\'.ucfirst(camel_case($name));
        return new $modelName();
    }
}
