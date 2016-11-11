<?php

namespace App;

use App\User;
use DeveloperDynamo\PushNotification\Contracts\Payload as PayloadBase;

class Payload extends PayloadBase
{
    /**
     * Generate Notification Payload
     *
     * @param User $user
     * @return void
     */
    public function __construct($data)
    {
        //IOS payload format
        $this->apsPayload = [
            "alert" => [
                "title" => $data['subject'],
                "body"  => $data['message'],
            ],
        ];

        //Android payload format
        $this->gcmPayload = [
            "title"     => $data['subject'],
            "message"   => $data['message'],
        ];
    }

    public function send($tokens, $queue = null){
        return parent::send($tokens, $queue = null);
    }
}
