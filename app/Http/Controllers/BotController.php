<?php

namespace App\Http\Controllers;

use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\Event\MessageEvent as LINEBotMessageEvent;
use LINE\LINEBot\Event\PostbackEvent as LINEBotPostbackEvent;
use LINE\LINEBot\Exception\InvalidEventRequestException;
use LINE\LINEBot\Exception\InvalidSignatureException;
use LINE\LINEBot\Exception\UnknownEventTypeException;
use LINE\LINEBot\Exception\UnknownMessageTypeException;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    public function callback(Request $request){
        /** @var \LINE\LINEBot $bot */
        $bot = new LINEBot(new CurlHTTPClient(env('CHANNEL_TOKEN')), [
            'channelSecret' => env('CHANNEL_SECRET')
        ]);

        $signature = $request->header('X_LINE_SIGNATURE');
        if (empty($signature)) {
            return (new Response($request, 400));
        }

        try {
            $events = $bot->parseEventRequest($request->getContent(), $signature[0]);
        } catch (InvalidSignatureException $e) {
            return (new Response('Invalid signature', 400));
        } catch (UnknownEventTypeException $e) {
            return (new Response('Unknown event type has come', 400));
        } catch (UnknownMessageTypeException $e) {
            return (new Response('Unknown message type has come', 400));
        } catch (InvalidEventRequestException $e) {
            return (new Response('Invalid event request', 400));
        }

        foreach ($events as $event) {
            if (!($event instanceof MessageEvent)) {
                // $logger->info('Non message event has come');
                continue;
            }

            if (!($event instanceof TextMessage)) {
                // $logger->info('Non text message has come');
                continue;
            }

            $replyText = $event->getText();
            $resp = $bot->replyText($event->getReplyToken(), $replyText);
        }

        $res->write('OK');
        return (new Response('OK', 200));
    }

}
