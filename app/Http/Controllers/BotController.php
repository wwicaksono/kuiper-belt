<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot;

class BotController extends Controller
{
    public function test(Request $request){
        $channelAccess = env('CHANNEL_ACCESS');
        $channelSecret = env('CHANNEL_SECRET');
        $lineHeader = new HTTPHeader();

        $httpClient = new CurlHTTPClient($channelAccess);
        $bot = new LINEBot($httpClient, ['channelSecret' => $channelSecret]);

        $signature = $request->header($lineHeader::LINE_SIGNATURE);
        if (empty($signature)) {
            return response('Bad Request', 400);
        }

        // Check request with signature and parse request
        try {
            $events = $bot->parseEventRequest($request->getBody(), $signature[0]);
        } catch (InvalidSignatureException $e) {
            return response('Invalid signature', 400);
        } catch (UnknownEventTypeException $e) {
            return response('Unknown event type has come', 400);
        } catch (UnknownMessageTypeException $e) {
            return response('Unknown message type has come', 400);
        } catch (InvalidEventRequestException $e) {
            return response("Invalid event request", 400);
        }

        foreach ($events as $event) {
            $replyText = $event->getText();
            $resp = $bot->replyText($event->getReplyToken(), $replyText);
        }

        return response('OK', 200);
    }
}
