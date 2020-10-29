<?php

$channelAccessToken = 'Zj2TD/IKGqT5o7b0Imn04FZy4x+eck+5PfNXb2bKR38yDMo17i4DVqijCaaiwVp4cNK9/+b4H7g4d24+9jsbruLq8zgx9BCTFxhkHj8z64CmTGkokkZfr/Mmh8MiIkMw4ty/zDqaW5RBIAbDMrDVcAdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น

$request = file_get_contents('php://input');   // Get request content

$request_json = json_decode($request, true);   // Decode JSON request

foreach ($request_json['events'] as $event)
{
	if ($event['type'] == 'message') 
	{
		if($event['message']['type'] == 'text')
		{
			$text = $event['message']['text'];
			
			$reply_message = 'ฉันได้รับข้อความ '. $text.' ของคุณแล้ว!';   
			
		} else {
			$reply_message = 'ฉันได้รับ '.$event['message']['type'].' ของคุณแล้ว!';
		}
	} else {
		$reply_message = 'ฉันได้รับ Event '.$event['type'].' ของคุณแล้ว!';
	}
	
	// reply message
	$post_header = array('Content-Type: application/json', 'Authorization: Bearer ' . $channelAccessToken);
	$data = ['replyToken' => $event['replyToken'], 'messages' => [['type' => 'text', 'text' => $reply_message]]];
	$post_body = json_encode($data);
	$send_result = replyMessage('https://api.line.me/v2/bot/message/reply', $post_header, $post_body);
	//$send_result = send_reply_message('https://api.line.me/v2/bot/message/reply', $post_header, $post_body);
}

function replyMessage($url, $post_header, $post_body)
{
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => $post_header,
                'content' => $post_body,
            ],
        ]);
	
	$result = file_get_contents($url, false, $context);

	return $result;
}

function send_reply_message($url, $post_header, $post_body)
{
	$ch = curl_init($url);	
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	
	$result = curl_exec($ch);
	
	curl_close($ch);
	
	return $result;
}

?>
