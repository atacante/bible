<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DeveloperDynamo\PushNotification\TokenTrait;

class DeviceToken extends Model
{
    use TokenTrait;
}
