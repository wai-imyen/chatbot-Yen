<?php

// http://thedebuggers.com/persistent-menu-get-started-buttons-bot/

// 中文編碼轉換
// https://www.mobilefish.com/services/unicode_escape_sequence_converter/unicode_escape_sequence_converter.php 

// 新增 Persistent Menu
curl -X POST -H "Content-Type: application/json" -d '{
  "setting_type" : "call_to_actions",
  "thread_state" : "existing_thread",
  "call_to_actions":[
    {
      "type":"postback",
      "title":"\u67e5\u8a62\u6392\u540d",
      "payload":"search_rank"
    },
    {
      "type":"postback",
      "title":"\u67e5\u8a62\u65b9\u6848",
      "payload":"search_case"
    },
    {
      "type":"web_url",
      "title":"go to Rankbar",
      "url":"rankbar.ktrees.com/keyword-backend"
    }
  ]
}' "https://graph.facebook.com/v2.6/me/thread_settings?access_token=EAACBMI5Xng0BAEiH8vx2gwP9biqKQNeOk5qwW2JrN9bAAcNTDLanQHPoUqUeUTfNsuOfFDy6KOzVE4hcHCSLrhQYHgqRj4QH8mMYMmtYdRXs3GbiWcZAsvZCZAT4QybSADc1LZCZAa89kQ1DRRYagcuaiSYlQQ6jAoYvNK1vnUOcAB3v16TQX"



// 刪除 Persistent Menu
curl -X DELETE -H "Content-Type: application/json" -d '{
  "setting_type":"call_to_actions",
  "thread_state":"existing_thread"
}' "https://graph.facebook.com/v2.6/me/thread_settings?access_token=EAACBMI5Xng0BAEiH8vx2gwP9biqKQNeOk5qwW2JrN9bAAcNTDLanQHPoUqUeUTfNsuOfFDy6KOzVE4hcHCSLrhQYHgqRj4QH8mMYMmtYdRXs3GbiWcZAsvZCZAT4QybSADc1LZCZAa89kQ1DRRYagcuaiSYlQQ6jAoYvNK1vnUOcAB3v16TQX"




// get_started



?>