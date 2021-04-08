<?php
/* MySQLサーバに接続し、データベースを使用可能な状態にする */
$db_opened = 0;
$mysqili = null;

function init_db() {
	global $db_opened;
	global $mysqli;
	$mysqli = new mysqli( 'localhost', 'librarian2', 'Kq3j45tyf9', 'library_db2' );
	if( $mysqli->connect_error ) {
		fatal_error( "データベースとの接続に失敗しました" );
	}
	$db_opened = 1;
}

/* 利用者IDと利用者名をチェックする */

$global_user_name=null;

function check_user( $user_id, $user_name ) {
	global $db_opened;
	global $mysqli;
	global $global_user_name;
	if( $db_opened == 0 ) init_db();
	$stmt = $mysqli->prepare( "SELECT user_name FROM users WHERE user_id=?" );
	if( $stmt->bind_param( 'i', $user_id ) == FALSE ) return( 1 );
	if( $stmt->execute() == FALSE ) return( 1 );
	if( $stmt->store_result() == FALSE ) return( 1 );
	if( $stmt->num_rows != 1 ) {
		// ユーザ名を取得できなかった
		$global_user_name = null;
		return( 1 );
	}
	if( $stmt->bind_result( $global_user_name ) == FALSE ) return( 1 );
	if( $stmt->fetch() == FALSE ) return( 1 );
	if( $global_user_name != $user_name) return( 1 );	
	return( 0 );
}

/* 利用者IDが予約している書籍IDの集合を返す */
$global_num_book_id=0;
$global_book_id = [];

function get_book_nums_from_user_id( $user_id ) {
	global $db_opened;
	global $mysqli;
	global $global_num_book_id;
	global $global_book_id;
	if( $db_opened == 0 ) init_db();
	$stmt = $mysqli->prepare( "SELECT * FROM books WHERE book_id in (SELECT book_id FROM booking WHERE user_id = ?)" );
	if( $stmt->bind_param( 'i', $user_id ) == FALSE ) return( 1 );
	if( $stmt->execute() == FALSE ) return( 1 );

	$result = $stmt->get_result();

	while( $row = $result->fetch_row() ){
		$global_book_id[$global_num_book_id++] = $row;
	}
	if( $global_num_book_id == 0 ) return( 1 );
	return 0;
}

/* テーブルbookingに、利用者IDと書籍IDを含む新しいレコードを挿入する */

function exe_book( $user_id, $book_id ) {
	global $db_opened;
	global $mysqli;
	if( $db_opened == 0 ) init_db();
	$mysqli->begin_transaction();
//sleep(5);
	$stmt = $mysqli->prepare( "INSERT INTO booking (user_id, book_id) VALUES (?,?)" );
	if( $stmt->bind_param( 'ii', $user_id, $book_id ) == FALSE ) return( 1 );
	if( $stmt->execute() == FALSE ) {
		$mysqli->rollback();
		return( 1 );
	}
	$mysqli->commit();
	return( 0 );
}

/* 図書IDに対する予約IDを返す */

$global_booking_id=0;

function get_booking_id_from_book_id( $book_id ) {
	global $db_opened;
	global $mysqli;
	global $global_booking_id;
	if( $db_opened == 0 ) init_db();
	$stmt = $mysqli->prepare( "SELECT booking_id FROM booking WHERE book_id=?" );
	$stmt->bind_param( 'i', $book_id );
	$stmt->execute();
	$stmt->store_result();
	if( $stmt->num_rows == 0 ) {
		return( 1 );
	}
	$stmt->bind_result( $booking_id );
	$stmt->fetch();
	$global_booking_id = $booking_id;
	return( 0 );
}

/* 図書IDに対する貸出しIDを返す */

$global_lending_id=0;

function get_lending_id_from_book_id( $book_id ) {
	global $db_opened;
	global $mysqli;
	global $global_lending_id;
	if( $db_opened == 0 ) init_db();
	$stmt = $mysqli->prepare( "SELECT lending_id FROM lending WHERE book_id=?" );
	$stmt->bind_param( 'i', $book_id );
	$stmt->execute();
	$stmt->store_result();
	if( $stmt->num_rows == 0 ) {
		return( 1 );
	}
	$stmt->bind_result( $lending_id );
	$stmt->fetch();
	$global_lending_id = $lending_id;
	return( 0 );
}

/* 検索される書籍名、著者に対する図書情報を返す */

$global_nums = 0;
$global_books = [];

function search_book($book_name, $author){
	global $db_opened;
	global $mysqli;
	global $global_nums;
	global $global_books;
	if( $db_opened == 0 ) init_db();
	$stmt = $mysqli->prepare( "SELECT * FROM books WHERE book_name LIKE CONCAT('%',?,'%') AND author LIKE CONCAT('%',?,'%')" );
	if( $stmt->bind_param( 'ss', $book_name, $author ) == FALSE ) return( 1 );
	if( $stmt->execute() == FALSE ) return( 1 );

	$result = $stmt->get_result();

	while( ($row = $result->fetch_row()) !=NULL){
		$global_books[$global_nums++] = $row;
	}
	if( $global_nums == 0 ) return( 1 );
	return 0;
}
?>
