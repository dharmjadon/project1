<?php // Code within app\Helpers\Helper.php

namespace App;

use App\Models\Amenties;
use App\Models\Landmark;
use App\Models\NotificationsInfo;
use App\Events\MyEvent;

class CustomerHelper
{
    public static function get_date_formate($date_time)
    {
        $date_only = explode(" ",$date_time);
        $dayofweek = date('D, d M Y', strtotime($date_only[0]));

        return $dayofweek;

    }


    public static function date_formate_detail_page($date_time)
    {
        $date_only = explode(" ",$date_time);
        $dayofweek = date('l, d M Y', strtotime($date_only[0]));

        return $dayofweek;

    }

    public static function get_time_formate($date_time)
    {
        $date_only = explode(" ",$date_time);
        $time_formate = date('g:i a', strtotime($date_only[1] ?? ''));

        return $time_formate;

    }

    public static function get_amentie_info($id)
    {
             $amentie  = Amenties::find($id);
           return $amentie;

    }

    public static function get_landmark_info($id)
    {
        $landmark  = Landmark::find($id);
        return $landmark;
    }

    public static function get_how_old_array(){

        $how_old_array = array(
            '1' => "1 year",
            '2' => "2 year",
            '3' => "3 year",
            '4' => "4 year",
            '5' => "5 year",
            '6' => "6 month",
            '7' => "3 month",
            '8' => "1 month",
            '9' => "new",
        );

        return  $how_old_array;
    }

    public static function get_type_sell(){

        $array_type_sell = array(
            '1' =>'Buy',
            '2' => 'Sell'
        );

        return  $array_type_sell;
    }

    public static function get_motor_type(){

        $array_type_sell = array(
            '1' =>'Buy',
            '2' => 'Rent'
        );

        return  $array_type_sell;
    }

    public static function get_time($date_time)
    {
        $time_formate = date('h:i a', strtotime($date_time));
        return $time_formate;
    }

    public  static function get_social_name(){

        $array_social_name = array(
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'youtube' => 'Youtube',
            'twitter' => 'Twitter',
            'linkedin' => 'Linkedin',
        );

        return $array_social_name;

    }

    public static  function get_youtube_id_from_url($url) {
        if (strpos($url, '://') === false) {
            return $url;
        }

        $videoID = '';

        $submitID = preg_replace('/[^\w\-_:?=.\/\\\\]|\s$/', '', $url);
        preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $submitID, $matches);
        if (isset($matches[1])) {
            $videoID = $matches[1];
        }

        return $videoID;
        /*$parts = parse_url($url);
        if(isset($parts['query'])){
            parse_str($parts['query'], $qs);
            if(isset($qs['v'])){
                return $qs['v'];
            }else if(isset($qs['vi'])){
                return $qs['vi'];
            }
        }
        if(isset($parts['path'])){
            $path = explode('/', trim($parts['path'], '/'));
            return $path[count($path)-1];
        }
        return false;*/
    }


    public static function send_notification_wishlist_guys($wishlist_notifications,$route,$title_msg,$description){

        if(isset($wishlist_notifications)){
            foreach($wishlist_notifications as $abd){

                    $url_wishlist =   $route;
                    if($abd->user->user_type=="3" || $abd->user->user_type=="2"){

                        event(new MyEvent($title_msg,$description,$url_wishlist,"2",$abd->created_by));
                        $notification = new NotificationsInfo();
                        $notification->title = $title_msg;
                        $notification->description = $description;
                        $notification->notification_for = 2;
                        $notification->notify_to = $abd->created_by;
                        $notification->url = $url_wishlist;
                        $notification->save();

                    }else if($abd->user->user_type=="4"){

                        event(new MyEvent($title_msg,$description,$url_wishlist,"1",$abd->created_by));
                        $notification = new NotificationsInfo();
                        $notification->title = $title_msg;
                        $notification->description =  $description;
                        $notification->notification_for = 1;
                        $notification->notify_to = $abd->created_by;
                        $notification->url = $url_wishlist;
                        $notification->save();
                    }
            }
         }


    }



    public static function send_notification_jobs_applied($user,$route,$title_msg,$description){



        if(isset($user)){

            event(new MyEvent($title_msg,$description,$route,"2",$user->id));

            $notification = new NotificationsInfo();
            $notification->title = $title_msg;
            $notification->description = $description;
            $notification->notification_for = 2;
            $notification->notify_to = $user->id;
            $notification->url = $route;
            $notification->save();

            return true;
       }
    }

}
