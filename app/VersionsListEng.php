<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class VersionsListEng extends Model {

    /**
     * Generated
     */

    public $timestamps  = false;

    protected $table = 'versions_list_eng';
    protected $fillable = ['id', 'version_name'];



}
