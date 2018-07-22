<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once('./LINEBotTiny.php');

$channelAccessToken = 'vJ37qU7VH2VkI22GzEiHq+txRrQsOhlk26J4D6eeRjcl0DNsfgqkfjbHs++AxJzeeoVNS4bnbaXbwn+owbdHfwjke0HJN7dgE43oPf2lVrVdT1ISatTBXoqgj2i849DUk8H+tfGEW0nlWBVNwiJ0pQdB04t89/1O/w1cDnyilFU=';
$channelSecret = '5818b90e22e7ea293323d0663061fc18';

$accessToken = "vJ37qU7VH2VkI22GzEiHq+txRrQsOhlk26J4D6eeRjcl0DNsfgqkfjbHs++AxJzeeoVNS4bnbaXbwn+owbdHfwjke0HJN7dgE43oPf2lVrVdT1ISatTBXoqgj2i849DUk8H+tfGEW0nlWBVNwiJ0pQdB04t89/1O/w1cDnyilFU=";
$json_string = file_get_contents('php://input');
$jsonObj = json_decode($json_string);
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};     //ReplyToken取得
$userId = $jsonObj->{"events"}[0]->{"source"}->{"userId"};        //userId取得
$eventType = $jsonObj->{"events"}[0]->{"type"};  //typeの取得
$client = new LINEBotTiny($channelAccessToken, $channelSecret);

if($eventType == "beacon"){
      ResponseLineText( $accessToken, $replyToken, "僕との距離が近づいてきました！！" );
  }
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                    $client->replyMessage(array(
                        'replyToken' => $event['replyToken'],
                        'messages' => array(
                            array(
                                'type' => 'text',
                                'text' => $message['text']
                            )
                        )
                    ));
                    break;
                default:
                    error_log("Unsupporeted message type: " . $message['type']);
                    break;
            }
            break;
        default:
            error_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};
function ResponseLineText($accessToken,$replyToken,$text){
    $response_format_text = [
      "type" => "text",
      "text" => $text
    ];
    $post_data = [
       "replyToken" => $replyToken,
       "messages" => [$response_format_text]
    ];
    $ch = curl_init("https://api.line.me/v2/bot/message/reply");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:
    application/json; charser=UTF-8','Authorization: Bearer ' . $accessToken));
    $result = curl_exec($ch);
    curl_close($ch);
 }
