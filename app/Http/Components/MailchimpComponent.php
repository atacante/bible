<?php

namespace App\Http\Components;

class MailchimpComponent {

    public static function addEmailToList($email)
    {
        $mailchimp = app('Mailchimp');
        try {
            $mailchimp
                ->lists
                ->subscribe(
                    config('app.mailchimpListId'),
                    ['email' => $email]
                );
        } catch (\Mailchimp_List_AlreadySubscribed $e) {
            return $e;
        } catch (\Mailchimp_Error $e) {
            return $e;
        }

    }

}