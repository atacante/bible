<?php

namespace App\Helpers;

use App\BaseModel;
use App\Group;
use App\LexiconBase;
use App\User;
use App\WallItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class NotificationsHelper
{
    public static function friendRequest(User $user){
        $data['to'] = $user->email;
        $data['subject'] = "Bible Study Company: Notification";
        $data['message'] = '
            <p>Dear '.$user->name.',</p>
            <p>There has been some activity at BSC associated to your account.<br />
            You have received a "friend request" from another user. View it <a href="'.url('community/find-friends?type=inbox-requests').'">here</a>.</p>
            ';
        return self::sendNotification($data);
    }

    public static function groupWallItem(Group $group){
        $data['to'] = $group->owner->email;
        $data['subject'] = "Bible Study Company: Notification";
        $data['message'] = '
            <p>Dear '.$group->owner->name.',</p>
            <p>There has been some activity at BSC associated to your account.<br />
            You have a "new post" from another user on your "'.$group->name.'" group wall. View it <a href="'.url('groups/view/'.$group->id).'">here</a>.</p>
            ';
        return self::sendNotification($data);
    }

    public static function groupWallItemComment(WallItem $wallItem){
        if(Auth::user()->id == $wallItem->user->id){
            return false;
        }
        $data['to'] = $wallItem->user->email;
        $data['subject'] = "Bible Study Company: Notification";
        /*  */
        $data['message'] = '
            <p>Dear '.$wallItem->user->name.',</p>
            <p>There has been some activity at BSC associated to your account.<br />
            You have received a "comment" from another user on group wall. View it in next groups: <br />
            ';
        foreach ($wallItem->groupsShares as $group) {
            $data['message'] .= '<a href="'.url('groups/view/'.$group->id).'">'.$group->group_name.'</a><br />';
        }
        $data['message'] .= '</p>';

        return self::sendNotification($data);
    }

    public static function publicWallItemComment(WallItem $wallItem){
        if(Auth::user()->id == $wallItem->user->id){
            return false;
        }
        $data['to'] = $wallItem->user->email;
        $data['subject'] = "Bible Study Company: Notification";
        /*  */
        $data['message'] = '
            <p>Dear '.$wallItem->user->name.',</p>
            <p>There has been some activity at BSC associated to your account.<br />
            You have received a "comment" from another user on public wall. View it <a href="'.url('community').'">here</a>.</p>
            ';
        return self::sendNotification($data);
    }

    public static function groupInvitation(User $user){
        $data['to'] = $user->email;
        $data['subject'] = "Bible Study Company: Notification";
        $data['message'] = '
            <p>Dear '.$user->name.',</p>
            <p>There has been some activity at BSC associated to your account.<br />
            You have received a "group invitation" from another user. View it <a href="'.url('groups?type=my').'">here</a>.</p>
            ';
        return self::sendNotification($data);
    }

    private static function checkData(array $data)
    {
        if(!isset($data['to']) || !isset($data['subject']) || !isset($data['message'])){
            return false;
        }
        return $data;
    }

    private static function sendNotification($data){
        $data = self::checkData($data);
        if($data){
            self::sendEmail($data);
            self::sendWebPush($data);
        }
        return false;
    }

    private static function sendEmail($data)
    {
        return Mail::send([],[], function($message) use($data)
        {
            $message
                ->to($data['to'])
                ->subject($data['subject'])
                ->setBody($data['message'], 'text/html');
        });
    }

    private static function sendWebPush()
    {

    }
}