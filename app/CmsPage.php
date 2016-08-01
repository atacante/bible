<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    protected $fillable = ['title', 'text'];
    protected $dates = ['created_at', 'updated_at'];

    public function rules()
    {
        $rules = [
            'title' => 'required|max:255',
            'text' => 'required',
        ];

        return $rules;
    }
}
