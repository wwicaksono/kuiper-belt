<?php

namespace App\Http\Controllers;

use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\MessageEvent as LINEBotMessageEvent;
use LINE\LINEBot\Event\PostbackEvent as LINEBotPostbackEvent;
use LINE\LINEBot\Exception\InvalidEventRequestException;
use LINE\LINEBot\Exception\InvalidSignatureException;
use LINE\LINEBot\Exception\UnknownEventTypeException;
use LINE\LINEBot\Exception\UnknownMessageTypeException;

use Illuminate\Http\Request;

use App\Handlers\MessageEventHandler;

class BotController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function callback(LINEBot $bot, Request $request){

        $events = $request->botevents;
        foreach ($events as $event) {
            /** @var \LINE\LINEBot\Response */
            $res = null;
            if ($event instanceof LINEBotMessageEvent) {
                return $bot->replyMessage(
                    $event->getReplyToken(),
                    new TextMessageBuilder("apa sih")
                );
                // $res = (new MessageEventHandler($bot, $event))->handle();
            }
            if ($event instanceof LINEBotPostbackEvent) {
                return $bot->replyMessage(
                    $event->getReplyToken(),
                    new TextMessageBuilder("postback neeh sih")
                );
                // $res = (new PostbackEventHandler($bot, $event))->handle();
            }
            if ($res !== null && ! $res->isSucceeded()) {
                // app('log')->error($res->getHTTPStatus() . ': ' . $res->getRawBody());
            }
        }
    }

}
