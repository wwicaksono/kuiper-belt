<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot;

class BotController extends Controller
{
    public function test(Request $request){
        $channelAccess = env('CHANNEL_ACCESS');
        $channelSecret = env('CHANNEL_SECRET');
        $bot = new LINEBot($httpClient, ['channelSecret' => $channelSecret]);

        $signature = $req->getHeader(HTTPHeader::LINE_SIGNATURE);
        if (empty($signature)) {
            return $res->withStatus(400, 'Bad Request');
        }

        // Check request with signature and parse request
        try {
            $events = $bot->parseEventRequest($req->getBody(), $signature[0]);
        } catch (InvalidSignatureException $e) {
            return $res->withStatus(400, 'Invalid signature');
        } catch (UnknownEventTypeException $e) {
            return $res->withStatus(400, 'Unknown event type has come');
        } catch (UnknownMessageTypeException $e) {
            return $res->withStatus(400, 'Unknown message type has come');
        } catch (InvalidEventRequestException $e) {
            return $res->withStatus(400, "Invalid event request");
        }

        foreach ($events as $event) {
            if (!($event instanceof MessageEvent)) {
                $logger->info('Non message event has come');
                continue;
            }

            if (!($event instanceof TextMessage)) {
                $logger->info('Non text message has come');
                continue;
            }

            $replyText = $event->getText();
            $resp = $bot->replyText($event->getReplyToken(), $replyText);
        }

        $res->write('OK');
        return $res;
    }
}
