<?php

namespace App\Http\Controllers;

use LINE\LINEBot;

use Illuminate\Http\Request;

class BotController extends Controller
{

    public function __construct()
    {
        //
    }

    public function callback(LINEBot $bot, Request $request){
        // $events = $request->botevents;
        foreach ($events as $event) {
            $bot->replyMessage(
                   $event->getReplyToken(),
                   'AHAHA'
            );
        }
    }
}
