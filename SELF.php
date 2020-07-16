<?php

ini_set('memory_limit', '2048M');
if(!is_dir('files')){
mkdir('files');
}
if(!file_exists('madeline.php')){
copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
if(!file_exists('online.txt')){
file_put_contents('online.txt','off');
}
include 'madeline.php';
$settings = [];
$settings['logger']['max_size'] = 5*1024*1024;
$MadelineProto = new \danog\MadelineProto\API('MYOJ.madeline', $settings);
$MadelineProto->start();
if(file_get_contents('online.txt') == 'on'){
$MadelineProto->account->updateStatus(['offline' => false]);
}
function closeConnection($message = 'lil_mos SELF Is Runinng...<br>Creator bot : @Lil_mos')
{
if (php_sapi_name() === 'cli' || isset($GLOBALS['exited'])) {
return;
}
  @ob_end_clean();
  header('Connection: close');
  ignore_user_abort(true);
  ob_start();
  echo '<html><body><h1 style="margin-top:50px; text-align:center; color:white; text-shadow:1px 1px 15px black;">'.$message.'</h1></body</html>';
  $size = ob_get_length();
  header("Content-Length: $size");
  header('Content-Type: text/html');
  ob_end_flush();
  flush();
  $GLOBALS['exited'] = true;
}
function shutdown_function($lock)
{
  $a = fsockopen((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'tls' : 'tcp').'://'.$_SERVER['SERVER_NAME'], $_SERVER['SERVER_PORT']);
  fwrite($a, $_SERVER['REQUEST_METHOD'].' '.$_SERVER['REQUEST_URI'].' '.$_SERVER['SERVER_PROTOCOL']."\r\n".'Host: '.$_SERVER['SERVER_NAME']."\r\n\r\n");
  flock($lock, LOCK_UN);
  fclose($lock);
}

if (!file_exists('bot.lock')) {
touch('bot.lock');
}
$lock = fopen('bot.lock', 'r+');
$try = 1;
$locked = false;
while (!$locked) {
$locked = flock($lock, LOCK_EX | LOCK_NB);
if (!$locked) {
closeConnection();
if ($try++ >= 30) {
exit;
}
 sleep(1);
}
}
if(!file_exists('data.json')){
 file_put_contents('data.json', '{"power":"on","adminStep":"","typing":"off","echo":"off","markread":"off","poker":"off","enemies":[],"answering":[]}');
}
// Coded by : @Lil_mos
class EventHandler extends \danog\MadelineProto\EventHandler
{
public function __construct($MadelineProto){
parent::__construct($MadelineProto);
}
public function onUpdateSomethingElse($update)
{
if (isset($update['_'])){
  if ($update['_'] == 'updateNewMessage'){
  onUpdateNewMessage($update);
  }
  else if ($update['_'] == 'updateNewChannelMessage'){
  onUpdateNewChannelMessage($update);
}
}
}

public function onUpdateNewChannelMessage($update)
{
 yield $this->onUpdateNewMessage($update);
}
public function onUpdateNewMessage($update){
$from_id = isset($update['message']['from_id']) ? $update['message']['from_id']:'';
  try {
 if(isset($update['message']['message'])){
 $text = $update['message']['message'];
 $msg_id = $update['message']['id'];
 $message = isset($update['message']) ? $update['message']:'';
 $MadelineProto = $this;
 $me = yield $MadelineProto->get_self();
 //$admin = $me['id']; 
 
 $myoj= 208549102;   //آیدی عددی ادمین که میتونه خود ربات هم باشه
 
 
 
 
 
 
 
 $admin = $myoj ; 
 $chID = yield $MadelineProto->get_info($update);
 $peer = $chID['bot_api_id'];
 $type3 = $chID['type'];
 @$data = json_decode(file_get_contents("data.json"), true);
 $step = $data['adminStep'];
 if($from_id == $admin){
 if($text == '/exit;'){
  exit;
 }
   if(preg_match("/^[\/\#\!]?(bot) (on|off)$/i", $text)){
     preg_match("/^[\/\#\!]?(bot) (on|off)$/i", $text, $m);
     $data['power'] = $m[2];
     file_put_contents("data.json", json_encode($data));
     $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Bot Now Is $m[2]"]);
   }
   if(preg_match("/^[\/\#\!]?(online) (on|off)$/i", $text)){
  preg_match("/^[\/\#\!]?(online) (on|off)$/i", $text, $m);
  file_put_contents('online.txt', $m[2]);
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Online Mode Now Is $m[2]"]);
   }
     if ($text == 'ping' or $text == '/ping') {
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Pong :) @Lil_mos"]);
  }
 if(preg_match("/^[\/\#\!]?(setanswer) (.*)$/i", $text)){
$ip = trim(str_replace("/setanswer ","",$text));
$ip = explode("|",$ip."|||||");
$txxt = trim($ip[0]);
$answeer = trim($ip[1]);
if(!isset($data['answering'][$txxt])){
$data['answering'][$txxt] = $answeer;
file_put_contents("data.json", json_encode($data));
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "کلمه جدید به لیست پاسخ شما اضافه شد👌🏻"]);
}else{
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "این کلمه از قبل موجود است :/"]);
 }
}

// lil_mos

if(preg_match("/^[\/\#\!]?(php) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(php) (.*)$/i", $text, $a);
if(strpos($a[2], '$MadelineProto') === false and strpos($a[2], '$this') === false){
$OutPut = eval("$a[2]");
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "`🔻 $OutPut`", 'parse_mode'=>'markdown']);
}
}

if(preg_match("/^[\/\#\!]?(upload) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(upload) (.*)$/i", $text, $a);
$oldtime = time();
$link = $a[2];
$ch = curl_init($link);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, TRUE);
curl_setopt($ch, CURLOPT_NOBODY, TRUE);
$data = curl_exec($ch);
$size1 = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD); curl_close($ch);
$size = round($size1/1024/1024,1);
if($size <= 200.9){
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => '🌵 Please Wait...
💡 FileSize : '.$size.'MB']);
$path = parse_url($link, PHP_URL_PATH);
$filename = basename($path);
copy($link, "files/$filename");
yield $MadelineProto->messages->sendMedia([
 'peer' => $peer,
 'media' => [
 '_' => 'inputMediaUploadedDocument',
 'file' => "files/$filename",
 'attributes' => [['_' => 'documentAttributeFilename',
 'file_name' => "$filename"]]],
 'message' => "🔖 Name : $filename
💠 [Your File !]($link)
💡 Size : @t000c ".$size.'MB',
 'parse_mode' => 'Markdown'
]);
// lil_mos
$t=time()-$oldtime;
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "✅ Uploaded ($t".'s)']);
unlink("files/$filename");
} else {
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => '⚠️ خطا : حجم فایل بیشتر از 200 مگ است!']);
}
}
 if(preg_match("/^[\/\#\!]?(delanswer) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(delanswer) (.*)$/i", $text, $text);
$txxt = $text[2];
if(isset($data['answering'][$txxt])){
unset($data['answering'][$txxt]);
file_put_contents("data.json", json_encode($data));
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "کلمه مورد نظر از لیست پاسخ حذف شد👌🏻"]);
}else{
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "این کلمه در لیست پاسخ وجود ندارد :/"]);
 }
}

// lil_mos

if($text == '/id' or $text == 'id'){
  if (isset($message['reply_to_msg_id'])) {
   if($type3 == 'supergroup' or $type3 == 'chat'){
  $gmsg = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$msg_id]]);
  $messag1 = $gmsg['messages'][0]['reply_to_msg_id'];
  $gms = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$messag1]]);
  $messag = $gms['messages'][0]['from_id'];
// @Lil_mos $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => 'YourID : '.$messag, 'parse_mode' => 'markdown']);
} else {
	if($type3 == 'user'){
 $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "YourID : `$peer`", 'parse_mode' => 'markdown']);
}}} else {
  $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "GroupID : `$peer`", 'parse_mode' => 'markdown']);
}
}

if(isset($message['reply_to_msg_id'])){
if($text == 'unblock' or $text == '/unblock' or $text == '!unblock'){
if($type3 == 'supergroup' or $type3 == 'chat'){
  $gmsg = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$msg_id]]);
  $messag1 = $gmsg['messages'][0]['reply_to_msg_id'];
  $gms = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$messag1]]);
  $messag = $gms['messages'][0]['from_id'];
  yield $MadelineProto->contacts->unblock(['id' => $messag]);
  $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "UnBlocked!"]);
  } else {
  	if($type3 == 'user'){
yield $MadelineProto->contacts->unblock(['id' => $peer]); $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "UnBlocked!"]);
}
}
}

if($text == 'block' or $text == '/block' or $text == '!block'){
if($type3 == 'supergroup' or $type3 == 'chat'){
  $gmsg = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$msg_id]]);
  $messag1 = $gmsg['messages'][0]['reply_to_msg_id'];
  $gms = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$messag1]]);
  $messag = $gms['messages'][0]['from_id'];
  yield $MadelineProto->contacts->block(['id' => $messag]);
  $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Blocked!"]);
  } else {
 	if($type3 == 'user'){
yield $MadelineProto->contacts->block(['id' => $peer]); $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Blocked!"]);
}
}
}

if(preg_match("/^[\/\#\!]?(setenemy) (.*)$/i", $text)){
$gmsg = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$msg_id]]);
  $messag1 = $gmsg['messages'][0]['reply_to_msg_id'];
  $gmsg = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$messag1]]);
  $messag = $gmsg['messages'][0]['from_id'];
  if(!in_array($messag, $data['enemies'])){
    $data['enemies'][] = $messag;
    file_put_contents("data.json", json_encode($data));
    yield $MadelineProto->contacts->block(['id' => $messag]);
    $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "$messag is now in enemy list"]);
  } else {
    $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "This User Was In EnemyList"]);
  }
}
if(preg_match("/^[\/\#\!]?(delenemy) (.*)$/i", $text)){
$gmsg = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$msg_id]]);
  $messag1 = $gmsg['messages'][0]['reply_to_msg_id'];
  $gmsg = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$messag1]]);
  $messag = $gmsg['messages'][0]['from_id'];
  if(in_array($messag, $data['enemies'])){
    $k = array_search($messag, $data['enemies']);
    unset($data['enemies'][$k]);
    file_put_contents("data.json", json_encode($data));
    yield $MadelineProto->contacts->unblock(['id' => $messag]);
    $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "$messag deleted from enemy list"]);
  } else{
    $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "This User Wasn't In EnemyList"]);
  }
 }
}

if(preg_match("/^[\/\#\!]?(answerlist)$/i", $text)){
if(count($data['answering']) > 0){
$txxxt = "لیست پاسخ ها :
";
$counter = 1;
foreach($data['answering'] as $k => $ans){
$txxxt .= "$counter: $k => $ans \n";
$counter++;
}
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $txxxt]);
}else{
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "پاسخی وجود ندارد!"]);
  }
 }
 if($text == 'help' or $text == '/help'){
$mem_using = round(memory_get_usage() / 1024 / 1024,1);
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "راهنمای سلف بات میدلاین
`/bot ` [on] or [off]
• خاموش و روشن کردن ربات

`ping`
• دریافت وضعیت ربات

`online ` on یا off
• روشن و خاموش کردن حالت همیشه انلاین

`typing ` on یا off
• روشن و خاموش کردن حالت تایپینگ بعد از هر پیام

`markread ` on یا off
• روشن و خاموش کردن حالت خوانده شدن پیام ها

`flood ` [NUMBER] [TEXT]
•  اسپم پیام در یک متن

`flood2 ` [NUMBER] [TEXT]
•  اسپم بصورت پیام های مکرر

`contacts ` on یا off
• فعال شدن حالت ادد شدن مخاطبین به صورت خودکار

`adduser ` [UserID] [IDGroup]
• ادد کردن یه کاربر به گروه موردنظر

`setusername ` [text]
• تنظیم نام کاربری (آیدی) ربات

`profile ` [NAME] `|` [LAST] `|` [BIO]
• تنظیم نام اسم , فامیل و بیوگرافی ربات

`/blue ` [TEXT-EN]
• تبدیل متن انگلیسی به فنت Blue

`/sticker ` [TEXT]
• تبدیل متن به استیکر

`/upload ` [URL]
• اپلود فایل از لینک

`/time ` [Time-Zone-EN] (iran)
• دریافت ساعت و تاریخ محلی

`/weather ` [TEXT-EN]
• آب و هوای منطقه

`/music ` [TEXT]
• موزیک درخواستی

`block ` [@username] یا [reply]
• بلاک کردن شخصی خاص در ربات

`unblock ` [@username] یا [reply]
• آزاد کردن شخصی خاص از بلاک در ربات

`/info ` [@username]
• دریافت اطلاعات کاربر

`/gpinfo`
• دریافت اطلاعات گروه

`/sessions`
• دریافت بازنشست های فعال اکانت

`/save ` [REPLAY]
• سیو کردن پیام و محتوا  در پیوی خود ربات

`/id ` [reply]
• دریافت ایدی عددی کابر

`!setenemy ` [userid] یا [reply]
• تنظیم دشمن با استفاده از ایدی عددی یا ریپلی

`!delenemy ` [userid] یا [reply]
• حذف دشمن با استفاده از ایدی عددی یا ریپلی

`!clean enemylist`
• پاک کردن لیست دشمنان

× × × × × ×

🍃 #بخش_تنظیم_جواب_سریع :

`/setanswer ` [TEXT] | [ANSWER]
• افزودن جواب سریع (متن اول متن دریافتی از کاربر و ددوم جوابی که ربات بدهد)

`/delanswer ` [TEXT]
• حذف جواب سریع

`/clean answers`
• حذف همه جواب سریع ها

`/answerlist`
• لیست همه جواب سریع ها

× × × × × ×
Creator bot : @Lil_mos
♻️ مقدار رم درحال استفاده : $mem_using مگابایت",
'parse_mode' => 'markdown']);
}
if(preg_match("/^[\/\#\!]?(save)$/i", $text) && isset($message['reply_to_msg_id'])){
$me = yield $MadelineProto->get_self();
$me_id = $me['id'];
yield $MadelineProto->messages->forwardMessages(['from_peer' => $peer, 'to_peer' => $me_id, 'id' => [$message['reply_to_msg_id']]]);
      $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "> Saved :D"]);
     }
 if(preg_match("/^[\/\#\!]?(typing) (on|off)$/i", $text)){
preg_match("/^[\/\#\!]?(typing) (on|off)$/i", $text, $m);
$data['typing'] = $m[2];
file_put_contents("data.json", json_encode($data));
      $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Typing Now Is $m[2]"]);
     }
 if(preg_match("/^[\/\#\!]?(echo) (on|off)$/i", $text)){
preg_match("/^[\/\#\!]?(echo) (on|off)$/i", $text, $m);
$data['echo'] = $m[2];
file_put_contents("data.json", json_encode($data));
      $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Echo Now Is $m[2]"]);
     }
 if(preg_match("/^[\/\#\!]?(markread) (on|off)$/i", $text)){
preg_match("/^[\/\#\!]?(markread) (on|off)$/i", $text, $m);
$data['markread'] = $m[2];
file_put_contents("data.json", json_encode($data));
      $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Markread Now Is $m[2]"]);
     }
 if(preg_match("/^[\/\#\!]?(info) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(info) (.*)$/i", $text, $m);
$mee = yield $MadelineProto->get_full_info($m[2]);
$me = $mee['User'];
$me_id = $me['id'];
$me_status = $me['status']['_'];
$me_bio = $mee['full']['about'];
$me_common = $mee['full']['common_chats_count'];
$me_name = $me['first_name'];
$me_uname = $me['username'];
$mes = "ID: $me_id \nName: $me_name \nUsername: @$me_uname \nStatus: $me_status \nBio: $me_bio \nCommon Groups Count: $me_common";
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => $mes]);
     }
 if(preg_match("/^[\/\#\!]?(block) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(block) (.*)$/i", $text, $m);
yield $MadelineProto->contacts->block(['id' => $m[2]]);
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Blocked!"]);
     }
 if(preg_match("/^[\/\#\!]?(unblock) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(unblock) (.*)$/i", $text, $m);
yield $MadelineProto->contacts->unblock(['id' => $m[2]]);
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "UnBlocked!"]);
     }
 if(preg_match("/^[\/\#\!]?(checkusername) (@.*)$/i", $text)){
preg_match("/^[\/\#\!]?(checkusername) (@.*)$/i", $text, $m);
$check = yield $MadelineProto->account->checkUsername(['username' => str_replace("@", "", $m[2])]);
if($check == false){
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Exists!"]);
} else{
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Free!"]);
}
     }
 if(preg_match("/^[\/\#\!]?(setfirstname) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(setfirstname) (.*)$/i", $text, $m);
yield $MadelineProto->account->updateProfile(['first_name' => $m[2]]);
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Done!"]);
     }
 if(preg_match("/^[\/\#\!]?(setlastname) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(setlastname) (.*)$/i", $text, $m);
yield $MadelineProto->account->updateProfile(['last_name' => $m[2]]);
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Done!"]);
     }
// @Lil_mos
 if(preg_match("/^[\/\#\!]?(setbio) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(setbio) (.*)$/i", $text, $m);
yield $MadelineProto->account->updateProfile(['about' => $m[2]]);
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Done!"]);
     }
 if(preg_match("/^[\/\#\!]?(setusername) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(setusername) (.*)$/i", $text, $m);
yield $MadelineProto->account->updateUsername(['username' => $m[2]]);
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Done!"]);
     }
 if(preg_match("/^[\/\#\!]?(join) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(join) (.*)$/i", $text, $m);
yield $MadelineProto->channels->joinChannel(['channel' => $m[2]]);
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Joined!"]);
     }
if(preg_match("/^[\/\#\!]?(add2all) (@.*)$/i", $text)){
preg_match("/^[\/\#\!]?(add2all) (@.*)$/i", $text, $m);
$dialogs = yield $MadelineProto->get_dialogs();
foreach ($dialogs as $peeer) {
$peer_info = yield $MadelineProto->get_info($peeer);
$peer_type = $peer_info['type'];
if($peer_type == "supergroup"){
  yield $MadelineProto->channels->inviteToChannel(['channel' => $peeer, 'users' => [$m[2]]]);
}
}
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "Added To All SuperGroups"]);
     }
 if(preg_match("/^[\/\#\!]?(newanswer) (.*) \|\|\| (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(newanswer) (.*) \|\|\| (.*)$/i", $text, $m);
$txxt = $m[2];
$answeer = $m[3];
if(!isset($data['answering'][$txxt])){
$data['answering'][$txxt] = $answeer;
file_put_contents("data.json", json_encode($data));
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "New Word Added To AnswerList"]);
} else{
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "This Word Was In AnswerList"]);
}
     }
 if(preg_match("/^[\/\#\!]?(delanswer) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(delanswer) (.*)$/i", $text, $m);
$txxt = $m[2];
if(isset($data['answering'][$txxt])){
unset($data['answering'][$txxt]);
file_put_contents("data.json", json_encode($data));
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "Word Deleted From AnswerList"]);
} else{
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "This Word Wasn't In AnswerList"]);
}
     }
 if(preg_match("/^[\/\#\!]?(clean answers)$/i", $text)){
$data['answering'] = [];
file_put_contents("data.json", json_encode($data));
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "AnswerList Is Now Empty!"]);
     }
 if(preg_match("/^[\/\#\!]?(setenemy) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(setenemy) (.*)$/i", $text, $m);
$mee = yield $MadelineProto->get_full_info($m[2]);
$me = $mee['User'];
$me_id = $me['id'];
$me_name = $me['first_name'];
if(!in_array($me_id, $data['enemies'])){
$data['enemies'][] = $me_id;
file_put_contents("data.json", json_encode($data));
yield $MadelineProto->contacts->block(['id' => $m[2]]);
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "$me_name is now in enemy list"]);
} else {
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "This User Was In EnemyList"]);
}
     }
 if(preg_match("/^[\/\#\!]?(delenemy) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(delenemy) (.*)$/i", $text, $m);
$mee = yield $MadelineProto->get_full_info($m[2]);
$me = $mee['User'];
$me_id = $me['id'];
$me_name = $me['first_name'];
if(in_array($me_id, $data['enemies'])){
$k = array_search($me_id, $data['enemies']);
unset($data['enemies'][$k]);
file_put_contents("data.json", json_encode($data));
yield $MadelineProto->contacts->unblock(['id' => $m[2]]);
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "$me_name deleted from enemy list"]);
} else{
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "This User Wasn't In EnemyList"]);
}
     }
 if(preg_match("/^[\/\#\!]?(clean enemylist)$/i", $text)){
$data['enemies'] = [];
file_put_contents("data.json", json_encode($data));
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "EnemyList Is Now Empty!"]);
     }
 if(preg_match("/^[\/\#\!]?(enemylist)$/i", $text)){
if(count($data['enemies']) > 0){
$txxxt = "EnemyList:
";
$counter = 1;
foreach($data['enemies'] as $ene){
  $mee = yield $MadelineProto->get_full_info($ene);
  $me = $mee['User'];
  $me_name = $me['first_name'];
  $txxxt .= "$counter: $me_name \n";
  $counter++;
}
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $txxxt]);
} else{
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "No Enemy!"]);
}
     }
 if(preg_match("/^[\/\#\!]?(inv) (@.*)$/i", $text) && $update['_'] == "updateNewChannelMessage"){
preg_match("/^[\/\#\!]?(inv) (@.*)$/i", $text, $m);
$peer_info = yield $MadelineProto->get_info($message['to_id']);
$peer_type = $peer_info['type'];
if($peer_type == "supergroup"){
yield $MadelineProto->channels->inviteToChannel(['channel' => $message['to_id'], 'users' => [$m[2]]]);
} else{
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "Just SuperGroups"]);
}
     }
 if(preg_match("/^[\/\#\!]?(leave)$/i", $text)){
yield $MadelineProto->channels->leaveChannel(['channel' => $message['to_id']]);
     }
 if(preg_match("/^[\/\#\!]?(flood) ([0-9]+) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(flood) ([0-9]+) (.*)$/i", $text, $m);
$count = $m[2];
$txt = $m[3];
$spm = "";
for($i=1; $i <= $count; $i++){
$spm .= "$txt \n";
}
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $spm]);
     }
 if(preg_match("/^[\/\#\!]?(flood2) ([0-9]+) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(flood2) ([0-9]+) (.*)$/i", $text, $m);
$count = $m[2];
$txt = $m[3];
for($i=1; $i <= $count; $i++){
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $txt]);
}
     }
 if(preg_match("/^[\/\#\!]?(music) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(music) (.*)$/i", $text, $m);
$mu = $m[2];
$messages_BotResults = yield $MadelineProto->messages->getInlineBotResults(['bot' => "@melobot", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
$query_id = $messages_BotResults['query_id'];
$query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
yield $MadelineProto->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
     }
 if(preg_match("/^[\/\#\!]?(wiki) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(wiki) (.*)$/i", $text, $m);
$mu = $m[2];
$messages_BotResults = yield $MadelineProto->messages->getInlineBotResults(['bot' => "@wiki", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
$query_id = $messages_BotResults['query_id'];
$query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
yield $MadelineProto->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
     }
 if(preg_match("/^[\/\#\!]?(youtube) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(youtube) (.*)$/i", $text, $m);
$mu = $m[2];
$messages_BotResults = yield $MadelineProto->messages->getInlineBotResults(['bot' => "@uVidBot", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
$query_id = $messages_BotResults['query_id'];
$query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
yield $MadelineProto->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
     }
 if(preg_match("/^[\/\#\!]?(pic) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(pic) (.*)$/i", $text, $m);
$mu = $m[2];
$messages_BotResults = yield $MadelineProto->messages->getInlineBotResults(['bot' => "@pic", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
$query_id = $messages_BotResults['query_id'];
$query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
yield $MadelineProto->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
     }
 if(preg_match("/^[\/\#\!]?(gif) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(gif) (.*)$/i", $text, $m);
$mu = $m[2];
$messages_BotResults = yield $MadelineProto->messages->getInlineBotResults(['bot' => "@gif", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
$query_id = $messages_BotResults['query_id'];
$query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
yield $MadelineProto->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
     }
 if(preg_match("/^[\/\#\!]?(google) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(google) (.*)$/i", $text, $m);
$mu = $m[2];
$messages_BotResults = yield $MadelineProto->messages->getInlineBotResults(['bot' => "@GoogleDEBot", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
$query_id = $messages_BotResults['query_id'];
$query_res_id = $messages_BotResults['results'][rand(0, count($messages_BotResults['results']))]['id'];
yield $MadelineProto->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
     }
 if(preg_match("/^[\/\#\!]?(joke)$/i", $text)){
preg_match("/^[\/\#\!]?(joke)$/i", $text, $m);
$messages_BotResults = yield $MadelineProto->messages->getInlineBotResults(['bot' => "@function_robot", 'peer' => $peer, 'query' => '', 'offset' => '0']);
$query_id = $messages_BotResults['query_id'];
$query_res_id = $messages_BotResults['results'][0]['id'];
yield $MadelineProto->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
     }
 if(preg_match("/^[\/\#\!]?(aasab)$/i", $text)){
preg_match("/^[\/\#\!]?(aasab)$/i", $text, $m);
$messages_BotResults = yield $MadelineProto->messages->getInlineBotResults(['bot' => "@function_robot", 'peer' => $peer, 'query' => '', 'offset' => '0']);
$query_id = $messages_BotResults['query_id'];
$query_res_id = $messages_BotResults['results'][1]['id'];
yield $MadelineProto->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
     }
 if(preg_match("/^[\/\#\!]?(like) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(like) (.*)$/i", $text, $m);
$mu = $m[2];
$messages_BotResults = yield $MadelineProto->messages->getInlineBotResults(['bot' => "@like", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
$query_id = $messages_BotResults['query_id'];
$query_res_id = $messages_BotResults['results'][0]['id'];
yield $MadelineProto->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
     }
 if(preg_match("/^[\/\#\!]?(search) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(search) (.*)$/i", $text, $m);
$q = $m[2];
$res_search = yield $MadelineProto->messages->search(['peer' => $peer, 'q' => $q, 'filter' => ['_' => 'inputMessagesFilterEmpty'], 'min_date' => 0, 'max_date' => time(), 'offset_id' => 0, 'add_offset' => 0, 'limit' => 50, 'max_id' => $message['id'], 'min_id' => 1]);
$texts_count = count($res_search['messages']);
$users_count = count($res_search['users']);
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "Msgs Found: $texts_count \nFrom Users Count: $users_count"]);
foreach($res_search['messages'] as $text){
$textid = $text['id'];
yield $MadelineProto->messages->forwardMessages(['from_peer' => $text, 'to_peer' => $peer, 'id' => [$textid]]);
 }
}
 else if(preg_match("/^[\/\#\!]?(weather) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(weather) (.*)$/i", $text, $m);
$query = $m[2];
$url = json_decode(file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=".$query."&appid=eedbc05ba060c787ab0614cad1f2e12b&units=metric"), true);
$city = $url["name"];
$deg = $url["main"]["temp"];
$type1 = $url["weather"][0]["main"];
if($type1 == "Clear"){
		$tpp = 'آفتابی☀';
		file_put_contents('type.txt',$tpp);
	}
	elseif($type1 == "Clouds"){
		$tpp = 'ابری ☁☁';
		file_put_contents('type.txt',$tpp);
	}
	elseif($type1 == "Rain"){
		 $tpp = 'بارانی ☔';
file_put_contents('type.txt',$tpp);
	}
	elseif($type1 == "Thunderstorm"){
		$tpp = 'طوفانی ☔☔☔☔';
file_put_contents('type.txt',$tpp);
	}
	elseif($type1 == "Mist"){
		$tpp = 'مه 💨';
file_put_contents('type.txt',$tpp);
	}/*
channel  https://t.me/lil_mos
Creator bot : @Lil_mos
https://t.me/lil_mos
*/
  if($city != ''){
$eagle_tm = file_get_contents('type.txt');
  $txt = "دمای شهر $city هم اکنون $deg درجه سانتی گراد می باشد

شرایط فعلی آب و هوا: $eagle_tm";
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $txt]);
unlink('type.txt');
}else{
 $txt = "⚠️شهر مورد نظر شما يافت نشد";
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $txt]);
 }
}
  else if(preg_match("/^[\/\#\!]?(sessions)$/i", $text)){
$authorizations = yield $MadelineProto->account->getAuthorizations();
$txxt="";
foreach($authorizations['authorizations'] as $authorization){
$txxt .="
هش: ".$authorization['hash']."
مدل دستگاه: ".$authorization['device_model']."
سیستم عامل: ".$authorization['platform']."
ورژن سیستم: ".$authorization['system_version']."
api_id: ".$authorization['api_id']."
app_name: ".$authorization['app_name']."
نسخه برنامه: ".$authorization['app_version']."
تاریخ ایجاد: ".date("Y-m-d H:i:s",$authorization['date_active'])."
تاریخ فعال: ".date("Y-m-d H:i:s",$authorization['date_active'])."
آی‌پی: ".$authorization['ip']."
کشور: ".$authorization['country']."
منطقه: ".$authorization['region']."

====================";
}/*
channel  https://t.me/lil_mos
Creator bot : @Lil_mos
https://t.me/lil_mos
*/
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $txxt]);
     }
 if(preg_match("/^[\/\#\!]?(gpinfo)$/i", $text)){
$peer_inf = yield $MadelineProto->get_full_info($message['to_id']);
$peer_info = $peer_inf['Chat'];
$peer_id = $peer_info['id'];
$peer_title = $peer_info['title'];
$peer_type = $peer_inf['type'];
$peer_count = $peer_inf['full']['participants_count'];
$des = $peer_inf['full']['about'];
$mes = "ID: $peer_id \nTitle: $peer_title \nType: $peer_type \nMembers Count: $peer_count \nBio: $des";
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $mes]);
     }
   }/*
channel  https://t.me/lil_mos
Creator bot : @Lil_mos
https://t.me/lil_mos
*/
 if($data['power'] == "on"){
   if ($from_id != $admin) {
   if($message && $data['typing'] == "on" && $update['_'] == "updateNewChannelMessage"){
$sendMessageTypingAction = ['_' => 'sendMessageTypingAction'];
yield $MadelineProto->messages->setTyping(['peer' => $peer, 'action' => $sendMessageTypingAction]);
     }
     if($message && $data['echo'] == "on"){
yield $MadelineProto->messages->forwardMessages(['from_peer' => $peer, 'to_peer' => $peer, 'id' => [$message['id']]]);
     }
     if($message && $data['markread'] == "on"){
if(intval($peer) < 0){
yield $MadelineProto->channels->readHistory(['channel' => $peer, 'max_id' => $message['id']]);
yield $MadelineProto->channels->readMessageContents(['channel' => $peer, 'id' => [$message['id']] ]);
} else{
yield $MadelineProto->messages->readHistory(['peer' => $peer, 'max_id' => $message['id']]);
}
     }
     if(strpos($text, '😐') !== false and $data['poker'] == "on"){
$MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => '😐', 'reply_to_msg_id' => $message['id']]);
     }
  $fohsh = [
"گص کش","کص خوارت مادر کونی کصکش ناموس,کیرم تو خواهرت چی میگی زبون بسته","کصکش ناموس ولدزنا","خفه شو کیرم تو ناموست مادر مرده بیناموس","چچچچچچچچچچچچ","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ","بالا باش مادر مرده کونی ناموس من آرمین بکن ناموست","به کص ننت قسم با ابجیت کار ندارم همون ننت برام کافیه,کیرم تو همه کس پدرت بی غیرت فرار نکن مرگ کص ننت😂😳","خیلی چصی بیناموس شمارش میزنی؟","ناموس خز شده هنو تو فاز ۱۲۳۴۵۶۷۸۹۰هستی کیرم  از همه جهت بر کص مادرت شلیک؟😃😂","عقب افتاده نترس کاریت ندارم خوار کصده اینجا مجازیه تو مجازی ام میترسی ازم؟؟؟","داش شل نباش بکل مرگ مادرت","داششششششششششش کص مادررررررررررررتتتتتتتتت","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ مادرت جندس دیگ چی بگم","ننتو از کص و کون بکنم چند در میاد؟؟؟","داش یه سوال ننت چرا انقد موقعی کص دادن میگوزه؟","خوب داش چیکار کنم ننت جندس؟","تقصیر من چیه خوارکصده هان؟","کصکش ناموس پاسخگو باش مرگ خواهرت","کیرم تو کص عشق اولت و زن و بچت","زن و بچت و ب سیخ کباب خوری بکشم بگام؟","هوم؟","دوست داری مادرت و از کص بکنم یا از کون؟","مادر کص چروکیده بالا باش به مرگ ابجی کوچیکت کاریت ندارم","ححححححححححححححخخخخخخخخخجججججججججچچچچچچچچچهههههههههههغفففففففففففففففففففپپپپپپپپپپپپپپپپپپپ","بدو شات کن شاخ شو کصکش ناموس","کص خوارت داش چرا انقد یتیمی به مولا دلم برات میسوزه","خیلی عقب افتاده ای به همین چایی قسم","چای رو لبریز کنم تو کص مادرت یتیم؟","پاسخگو باش داش","خیلی عقب افتاده ای بیناموس","کص اون ناموست درجه یکه هیچ میدونستی؟","میخوای رو کص آبجیت شاش کنم وادارت کنم بلیسیش؟","خوب یتیم جواب گو باش دیگ بیناموس چرا فقط سعی داری فرار کونی؟","به مولا تو مجازی دستم بهت نمیرسه داش فرار نکن","جون ناموست وایسا در نرو میخوام با کص آبجی کوچیک ور برم","کص ننت چن بخشه؟","ک ُ ص ن َ ن َ ت هشت بخشه بیناموس؟/🤣😂","کص ننت خیلی خوب و مکندس ینی وقتی میکنم توش دو سوت ارضاع میشم😍","اخ ک قربون اون کص چروک و صورتی مادرت برم یتیم","ننت جنده خودمه","یه حقیقتی و میخوام بهت بگم∮","میخوای بدونی ننت چه جنده ایه؟","ننه جنده تو پسر منی","عقب افتاده چرا انقد حمالی؟","خوشت میاد از حمالی مادر کصده خدنگ ناموس؟","مادرت و میخورم اومممممم هام هاممم ننت چقد خوشمزس پسرک یتیم","کص اون مادرت و به سیخ بکشم؟","تا حالا کسی بهت گفته مادرکباب؟عه نگفته؟؟؟پس کص ننت مادر کباب","مادرت و ببرم کبابی بگم سیخ کنن تو کونش بزارن رو منقل برازرز؟","يکم رواني شو  مادرتو رواني بارببندم ب کيرم بدو تيز  باش عيزم هاهاهاهاهاههاابالا باش عه ميخام خشابامو روت تست کنم","چرا در ميري تو انقد هن مگ  نگفتم در نرو از زير کيرم","هاهاههاهاهاهاهههاهاههاهاهاهاهه خهخهخهخهخهخهخهخخخخ","راهي  ني گل من  کيرم تو  سلولاي عصبي مادرت بره الهي تو گو ميخوری","نميرسي همش  و منم مجبورم برا مادرت لالايي بخونم","خارکصه برص بينم مگ من ميزاررم در بر ي  تو  هن  ن راهي برا در رفتن و يتيم بازيو بهونه   وجود نعره  اصن جلومن بزرگ بودن این چيزا   غير ممکنه","هخخخخخخخخخخخخخخخ","تو باسش سال هاي دراز ب پهنا ناموست و ب درازي کيرم بالا باشي  وو  برسي ب تايپ هاي  سنگين و مرگبارمم چصکي بدرد نخور","دوروزه تو گو ميخوري چصي   پيسي نعري بدون خشاب مياي  جلوم   نن نن   ميکني ک اينطور کير شي جلو همه؟","هاهاهههاهاهاهاههاههاهاهاههاهاهاهاههاهاهاههاهاهاههاهاهاهاههاهاهاها","خارکسه ميگم  بالا باش   بفهم    بالااااااااااا هاهاها","در حالي که به ته خط رسيدي بازم از ننه جندت دفاع نميکني؟","کيرم تو غيرت چسيت بدبخت خيلي بدبختي","کيرم تو کس ننت خار خايه ليس اتحادي سيک مادر سگ","خارت ديشب پيويم بودش داشت خايه ليسيمو ميکرد نزنم بگامش","چيه؟ الان ميخاي بگي نه و بهونه بياري؟","خار کسه با خارتم سکس چت کردم تا باور کني","شات فيلم سکس چتاي منو خارت موجوده هيچ راهيم ني بگي نه و در بري با بهونه"," بيناموست کردم براي بار هزارم خار کسه","حس نميکني ناموست کص شده ؟","يه جسي بت دست نداده که الان ديگه چيزي از ابروت نمونده ؟","با چه رويي ديگه ميخاي تو اين چس گپا چت بدي تو ","رو برات نميمونه ک خار کسه ,هوي کس ننه بيناموس بره چي شل تايپ ميدي ؟","خارتو بخورم من بگي اتو ميزني باز ؟","کيرمو بکن تو کصو کون خارت بيناموس چس
در ميري برا چي الان خار کسه ؟","تو گو خوردي کير خوردي بيناموس","کس ننتو خوردي تو شاخ شدي خار کسه؟","کص خارتم من گاییدم شاخ شدي فهميدي کير من برا ننت زياديه عزيزم","کير اين مممبراي دو روزه چس  برا ننت خوبه در سطح خودشوني"," ","چونخار کسه بيناموس چس نباش انقد تو عمرت برا يه بارم ک شده چس نباش","چن بار من کيرمو بکنم تو کس ننت اخه بيناموس ؟","خار کسه مادر کسه اين کيره دسته تبر ني ک هي بره تو مادرت در بياد چيزيش نشه","خار بيناموس بدبخت در ميري الان ؟","الان ناموست اينجا داره شکنجه روحي ميشه تو در ميري ؟","خار کسه تو ده سال ديگم نميتوني به من برس من برسي","من رو کس ننتم دارم برات اواز ميخونم","بيچاره ناموس","اين تل زير دست من جون گرفته بيناموس","خارتم زير کيره من جون گرفته","آِخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ
داش  مادرتوووووووو گاييدم چرا نميرسي بم   تو  هن  ؟؟؟؟؟؟؟؟؟؟؟؟؟","خخخخخخخخخخ   تو خابت ببيني  من   ننتو ب  عنوان   خايه مال بپذيرم","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ","راهي ني ميگم نمال بي مادر  مادرتو     نميگيرم   عه   مادر کسه  مگ  غيرت  نعري  ک  ممادرتو  ميدي  من؟","راهي ني  ميگم در نرو","خخخخخخخخخخخخخخخ بالا باش فقط برس ب تايپم","خخخخخخخخخخ","اين کيرمووووووووو ميگکنم تو مادرتصدا    خر  بدي  ممادر  کسه نمال  ميگم عه  راهي ني","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ","مادر جنده من ننتو ب  عنوان  خدمت کار  خونمونم  نميپزيرم   چ برسه ب  جندع  شخصي ن باشه","خخخخخخخخهخهخهههههههههههههخهخهخهخهخهخهخهخهخ","بالا باش ا نقد  خايه مالي نکن کيرم واص   مادرته  اصن راهي ني نباس بمالي کيرممو","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ  بالاااااااااااااااااااا باششششششششششش","خخخخخخخخخخخخخخنخخخخخخخخخخخخ","کون مادرررررررررررررررررر","کوشيييييييي تو  هن نميرسي","ب تکستاي  چن خطيم؟؟؟؟؟؟؟؟؟؟؟؟؟؟؟//","خخخخخخخخخخخخخخخخخخخخخ","خب نميرسي  ک","ولي باس برسي داچ  برس  برس برس","همش برس مادر کوني افغاني  من مستر بيباک کبيرم  بکن ناموس نسلت","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ","خار کسه کشووي تو  در رفتي؟ راهي ني بالا باش   عه","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ","مادر کون  خارکسه برس بدو  نن نن کن  شايد رسيدي ب تکستام زير کيرم له   نشي يکم تو ديد باشي","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ","ولي راهي نی درنرو","ححخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ","کوشي داش نميرسي ؟","چرا نميرسي  تو ؟","نميخاي  ي  تکوني  بخوري  برسي ؟/؟؟","باش نرص  ممن  مادرتو مميگام فقط باش؟؟؟؟؟؟؟","خب  برس  در  نرو  انقد راهي ني","خارکسه  کينگ بيباکم تو خودتو جر بدي بازم نميرسي بم بفهم","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ
هر  جا منو ديدي در برو  باش؟","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ","راهي ني","بدوووووووووووووووووووووووووووووووووووووووووووووووووووو","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ","خارکسسسسه برس راهي ني خخخخخخخخخخ","بدو  راهي  ني يلع  عه  کوشي/","من ي  دستي بتايپمم توام با  اتوت نميرسي    ک","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ","خب  مادر  کون  ممبر برس حخخخخخخخخخخخکوشي  تو   نيرسي ؟","بیناموس بی فرهنگ ممبر چرا اخه انقدر چصی تو گوز ممبر","بیناموس زاده شده کیرم تو ننت بشه به حق 14 معصوم","ناموس گل خورده از روی نقطه پنالتی در دقیقه 23 توی دربی تهران بین استقلال و پرسولیس به نفع پرسپولیس در دربی 85 بالا تر باش مادر جنده","کشتی کار ناموس بالا باش ببینم میتونی دووم بیاری زیر کیرم حرو زاده ممبر تو سری خور زاده شده هن ؟","تو کلا زیر کیری بیناموس چجوری میخای جلوی من دووم بیاری هن کص ننه ؟","ناموس جوراب فروش در قرن 18 بالا باش","کص ننه خر زاده شده ممبرک چص ننه تو گه خوردی ک شاخی اصن  کص ننه گمنام","ناموس پشمک خور و پشمک دوس و پشمک فروش و پشمک ساز و پشمک پرست کوشی؟","هن؟ زیری؟ کص ننه 
تا نگی گه خوردم ولت نمیکنم پس فکر نکن میزارم به همین راحتیا در بری","خارولدزنا اصلا فکر فرار به سرت نزنه ک کلا تمام راه های فرار رو بستم و نمیزارم در بری","شیره بز تو کصه ننت","در ضمن کیره بز هم تو کصه ننت","درضمن تمام خواص بز  بره تو کصه ننت همراه خوده بز","بیناموس چرا انقدر خزی تو هن ؟","چرا انقدر خری حالا ؟ کص ننه ؟ بالا باش؟","جوش ناموس؟","جوش تو کصه ننت","ناموستو بگا میدم بیناموس ممبرک گه خور زاده بالا باش ببینم بلدی کل کنی یا نه","فکر کردی به همین راحیتا ولت میکنم؟","نه باو باید بگی گه خوردم تا ولت کنم بیناموس","باید مادرتو هدیه کنی بهم تا ازادت کنم","صگ میندازم تو جونه ننت تا ننتو به شدت عجیبی ازار بده جوری ک تو تاریخ ثبت نشده باشه","تخمصگ ممبرک","سوسمار ناموس به همین راحتیا ولت نمیکنم ک","مادرتو به صورت آنال میگام تا درد بکشه تا بفهمه با کی طرفه","مادرتو جوری عذاب میدم ک دیگه نیای گوه خوری کنی","باید بفهمی با دم شیر بازی کردن یعنی چی","ننتو میکنم تو ساعت بجای عقربه های ساعت ننت بچرخه بیناموس اونطوری ساعت هم سکسی میشه","میتونیم این کارم کنیم ک ننتو بکنیم تو اگزوز پرشیا و با پرشیا تند تند ویراژ بدیم تا ننت دود کنه","میخام با کیرم ننتو ضربه فنی کنم اصن مشکلی داری کصه ننه خراب زاده لال مادر"," کور ننه کر ننه خر ننه ول  ننه گوز ننه","خخخخخخخخخخخخخخخخخخ","بیناموس عالمت کردم چرا انقدر  حقیر و بدبختی تو ها ؟ بیچاره زاده شدی مگه نه ؟","راستش بابات خاجه بود نمیتونست ننتو حامله کنه من اومدم ننتو حامله کردم و تو بوجود اومدی یعنی تو از کمر منی","خخخخخخخخخخ","یه کار میکنم ک هروقت اسمون خشمگین شد و خاست رعد و برق بزنه رعد و برق اثابت کنه به کصه مادرت ببینم کصه مادرت توانایی تحمل رعد و برق رو داره یا نه","انگار خیلی زبون نفهمی ک هنوز داری نن نن میکنی بیناموس","پس بهتره ک مادرتو به چرخو فلک بسپرم و از سرعت چرخ و فلک رو زیاد کنم و دهن ننت سرویس شه!!!","فکر خوبیه مادرتو با دستگاه اسیاب کن اسیاب میکنم و میریزنم تو فسنجون و میخورمش نظرت چیه ؟","گه خوردی اصن نظری بدی تو نباید نظری بدی بیناموس ممبرک تو کی هستی ک بخای نظر بدی اصن 
 شل مغز بدبخت چلمنگ بیچاره بفهم ک خاهرت میره بیرون و کص میده","نمیره بیرون کتاب بخره ک میره بیرون کص میده و برمیگرده به همین سادگی"," شیر بز تو کصه ننت بریزم یا شیره گوسفندی بریزم تو کصه ننت یا شیر شتر بریزم تو کصه ننت یا شیر گاو ؟","همرو اصن میریزم حرفم نباشه گوه زیادی هم نخور","کیرم دهن مادره کیر دوستت ک عاشق کیره و کیر رو میپرسه در این حد حشریه ک کیر رو میپرسه بفهم خخخخخخ","کوه و دریا و رود و رودخانه و جزیره و دریاچه و جنگل و صحرا و جاده و چشمه همش بره تو کصه مادرت هعی"," ببین کصه مادرت چیشد همینطوری زیبایی های طبیعت رفت تو کصه ننت","هعی روزگار هم تو کصه ننت فرو بره اشغال  حمال","کیره خر بره تو کصه ابجیت اخه چرا تو انقدر کسخلی ک زود کسخل میشی درگیر ناموس هن ؟","ورقی؟","الاغی؟","خری؟","چی تو ک انقدر بدبخت و ذلیلی","توپ طلا رو میکنم تو کصه ننت بعد کصه ننتو ک توپ طلا هم توش قرار داده شده","رو تقدیم میکنم به بهترین بازیکن سال","میفهمی یتیم ناموس گه خورد زاده ؟","هن ؟ خارکصده باید بفهمی و مطلبو درک کنی","بدبخت تو اونقدر چصی ک اصلا نمیتونی حرف بزنی و موقع جواب دادن مقابل اربابت ک منم به تتهه پتته میوفتی و نمیتونی حرف بزنی","مادرتو میکنم تو گونی اصن میخای گه بخوری؟","ننت جنده شخصیمه هرکاری بخام باهاش میکنم گوه نخور تو","خفه شو تو کصه ننت میگم عه باز داره گه میخوره یکار نکن با کیر بیوفتم تو جونتت بیناموس
اصلا تو گه خوردی ک تند تند گه میخوری گه خور ممبرک با گه مخلوط شده","خارصگ بالا باش ببینم کصه مادرت بشه به حق 5 تن بیناموس ممبرک تو سریخور زاده شده","الهی ک ننتو صگ بگاد به حق تمام ریش سفیدان و تصمیم گیران ادیان جهان کصه ننت بشه الهی ","هیدن تسلا میدونی چیه تو کلش اف کلنز؟","هن؟ اگه میدونی بره تو کصه ننت اگه نمیدونی بره تو کونه ننت","قیاس استثنایی تو کصه ننت فرو بره اصن چ گهی میخای بخوری کص مادر ممبرک چص زاده","اصن کیرم سر دره کصه مادرت بشه بالا باش ببینم میتونی گهی بخوری کص ننه","ناموستو گاییدم اصن ناموستو به سلابه کشیدن اصن بالاا باش","نزار ناموستو سلاخی کنما خارصگ ممبرک چص زاده","یکاری نکن ناموستو به سلابه بکشم یا از صلیب اویزونش کنم کونی ممبر","مادر دسشویی ممبرک چرا همش محوی هن؟","همش زیر کیری∇زن ایندتو گاییدم اصن اگه قراره تو اینده دختر دار بشی دخترتو گاییدم حله؟","اگه رل داری رله فعلیتو گاییدم کصه رلت بشه اصن کصه زنه ایندت","اصن عشق اول خودت و داداشت و بابات و گاییدم","بالا باش جنده ناموس بالاتر باش زن جنده ممبرک مادر خیار غیب کن","لوستر های بزرگ مخصوص قصر های بزرگ رو میکنم تو کص و کونه ناموست بفهمم","من میگم ننتو گاییدم تو بگو لایک حله؟ خب بریم ننتو گاییدم بگو لاییککککک","خخخخخخخخ","کسخلت کردم کسخل شدی دلقک ممبر شدی سوژه خندم شدی ","مادرتو به 37 روش سامورایی گاییدم بووووووووووووووووم !!","بالا باش تا کمر ننتو بشکونم ببینی ک من توانایی  شکوندن کمر مادرت رو هم دارم","بیناموس بالا باش دیگه خار ولدزنا","میخام با مادرت جوری سکس کنم ک تا الان تو تاریخ  ثبت نشده باشه و رکورد همه سکس های دنیا رو بزنه "," سبک و سنگین میکنم ننتو به شدت بالا باش خار زنایی کیرم تو کصه ابجیت بشه خاک بر سر ممبرک","ناموس باخته بالا تر باش","بیا ببین چجوری میخام ننتو بگام بیا ببین ک چجوری تو 5 دیقه ننتو ارضا میکنم خارجنده ممبرک","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ "," کسکش ناموس چرا انقدر یتیم و تو سری خوری تو و نمیتونی از ناموست دفاع کنی؟ هن ؟","مادرتو به صلیب میکشم بیناموس وقتی از دستورات اربابت اطاعت نکنی چنان کیری به کصه ناموست میزنم ک تا سالیان دراز فراموشش نکنی بیناموس","ممبرک تو سری خور زاده شده تو سال 88","من مادرتو در سال های اخیر همش ملایم گاییدم ولی دیشب از کوره در رفتم و به شدت تلمبه های خطرناکی به مادرت وارد کردم","میخام یه حقیقتی بهت بگم ببین تو حاصل شهوت یه شب پدرتی یعنی من پدرتم موقع کردن مادرت کاندوم نداشتم ننت حامله شد","پسرم متاسفم ک برات زندگی پر از رنج و پر از بدبختی فراهم کردم کصه ننت انقدر خوب بود یادم رفت کاندوم استفاده کنم ک تو بدنیا نیای","تو از کمر من بوجود اومدی پس چرا الان داری جلوی من نن نن میکنی هن ؟","بزنم تو سرت صدای صگ بدی بیناموس ممبر","جوری بزنمت ک با گه مخلوط بشی و تشخیص تو از گه یه کار بسیار دشوار بشه ؟","مادرتو بگام؟","میخای شعر بگم بهت ؟","نود نود کصه ننت","خوبه بهت بگم ؟ اره ؟ پس نود نود کصه ننت","یه کام ؟ دو کام ؟ میام ننتو بگام","خخخخخخخخخخخ خوبه مگه نه ؟","کصه ننت گه خوردی بگی بده","مادرتو به سکس رمانتیک دعوت میکنم و ازش میخام ک اب کیرمو بنوشه و ننت هم اینو مطمئنم ک قبول میکنه","سکرت چت تلگرام میدونی چیه ؟ اره ؟","اگه میدونی بکنش تو کصه ننت اگه نمیدونی بکنش تو کونه ننت","خهخههخهخهخخهخههخهخخهخهخهخهخهخهخهخهخهخهخهخههخهخخهخهخهخهخهخهخهخهخهخه","پسرم چرا داری گریه میکنی و اشک میریزی؟ چرا باز داری مثل یتیما گریه میکنی؟ از تکستای من ازرده شدی؟","گه خوردی باید قوی باشی و  زیر تکستام دوام بیاری بیناموس","ممبرک خاک تو کصه ننت بشه","ناموصتو با اموجیای تلگرام دکور میکنم میزارم رو طاقچه خونه و هرفقط حشرم زد بالا ننتو میکنم ابمم میریزنم دهنت تو هم نمیتونی هیچ گهی بخوری کصه ننت پس","من برای به چالش کشیدن حیوانات خونگی مثل صگ و گربه بینشون مسابقه میزارم جایشم اینه ک یه ربع کصه ننتوبلیسن نمیدونی چ تلاشی میکنن اول بشن ک","یه بار هم کصه ننتو تقدیم به اسب شخصیم کردم با کیر زد تو کصه ننت ولی شاید باورت نشه کصه ننت مقابل کیر اسب هم دووم اورد","بیناموس عالمت کردم با تکستای بسیار سنگینم میدونم ک الان بغض گلوتو گرفته و هران ممکه بزنی زیر گریه","ولی نباید گریه کنی باید قوی باشی و مقابل مشکلات زندگی دووم بیاری مثلا من الان این همه تکست روانه کصه ننت کردم تو نباید بشینی گریه کنی ک باید قوی تر بشی  نه اینکه مثل بچه دوساله های گریه کنی و کینه دوزی کنی  کسکش ناموس","راسی من از همه سکسام با ننت فیلم گرفتم و تصمیم برا این دارم ک تمام فیلم های ننت رو در سایت پورنوگرافی برازرس قرار بدم تا بلکه ننت پورن استار هایی همچون رایلی رید التا اوشن جسی جین و الکسیس تگزاس  رو تخریب کنه","کصه ننت برای هزارمین بار بفهم ک ننت کصه چرا اینو درک نمیکنی ک ننت یه ابر کصه هن ؟","قبول کن بدنی ک ننت داره رو هیچ زنی رو زمین نداره باید قبول کنی این حقیقته","حقیقت اینه ک ننت بزرگ ترین کصع روی زمین شناخته شده بفهم اینو کسخل","مگه کسخلی ک نمیفهمی باید هی یه حرفو هزار بار بهت بگم تا بره تو کصه ننت ؟","میخای همینجا ننتو با ساطور قصابی به 2 شقه تبدیل کنم بیناموس ممبرک گه خور ناموس ؟ هن ؟","ولد زنا من با ضربات مشتم به کصه ننت از کصه ننت یه هیولا ساختم ک بی رقیبه انقدر کصه ننتو قوی کردم من","کسخل این تلمبه های محکم من بود ک کون ننتو کرد بهترین کونه جهان این تلمبه های حرفه ای من بود ک کصه ننتو کرد بهترین کصه روی زمین خارکصده خاک تو سرت بشه","اگه من نبودم الان ننت بهترین پورن استار روی زمین نبود ک میشد یه هرزه خیابونی ولی الان بهترین پورنه جهانه","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ","زمانی ک ننه تو بهترین کص بود الکسیس و التا اصلا مطرح نبودن ک ننت فقط مطرح بود البته الان ابجیت داره جای ننتو میگیره و نمیزاره جای خالی ننت احساس بشه هخهخهخهخهخهخهخهخهخهخهخهخهخهخهخخهخههخهخهخهخخهخهخهخهخهخههخخ","بیناموس دارم عذابت میدم با تکستای وحشیم مگه نه ؟","خارولد زنا جوری ک داری تو زجر میکشی زیر تکستام مادرت زیر کیرم عذاب نکشید","ناموستو به شدت عجیبی با المنت میزدم با کیر میزدم مثل اشکه بهار گریه میکردن اما مثل تو نمیرفتن قایم بشن و خفه بشن و نتونن از چیزی بگن خاک عالم تو سرت بشه بیناموس ممبر","اختفا نکن انقدر تو کصه مادرت انقدر استتار نکن لای ممه های ننت بالا باش تا  ابجیتو بگام بیناموس ممبرک","مادر جنده شده در قرن هیجدهم میلادی بالا باش تا روانشناسی گشتالت رو بکنم تو کصه مادرت","میخام به ابجیت همونطور ک به ننت انرژی مثبت دادم به ابجیت هم انرژی مثبت بدم تا بلکه مثل ننت بشه","درک میکنی دارم ننتو میگام ؟ ایا میفهمی ک الان ننت کصش شده غاره علی صدر از بس ک کص داده؟","تونل تو کصه ننت فرو بره چصک ممبر جان","خخخخخخخخخخخخخخخخخخخخخخخخخخخخخخخ","خارکصده عالمی تو چرا انقدر اخه چصی من برام سواله چص بودن تو در حد عجیبی چصی و اصلا نمیرسی و فقط بهونه میاری","خخخخخخخخخخخخخخخخخخخ","کیره کله عالم و ادم و مقامات مهم جهان و بزرگان ادیان دینی و بزرگان و ریش سفیدان کشورای مختلف تو کصه ننت","ای خارکصده مادرتو به روش چینی گاییدم بفهم ناموس خراب زاده کصه ناموست بشه چرا بی غیرتی تو","چرا مادرت جندست تو حروم زاده؟","ولد زنا بالا باش دیگه عه حرصی شدی","خخخخخخخخخخخخ","زخمی ناموس کیرم تو کصه صورتی ننت حله؟","کصه ننت بگو حله خخخخ","خارکصده ننتو به صورت عجیبی میخام بکنم یعنی جوری ک تو تاریخ ثبت نشده","ناهار تو کصه ننت بیناموس","خخخخخخخخخخخخخخخخخخخخ","ناموس خورشت قورمه سبزی بالاتر باش چص زاده","خخخخخخخخخخخخخخخخخخخخ","موهای بلوند ننتو میکشم ننت جیغ میزنه بعد کیرمو فرو میکنم تو کصش حال میکنه بفهم چصکی ناموس","تو خارکصده ترین ادم روی زمینی چون خاهرت بهترین کصه روی زمینه ابجیت ابر داف جهانه","ویژگی های ابجیت ایناس ک میگم دارای کصی داغ و صورتی دارای ممه 85 و بدنی بسیار سفید و سکسی و پاهایی فوق سکسی و لب هایی مخصوص ساک خخخخخخخخخخخخخ","ننتو گاییدم بفهم دیگه بی غیرت نباش بیا کمی تلاش  کن بلکه ننت بهت امیدوار شه","","کس ننه","کص ننت","کس خواهر","کس خوار","کس خارت","کس ابجیت","کص لیس","ساک بزن","ساک مجلسی","ننه الکسیس","نن الکسیس","ناموستو گاییدم","ننه زنا","کس خل","کس مخ","کس مغز","کس مغذ","خوارکس","خوار کس","خواهرکس","خواهر کس","حروم زاده","حرومزاده","خار کس","تخم سگ","پدر سگ","پدرسگ","پدر صگ","پدرصگ","ننه سگ","نن سگ","نن صگ","ننه صگ","ننه خراب","تخخخخخخخخخ","نن خراب","مادر سگ","مادر خراب","مادرتو گاییدم","تخم جن","تخم سگ","مادرتو گاییدم","ننه حمومی","نن حمومی","نن گشاد","ننه گشاد","نن خایه خور","تخخخخخخخخخ","نن ممه","کس عمت","کس کش","کس بیبیت","کص عمت","کص خالت","کس بابا","کس خر","کس کون","کس مامیت","کس مادرن","مادر کسده","خوار کسده","تخخخخخخخخخ","ننه کس","بیناموس","بی ناموس","شل ناموس","سگ ناموس","ننه جندتو گاییدم باو ","چچچچ نگاییدم سیک کن پلیز D:","ننه حمومی","چچچچچچچ","لز ننع","ننه الکسیس","کص ننت","بالا باش","ننت رو میگام","کیرم از پهنا تو کص ننت","مادر کیر دزد","ننع حرومی","تونل تو کص ننت","کیر تک تک بکس تلع گلد تو کص ننت","کص خوار بدخواه","خوار کصده","ننع باطل","حروم لقمع","ننه سگ ناموس","منو ننت شما همه چچچچ","ننه کیر قاپ زن","ننع اوبی","ننه کیر دزد","ننه کیونی","ننه کصپاره","زنا زادع","کیر سگ تو کص نتت پخخخ","ولد زنا","ننه خیابونی","هیس بع کس حساسیت دارم","کص نگو ننه سگ که میکنمتتاااا","کص نن جندت","ننه سگ","ننه کونی","ننه زیرابی","بکن ننتم","اوج مای ننع فاسد","ننه ساکر","کس ننع بدخواه","نگاییدم","مادر سگ","ننع شرطی","گی ننع","بابات شاشیدتت چچچچچچ","ننه ماهر","حرومزاده","ننه کص","کص ننت باو","پدر سگ","سیک کن کص ننت نبینمت","کونده","ننه ولو","ننه سگ","مادر جنده","کص کپک زدع","ننع لنگی","ننه خیراتی","سجده کن سگ ننع","ننه خیابونی","ننه کارتونی","تکرار میکنم کص ننت","تلگرام تو کس ننت","کص خوارت","خوار کیونی","پا بزن چچچچچ","مادرتو گاییدم","گوز ننع","کیرم تو دهن ننت","ننع همگانی","کیرم تو کص زیدت","کیر تو ممهای ابجیت","ابجی سگ","کس دست ریدی با تایپ کردنت چچچ","ابجی جنده","ننع سگ سیبیل","بده بکنیم چچچچ","کص ناموس","شل ناموس","ریدم پس کلت چچچچچ","ننه شل","ننع قسطی","ننه ول","دست و پا نزن کس ننع","ننه ولو","خوارتو گاییدم","محوی!؟","ننت خوبع!؟","کس زنت","شاش ننع","ننه حیاطی /:","نن غسلی","کیرم تو کس ننت بگو مرسی چچچچ","ابم تو کص ننت :/","فاک یور مادر خوار سگ پخخخ","کیر سگ تو کص ننت","کص زن","ننه فراری","بکن ننتم من باو جمع کن ننه جنده /:::","ننه جنده بیا واسم ساک بزن","حرف نزن که نکنمت هااا :|","کیر تو کص ننت😐","کص کص کص ننت😂","کصصصص ننت جووون","سگ ننع","کص خوارت","کیری فیس","کلع کیری","تیز باش سیک کن نبینمت","فلج تیز باش چچچ","بیا ننتو ببر","بکن ننتم باو ","کیرم تو بدخواه","چچچچچچچ","ننه جنده","ننه کص طلا","ننه کون طلا","کس ننت بزارم بخندیم!؟","کیرم دهنت","مادر خراب","ننه کونی","هر چی گفتی تو کص ننت خخخخخخخ","کص ناموست بای","کص ننت بای ://","کص ناموست باعی تخخخخخ","کون گلابی!","ریدی آب قطع","کص کن ننتم کع","نن کونی","نن خوشمزه","ننه لوس"," نن یه چشم ","ننه چاقال","ننه جینده","ننه حرصی ","نن لشی","ننه ساکر","نن تخمی","ننه بی هویت","نن کس","نن سکسی","نن فراری","لش ننه","سگ ننه","شل ننه","ننه تخمی","ننه تونلی","ننه کوون","نن خشگل","نن جنده","نن ول ","نن سکسی","نن لش","کس نن ","نن کون","نن رایگان","نن خاردار","ننه کیر سوار","نن پفیوز","نن محوی","ننه بگایی","ننه بمبی","ننه الکسیس","نن خیابونی","نن عنی","نن ساپورتی","نن لاشخور","ننه طلا","ننه عمومی","ننه هر جایی","نن دیوث","تخخخخخخخخخ","نن ریدنی","نن بی وجود","ننه سیکی","ننه کییر","نن گشاد","نن پولی","نن ول","نن هرزه","نن دهاتی","ننه ویندوزی","نن تایپی","نن برقی","نن شاشی","ننه درازی","شل ننع","یکن ننتم که","کس خوار بدخواه","آب چاقال","ننه جریده","ننه سگ سفید","آب کون","ننه 85","ننه سوپری","بخورش","کس ن","خوارتو گاییدم","خارکسده","گی پدر","آب چاقال","زنا زاده","زن جنده","سگ پدر","مادر جنده","ننع کیر خور","چچچچچ","تیز بالا","ننه سگو با کسشر در میره","کیر سگ تو کص ننت","kos kesh","kir","kiri","nane lashi","kos","kharet","blis kirmo","دهاتی","کیرم لا کص خارت","کیری","ننه لاشی","ممه","کص","کیر","بی خایه","ننه لش","بی پدرمادر","خارکصده","مادر جنده","کصکش"
];
if(in_array($from_id, $data['enemies'])){
  $f = $fohsh[rand(0, count($fohsh)-1)];
  $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $f, 'reply_to_msg_id' => $msg_id]);
}
if(isset($data['answering'][$text])){
  $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $data['answering'][$text] , 'reply_to_msg_id' => $msg_id]);
    }
   }
  }
 }
} catch(\Exception $e){}	catch(\danog\MadelineProto\RPCErrorException $e){}
 }
}
/*
channel  https://t.me/lil_mos
Creator bot : @Lil_mos
https://t.me/lil_mos
*/
// Madeline Tools
register_shutdown_function('shutdown_function', $lock);
closeConnection();
$MadelineProto->async(true);
$MadelineProto->loop(function () use ($MadelineProto) {
  yield $MadelineProto->setEventHandler('\EventHandler');
});
// @Lil_mos
$MadelineProto->loop();
// lil_mos
/*
channel  https://t.me/lil_mos
Creator bot : @Lil_mos
https://t.me/lil_mos
*/
?>
