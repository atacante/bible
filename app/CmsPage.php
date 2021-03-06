<?php

namespace App;

class CmsPage extends BaseModel
{
    const CONTENT_PAGE = 'page';
    const CONTENT_HOME = 'home';
    const CONTENT_TEXT = 'text';
    const CONTENT_TOOLTIP = 'tooltip';

    protected $fillable = ['title', 'text', 'description', 'meta_title', 'meta_description', 'meta_keywords','published'];
    protected $dates = ['created_at', 'updated_at'];

    public function rules()
    {
        $rules = [
            'title' => 'required|max:255',
            'text' => 'required',
        ];

        return $rules;
    }

    public static function getPage($system_name)
    {
        return self::where('system_name', $system_name)->first();
    }
}
