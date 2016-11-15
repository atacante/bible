<?php

namespace App\Http\Components;

class MailchimpComponent
{

    public static function addEmailToList($email)
    {
        $mailchimp = app('Mailchimp');
        try {
            $mailchimp
                ->lists
                ->subscribe(
                    config('app.mailchimpListId'),
                    ['email' => $email],
                    null,
                    'html',
                    false
                );
        } catch (\Mailchimp_List_AlreadySubscribed $e) {
            return $e->getMessage();
        } catch (\Mailchimp_Error $e) {
            return $e->getMessage();
        }
    }

    public static function removeEmailFromList($email)
    {
        $mailchimp = app('Mailchimp');
        try {
            $mailchimp
                ->lists
                ->unsubscribe(
                    config('app.mailchimpListId'),
                    ['email' => $email],
                    true
                );
        } catch (\Mailchimp_List_AlreadySubscribed $e) {
            return $e->getMessage();
        } catch (\Mailchimp_Error $e) {
            return $e->getMessage();
        }
    }
}
