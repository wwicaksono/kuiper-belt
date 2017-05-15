<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Request;

class BaconController extends Controller
{
    public function delicious(Request $request){
    	$replyToken = $request->input('replyToken');

    	$channelAccess = getenv(CHANNEL_ACCESS);
    	$channelSecret = getenv(CHANNEL_SECRET);
    	
    	$httpClient = new CurlHTTPClient($channelAccess);

    	$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channelSecret]);

    	$textMessageBuilder = new TextMessageBuilder('hello');
    	$response = $bot->replyMessage($replyToken, $textMessageBuilder);

    	echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
    }
}
