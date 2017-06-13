<?php
$data = "";
$option = array(
	array( "امار" ),
	array( "ارسال به گروه", "ارسال به سوپرگروه", "ارسال به کاربر" ),
	array( "ارسال به همه" )
);
// Get the keyboard
$keyb    = '';

function CheckText( $text, $chat_id, $telegram ) {
	global $data, $option, $keyb;
	$data = json_decode( file_get_contents( "functions/data.json" ) );
	$botchatid = array("389905657", "388860778", "395307709", "332060339", "363459410");


	CheckId( $chat_id );


	$option = array();
// Get the keyboard
	$keyb    = $telegram->buildKeyBoardHide();

	$result = $telegram->getData();
	if ( ! preg_match( '/^(\d+)$/', $chat_id ) ) {
		$admin = $result["message"]["from"]["id"];
	} else {
		$admin = $chat_id;
	}

	if ( isset($result["message"]["left_chat_participant"]) && $result["message"]["left_chat_participant"]["id"] == "246405229" ) {
		remove($chat_id);

		return false;
	}

	if ( isset($result["message"]["new_chat_participant"]) && $result["message"]["new_chat_participant"]["id"] == "246405229"  ) {
		CheckId( $result["message"]["chat"]["id"] );
	}

	if ( isset( $data->admins->$admin ) ) {
		switch ( $text ) {
			case '/panel':
				$panel   =
					"آمار و اطلاعات ربات تلگرام........
					
					تعداد کاربران:
					" . count((array)$data->users) . "
					
					تعداد سوپرگروه ها:
					" . count( (array)$data->supergroups ) . "
					
					تعداد گروه ها:
					" . count( (array)$data->groups ) . "
					";
				$content = array( 'chat_id' => $chat_id, 'text' => $panel );
				$telegram->sendMessage( $content );

				break;
			case '/start':
				$content = array( 'chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => 'salam Admin :-)' );
				$telegram->sendMessage( $content );
				break;
			case '/fwdgroup':
				forward( "groups", $data, $telegram, $chat_id, $result["message"]["reply_to_message"]["message_id"], "گروه" );

				break;
			case '/fwdsuper':
				forward( "supergroups", $data, $telegram, $chat_id, $result["message"]["reply_to_message"]["message_id"], "سوپرگروه" );

				break;
			case '/fwduser':
				forward( "users", $data, $telegram, $chat_id, $result["message"]["reply_to_message"]["message_id"], "کاربر" );

				break;
			case '/fwdall':
				if(isset($result["message"]["reply_to_message"]["message_id"])){
					forward( "all", $data, $telegram, $chat_id, $result["message"]["reply_to_message"]["message_id"], "همه" );
				}

				break;
			case '/help':
				$msg =
					"
					ارسال به گروه
					/fwdgroup
					
					ارسال به سوپرگروه
					/fwdsuper
					
					ارسال به کاربر
					/fwduser
					
					ارسال به همه
					/fwdall
					
					همه ی دستورات با reply انجام میشود.
					";
				$content = array( 'chat_id' => $chat_id, 'text' => $msg );
				$telegram->sendMessage( $content );

				break;
		}
	} else {
		UsrTxt($telegram);
		CheckId( $chat_id );
	}


	file_put_contents( "functions/data.json", json_encode( $data ) );
}

function UsrTxt($telegram) {
	$data     = json_decode( file_get_contents( 'functions/data.json' ), JSON_UNESCAPED_UNICODE );

	$result  = $telegram->getData();
	$text    = $result["message"]["text"];
	$chat_id = $result["message"]["chat"]["id"];
	$ret     = false;
	$name    = $telegram->FirstName() . ' ' . $telegram->LastName();
	if ( $text == "/start" ) {
		$option = array(
			array( $telegram->buildKeyboardButton( "دریافت عکس" ), $telegram->buildKeyboardButton( "ارتباط با ما" ) )
		);
		$kbBTN  = $telegram->buildKeyBoard( $option, true, true );

		$name = $telegram->FirstName() . ' ' . $telegram->LastName();

		$content = array(
			'chat_id'      => $chat_id,
			'reply_markup' => $kbBTN,
			'text'         => "سلام " . $name . " عزیز، خوش آمدید!
		
		برای دریافت عکس پروفایل باید مراحل زیر را بگذرانید:
		
		1.ابتدا عکس مورد نظر خود را از کانال ما به آدرس @ProfilePic_free انتخاب کنید.
		
		2.سپس در اینجا دکمه 'دریافت عکس' را بزنید.
		
		3.سپس ربات از شما آیدی عکسی که انتخاب کردید را میخواهد.
		
		4.پس از وارد کردن آیدی عکس ، ربات عکس مورد نظر شما را برای اطمینان از درست بودن آیدی برای شما میفرستد و تعداد اسم هایی که شما باید وارد کنید را به شما میگوید.
		
		5.شما باید اسم ها را مطابق با عکس ارسال کنید. یعنی دقت کنید که اسم اول روی عکس باید چه اسمی باشد آن را بفرستید و به همین روش ادامه دهید تا اسم ها به پایان برسد.
		
		6.درخواست شما برای دریافت عکس ذخیره میشود و توسط طراحان ما بررسی شده و در کمترین زمان ممکن برای شما ارسال میشود.
		
		متشکریم!
		",
		);
		$telegram->sendMessage( $content );
	}

	if ( $text == "ارتباط با ما" ) {
		$data["all_users"][ $chat_id ]["contact"] = true;
		$content                                  = array(
			'chat_id' => $chat_id,
			'text'    => "لطفا پیام خود را ارسال کنید.
		",
		);
		$telegram->sendMessage( $content );
	} else if ( isset( $data["all_users"][ $chat_id ]["contact"] ) && $data["all_users"][ $chat_id ]["contact"] == true ) {
		$content = array(
			'chat_id' => $chat_id,
			'text'    => "پیام شما دریافت شد.
		در صورت نیاز در اولین فرصت پاسخ پشتیبان برای شما ارسال خواهد شد.
		با تشکر از تماس شما!
		",
		);
		$telegram->sendMessage( $content );
		$data["all_users"][ $chat_id ]["contact_texts"][] = [
			'id'   => rand( 100000, 999999 ),
			'text' => $text,
			'name' => $name
		];

		$data["all_users"][ $chat_id ]["contact"] = false;
		sendToSup();
	}

	if ( ! isset( $data["all_users"][ $chat_id ] ) ) {
		$data["all_users"][ $chat_id ] = [];
		$option                        = array(
			array( $telegram->buildKeyboardButton( "دریافت عکس" ) ),
			array( $telegram->buildKeyboardButton( "ارتباط با ما" ) )
		);
		$contactBTN                    = $telegram->buildKeyBoard( $option, true, true );
		$name                          = $telegram->FirstName() . ' ' . $telegram->LastName();
		$content                       = array(
			'chat_id'      => $chat_id,
			'reply_markup' => $contactBTN,
			'text'         => "سلام " . $name . " عزیز،
		دکمه 'ارتباط با ما' به ربات ما اضافه شد.
		از این پس میتوانید سوالات ، پیشنهادات و انتقادهای خود را با ما در میان بگذارید.
		متشکریم!
		",
		);
		$telegram->sendMessage( $content );


		$data["all_users"][ $chat_id ]['names'] = $name;
		$ret                                    = true;
	} else {
		$name                                   = $telegram->FirstName() . ' ' . $telegram->LastName();
		$data["all_users"][ $chat_id ]['names'] = $name;
	}

	if ( ! $ret ) {
		$dt = new DateTime();
		$dt->setTimestamp( $data["user"][ $chat_id ]["Req"]["lastDate"] );

		if ( ! isset( $data["user"][ $chat_id ]["Req"]["count"] ) ||
		     ( intval( $data["user"][ $chat_id ]["Req"]["count"] ) < 2 || ( intval( $dt->diff( new DateTime() )->format( '%a' ) ) > 0 || $dt->format( 'd' ) != ( new DateTime() )->format( 'd' ) ) )
		) {


			if ( isset( $data["user"][ $chat_id ]["Req"]["lastDate"] ) && ( intval( $dt->diff( new DateTime() )->format( '%a' ) ) > 0 || $dt->format( 'd' ) != ( new DateTime() )->format( 'd' ) ) ) {
				$data["user"][ $chat_id ] = [];
			}

			if ( $text == "دریافت عکس" ) {
				$content = array(
					'chat_id' => $chat_id,
					'text'    => "لطفا آیدی الگوی مورد نظر خود را وارد کنید.
				آیدی عکس مورد نظر خود را از کانال ما انتخاب کنید:
				@ProfilePic_free"
				);
				$telegram->sendMessage( $content );

				$data["user"][ $chat_id ]["step"] = 'get pattern';

			} else if ( $data["user"][ $chat_id ]["step"] == 'get pattern' ) {
				getPAT( $text, $chat_id, $telegram );

			} else if ( $data["user"][ $chat_id ]["step"] == 'names' && count( $data["user"][ $chat_id ]["pattern"]["names"] ) < intval( $data["user"][ $chat_id ]["pattern"]["count"] ) ) {
				$data["user"][ $chat_id ]["pattern"]["names"][] = $text;

				if ( count( $data["user"][ $chat_id ]["pattern"]["names"] ) >= intval( $data["user"][ $chat_id ]["pattern"]["count"] ) ) {
					$textforsend = 'ذخیره شد. تمام';
					$content     = array(
						'chat_id' => $chat_id,
						'text'    => $textforsend
					);
					$telegram->sendMessage( $content );
					call_DBFunctions( $chat_id, $telegram );
				} else {
					$textforsend = 'ذخیره شد. نام بعدی را وارد کنید.';
					$content     = array(
						'chat_id' => $chat_id,
						'text'    => $textforsend
					);
					$telegram->sendMessage( $content );
				}
			}
		} else {
			$content = array(
				'chat_id' => $chat_id,
				'text'    => "شما در هر روز مجاز به دریافت 2 عکس میباشید\nلطفا درخواست خود را فردا ثبت کنید"
			);
			$telegram->sendMessage( $content );
		}
	}

	file_put_contents( 'functions/data.json', json_encode( $data, JSON_UNESCAPED_UNICODE ) );


	function sendPhoto( $chat_id, $img ) {
		global $telegram;

		$content = array(
			'chat_id' => $chat_id,
			'text'    => "ممنون از صبر شما"
		);
		$telegram->sendMessage( $content );

		$content = array( 'chat_id' => $chat_id, 'photo' => $img );
		$telegram->sendPhoto( $content );
	}

	/**
	 * @param $text
	 * @param $chat_id
	 * @param $telegram
	 */
	function getPAT( $text, $chat_id, $telegram ) {
		global $data;
		$p_id = $text;

		$data["user"][ $chat_id ]["pattern"]["id"] = $text;
		$data["user"][ $chat_id ]["step"]          = 'names';

		$result = DBFunctions( "namesCount", $p_id );

		$content = array( 'chat_id' => $chat_id, 'photo' => "http://78.47.129.34/FinalImages/" . $p_id . '.png' );
		$telegram->sendPhoto( $content );
		$textforsend2 = '';
		$textforsend  = '';

		if ( ! $result ) {
			$data["user"][ $chat_id ]["step"] = 'get pattern';
			$textforsend                      = 'الگو اشتباه است. لطفا الگوی صحیح را وارد کنید.';
		} else {
			$textforsend2 = 'اسم اول را وارد کنید';

			$textforsend = "الگو ذخیره شد
		این الگو دارای " . $result . " اسم میباشد که باید وارد کنید.
		";

			$data["user"][ $chat_id ]["pattern"]["count"] = $result;
			$data["user"][ $chat_id ]["pattern"]["names"] = [];
		}


		$content = array(
			'chat_id' => $chat_id,
			'text'    => $textforsend
		);

		$telegram->sendMessage( $content );

		file_put_contents( 'functions/data.json', json_encode( $data, JSON_UNESCAPED_UNICODE ) );

		if ( $textforsend2 != '' ) {
			$content = array(
				'chat_id' => $chat_id,
				'text'    => $textforsend2
			);

			$telegram->sendMessage( $content );

			file_put_contents( 'functions/data.json', json_encode( $data, JSON_UNESCAPED_UNICODE ) );
		}
	}

	function sendToAdmin( $count, $telegram ) {
		global $data;
		$content = array(
			'chat_id' => "93077939",
			'text'    => "یک درخواست عکس جدید دریاف شد.\nتعداد کل درخواست های انجام نشده:\n" . $count
			             . "\n" . "http://78.47.129.34/editor/reqList.php"
		);
		$telegram->sendMessage( $content );
		$content = array(
//		hamid
//		93077939
			'chat_id' => "91083286",
			'text'    => "یک درخواست عکس جدید دریاف شد.\nتعداد کل درخواست های انجام نشده:\n" . $count
			             . "\n" . "http://78.47.129.34/editor/reqList.php"
		);
		$telegram->sendMessage( $content );

		file_put_contents( 'functions/data.json', json_encode( $data, JSON_UNESCAPED_UNICODE ) );
	}

	function call_DBFunctions( $chat_id, $telegram ) {
		global $data;

		$names = json_encode( $data["user"][ $chat_id ]["pattern"]["names"], JSON_UNESCAPED_UNICODE );
		$p_id  = $data["user"][ $chat_id ]["pattern"]["id"];

		$result = DBFunctions( "writeDB", $p_id, $chat_id, $names );

		$content = array(
			'chat_id' => $chat_id,
			'text'    => "درخواست شما ثبت شد. تا ساعاتی دیگر تصویر مورد نظر برای شما ارسال خواهد شد."
		);
		$telegram->sendMessage( $content );

		$data["user"][ $chat_id ]["Req"] = [
			"count"    => $data["user"][ $chat_id ]["Req"]["count"] ? intval( $data["user"][ $chat_id ]["Req"]["count"] ) + 1 : 1,
			"lastDate" => strtotime( date( "Y-m-d H:i:s",
				mktime( 0, 0, 0 )
			) )
		];

		sendToAdmin( $result, $telegram );

		file_put_contents( 'functions/data.json', json_encode( $data, JSON_UNESCAPED_UNICODE ) );
	}

	function sendToSup() {
		global $telegram;

		$content = array(
			//		hamid
			'chat_id' => "93077939",
			'text'    => "در ارتباط با ما یک پیام جدید دریافت شد.
		لطفا برای پاسخ دهی به این آدرس مراجعه کنید.
		http://78.47.129.34/functions/support.php
		"
		);
		$telegram->sendMessage( $content );
		$content = array(
			//		zeynab
			'chat_id' => "91083286",
			'text'    => "در ارتباط با ما یک پیام جدید دریافت شد.
		لطفا برای پاسخ دهی به این آدرس مراجعه کنید.
		http://78.47.129.34/functions/support.php
		"
		);
		$telegram->sendMessage( $content );
	}
}

function forward( $type, $data, $telegram, $chat_id, $result, $text ) {
	global $keyb;
	$sended = 0;
	foreach ( $data->$type as $chatId=>$i ) {
		$response = $telegram->forwardMessage( [
			'chat_id'      => $chatId,
			'from_chat_id' => $chat_id,
			'message_id'   => $result
		] );

		if ( $response["ok"] == 1 ) {
			$sended ++;
		}else{
			unset($data->all->$chatId);
			unset($data->users->$chatId);
			unset($data->supergroups->$chatId);
			unset($data->groups->$chatId);
		}
	}
	$msg     = "ارسال به "
	           . $sended . " " . $text . " " .
	           "با موفقیت انجام شد.";
	$content = array( 'chat_id' => $chat_id, 'text' => $msg );
	$telegram->sendMessage( $content );
}

function CheckId( $chat_id ) {
	global $data;
	if ( preg_match( '/^(\d+)$/', $chat_id ) ) {
		if ( isset( $data["users"] ) && !isset( $data["users"]["$chat_id"] ) ) {
			$data["users"]["$chat_id"] = true;

			$data["all"]["$chat_id"] = true;
		} else if ( ! isset( $data["users"] ) ) {
			$data["users"]["$chat_id"] = true;

			$data["all"]["$chat_id"] = true;
		}
	} else if ( preg_match( '/^-100/', $chat_id ) ) {
		if ( isset( $data["supergroups"] ) && !isset( $data["supergroups"]["$chat_id"] ) ) {
			$data["supergroups"]["$chat_id"] = true;

			$data["all"]["$chat_id"] = true;
		} else if ( ! isset( $data["supergroups"] ) ) {
			$data["supergroups"]["$chat_id"] = true;

			$data["all"]["$chat_id"] = true;
		}
	} else {
		if ( isset( $data["groups"] ) && !isset( $data["groups"]["$chat_id"] ) ) {
			$data["groups"]["$chat_id"] = true;

			$data["all"]["$chat_id"] = true;
		} else if ( ! isset( $data["groups"] ) ) {
			$data["groups"]["$chat_id"] = true;

			$data["all"]["$chat_id"] = true;
		}
	}


	file_put_contents( "functions/data.json", json_encode( $data ) );
}

function remove( $chat_id ) {
	global $data;
	if ( preg_match( '/^(\d+)$/', $chat_id ) ) {
		unset($data->users->$chat_id);
	} else if ( preg_match( '/^-100/', $chat_id ) ) {
		unset($data->supergroups->$chat_id);
	} else {
		unset($data->groups->$chat_id);
	}

	unset($data->all->$chat_id);


	file_put_contents( "functions/data.json", json_encode( $data ) );
}