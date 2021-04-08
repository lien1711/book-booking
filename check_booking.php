<?php
	session_start();
	include( "./access_db.php" );

	$user_id = $_SESSION[ 'user_id' ];
	$book_id = $_POST[ 'book_id' ];

	try{
	  if(get_lending_id_from_book_id($book_id) == 0){
		$_SESSION[ 'message' ] = "貸出中です";
		//$_SESSION[ 'button1' ] = "戻る";
		$_SESSION[ 'link' ] = "./booking.php";
		header( "Location: ./message.php" );
	  } else if( get_booking_id_from_book_id( $book_id ) ==0 ){
		$_SESSION[ 'message' ] = "予約中です";
		//$_SESSION[ 'button1' ] = "戻る";
		$_SESSION[ 'link' ] = "./booking.php";
		header( "Location: ./message.php" );		
	  } else { // 正常終了
		//$_SESSION[ 'message' ] = "<h4>予約しました</h4>";
		// 貸出数が制限に達したかどうかチェック
		get_book_nums_from_user_id( $user_id );
		if( $global_num_book_id >= 3 ) { // 既に制限に達した
			$_SESSION[ 'message' ] = "予約制限数に達しているため、これ以上予約できません";
			//$_SESSION[ 'button1' ] = "戻る";
			$_SESSION[ 'link' ] = "./booking.php";
		header( "Location: ./message.php" );
		} else {
			exe_book( $user_id, $book_id );
			header( "Location: ./booking.php" );
		}
	  } } catch (Exception $e) {
		echo "予約処理で内部エラー発生！<br>" ;
		echo $e->getMessage();
	}
	//header( "Location: ./message.php" );
?>

