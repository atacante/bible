<?php namespace App;

class PasswordReset extends BaseModel
{

    /**
     * Generated
     */

    protected $table = 'password_resets';
    protected $fillable = ['email', 'token'];
}
