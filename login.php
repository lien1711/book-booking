<?php
	include( "./access_db.php" );
	session_start();
	$user_id = $_POST[ 'user_id' ];
	$user_name = $_POST[ 'user_name' ];
	if(( $user_id == '' ) || ($user_name == '' )||( check_user( $user_id,$user_name ) != 0 )) {
		$_SESSION[ 'message' ] = "利用者IDまたは利用者名が正しくない";
		$_SESSION[ 'link' ] = "./booking_system.html";
		header( "Location: ./message.php" );
	} else { // 予約へ進む
		$_SESSION[ 'user_name' ]= $global_user_name;
		$_SESSION[ 'user_id' ] = $user_id;
		header( "Location: ./booking.php" );
	}
?>
