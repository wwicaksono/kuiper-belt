<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;

class BotController extends Controller
{
    public function test(Request $request){
    	$replyToken = $request->input('replyToken');

    	$channelAccess = env('CHANNEL_ACCESS');
    	$channelSecret = env('CHANNEL_SECRET');
    	
    	$httpClient = new CurlHTTPClient($channelAccess);

    	$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

    	$textMessageBuilder = new TextMessageBuilder('hello');
    	$response = $bot->replyMessage($replyToken, $textMessageBuilder);

    	echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
    }
}
