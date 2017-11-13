<?php

include_once 'DB/PDO_DB_Class.php';

$db = new DB($DB_USER,$DB_PASS,$DB_NAME,$DB_HOST);

$m_id = 172;

// 與FB及Wit.ai 界接的參數
$hubVerifyToken = 'chatbotYen486'; // 這裏寫的字串，後面我們設定 webhooks 時要一致
$accessToken = 'EAACBMI5Xng0BAEiH8vx2gwP9biqKQNeOk5qwW2JrN9bAAcNTDLanQHPoUqUeUTfNsuOfFDy6KOzVE4hcHCSLrhQYHgqRj4QH8mMYMmtYdRXs3GbiWcZAsvZCZAT4QybSADc1LZCZAa89kQ1DRRYagcuaiSYlQQ6jAoYvNK1vnUOcAB3v16TQX';


// check token at setup
if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe') {
    if($_REQUEST['hub_verify_token'] == $hubVerifyToken) {
        echo $_REQUEST['hub_challenge'];
        exit();
    }
}



// 接收來自 Facebook 的 input
$input = json_decode(file_get_contents('php://input'), true);

$send_id = $input['entry'][0]['messaging'][0]['sender']['id'];

$msg_array = $input['entry'][0]['messaging'][0];

$msg = isset($input['entry'][0]['messaging'][0]['message']['text']) ? $input['entry'][0]['messaging'][0]['message']['text']: '' ;
$messaging_postback = isset($input['entry'][0]['messaging'][0]['postback']['payload']) ? $input['entry'][0]['messaging'][0]['postback']['payload']: '' ;

$quick_replies_payload = $input['entry'][0]['messaging'][0]['message']['quick_reply']['payload'];

$profile = get_sender_profile($send_id,$accessToken);

$reply = '';

/* 查詢專案資料 start */

$columns = '*';

$table = 'project';

$where = 'm_id = ?';

$data = array($m_id);

$format = array($i);

$result = $db->select($columns,$table,$where,$others='',$data,$format,$debug=0);

$pj_array = array();

foreach ($result as $data)
{
    
	array_push($pj_array, $data['pj_name']);
}

/* 查詢專案資料 end */

if ($messaging_postback || $msg) {

	if ($messaging_postback == 'first_hand_shake') {

		$reply = 'Hello ~ '.$profile['first_name'];

	}

	elseif($messaging_postback == 'search_rank' || $msg == '查詢排名') {

		$reply = '輸入您要查詢的專案';

		$quick_replies =  [];

		foreach ($result as $data)
		{
		    $push_data = [
		        "content_type" => "text",
		        "title" => $data['pj_name'],
		        "payload" => $data['pj_name']
	    	];

	    	array_push($quick_replies, $push_data);
		}



	}elseif (in_array($msg, $pj_array)) {


	
		$reply =	'"'.$msg.'" 關鍵字排名 :'.chr(10).chr(10);



		/* 查詢專案資料 start */

		$columns = 'pj_id, update_time, pj_domain';

		$table = 'project';

		$where = 'pj_name = ? AND m_id = ?';

		$data = array($msg,$m_id);

		$format = array($s,$i);

		$project_result =  $db->select($columns,$table,$where,$others='',$data,$format,$debug=0)[0];

		$pj_id = $project_result['pj_id'];

		$pj_domain = $project_result['pj_domain'];

		$update_date = substr($project_result['update_time'], 0,10);




		$columns = 'keyword';

		$table = 'keyword';

		$where = 'pj_id = ? ';

		$data = array($pj_id);

		$format = array($i);

		$keyword_result = $db->select($columns,$table,$where,$others='',$data,$format,$debug=0);

		foreach ($keyword_result as $keyword_data)
		{
		    
			$columns = '*';

			$table = 'history';

			$where = 'keyword = ? AND pj_id = ? AND query_site = "google" AND domain = ?';

			$others = 'ORDER BY create_time DESC';

			$data = array($keyword_data['keyword'],$pj_id,$pj_domain);

			$format = array($s,$i,$s);

			$history_result = $db->select($columns,$table,$where,$others,$data,$format,$debug=0)[0];

			$reply .=	$history_result['keyword'].' : '.$history_result['rank'].chr(10);
		}

		$reply .=	chr(10).'最後更新於 '.$update_date.chr(10);


		/* 查詢專案資料 end */

	}elseif ($messaging_postback == 'search_case' || $msg == '查詢方案') {
		
		$reply = '立馬幫您查詢方案資料';


	}elseif ($messaging_postback == 'ask_questions' || $msg == '詢問問題') {
		
		$reply = '問！';

	}else{

		$reply = '哇聽謀哩勒共蝦';

	}
	

	send_text_message($send_id,$reply,$quick_replies,$accessToken);

	die();

}









function get_sender_profile($send_id,$accessToken)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v2.6/".$send_id."?fields=first_name,last_name,profile_pic&access_token=".$accessToken);
	$result = curl_exec($ch);
	curl_close($ch);

	$profile = json_decode($result,true); 

	return $profile;
}

function send_text_message($send_id,$reply,$quick_replies,$accessToken){

	$response = [
	    'recipient' => [ 'id' => $send_id ],
	    'message' => [ 

	    	'text' => $reply ,
	    	"quick_replies" => $quick_replies

	    ]	// -- message

	];	// -- $response

	$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$accessToken);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_exec($ch);
	curl_close($ch);

	exit();

	return ;

	
}



