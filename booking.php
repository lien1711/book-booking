<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上記の３つのタグはheadの中で最初に現れないといけない -->

    <title>図書予約システム</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

   <!-- Internet Explorer 8 以前のバージョンのための対策 -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <!--  コンテンツ  -->
    <?php
	session_start();
	include( "./access_db.php" );
	$user_id = $_SESSION[ 'user_id' ];
	$user_name = $_SESSION[ 'user_name' ];
    ?>
    <div class="container">
      <div class="bg-primary">
        <h1 class="text-center"> 図書予約 </h1>
      </div>
	<div class="row">
          <div class="col-sm-6"></div>
          <div class="col-sm-2"><h4 class="text-center">利用者ID:<?php echo $user_id;?></h4></div>
          <div class="col-sm-2"><h4 class="text-center"><?php echo $user_name;?> 様</h4></div>
	   <div class="col-sm-2">
		<a type="button" href="./logout.php" class="btn btn-link btn btn-block"> ログアウト </a></div>
        </div>

      <br>

      <form action="./check_booking.php" method="post">
	<div class="row">
          <div class="col-sm-2"></div>
          <div class="col-sm-10"> <h4> 予約する書籍のIDを入力して下さい </h4> </div>
        </div>

        <div class="row">
          <div class="col-sm-2"></div>
          <div class="col-sm-4"> <input type="text" class="form-control" name="book_id"> </div>
	  <div class="col-sm-6"> </h4></div>
        </div>

	<br>
	<div class="row">
          <div class="col-sm-7"></div>
          <div class="col-sm-3">
            <button type="submit" class="btn btn-primary btn-block"> 予約実行 </button> 
          </div>
          <div class="col-sm-2"></div>
        </div>

	<br>
	<div class="row">
          <div class="col-sm-2"></div>
          <div class="col-sm-10"> <h4> 予約状況 </h4> </div>
        </div>
	<div class="row">
          <div class="col-sm-2"></div>
          <div class="col-sm-8"> <?php	
		if (get_book_nums_from_user_id( $user_id ) == 0){	echo
	   "<table class='table table-bordered'>
		<thead><tr>	<th>書籍ID</th>
				<th>書籍名</th>
				<th>著者名</th>
		</tr></thead>
		<tbody> ";
		for ( $row=0; $row<$global_num_book_id;  $row++ ) {
		   echo "<tr>";
		   for ( $col=0; $col<3; $col++ ) {
			echo "<td>";
			echo $global_book_id[$row][$col];
			echo "</td>";
		   }
		   echo "</tr>";
		}
		}	else echo "&nbsp;&nbsp;&nbsp;&nbsp; 現在予約はありません";
		echo "</tbody></table>";  ?></div>
          <div class="col-sm-2"></div>	    
        </div>

      </form>

      <form action="./booking.php" method="post">
	<br>
	<div class="row">
          <div class="col-sm-2"></div>
          <div class="col-sm-10"> <h4> 書籍検索 </h4> </div>
        </div>

	<br>
	<div class="row">
          <div class="col-sm-3"></div>
          <div class="col-sm-2"> 書籍名キーワード </div>
          <div class="col-sm-4"> <input type="text" class="form-control" name="book_name" value="<?php echo $_POST['book_name']; ?>" > </div>
          <div class="col-sm-3"></div>
        </div>

	<br>
	<div class="row">
          <div class="col-sm-3"></div>
          <div class="col-sm-2"> 著者名キーワード </div>
          <div class="col-sm-4"> <input type="text" class="form-control" name="author" value="<?php echo $_POST['author']; ?>" > </div>
          <div class="col-sm-3"></div>
        </div>

	<br>
	<div class="row">
          <div class="col-sm-5"></div>
          <div class="col-sm-2">
            <button type="submit" class="btn btn-primary btn-block" name="search"> 検索実行 </button> 
          </div>
          <div class="col-sm-5"></div>
        </div>

	<?php if(isset($_POST['search'])) { 
		$book_name = $_POST['book_name'];
		$author = $_POST['author'];
		if(( $book_name == '' ) && ($author == '' )) { ?>

	<br>
	<div class="row">
          <div class="col-sm-4"></div>
          <div class="col-sm-8"> <h4 style="color: red"> 検索条件が入力されていない </h4> </div>
        </div>
	<?php	} else {
			search_book($book_name, $author);  ?>
	<br>
	<div class="row">
          <div class="col-sm-2"></div>
          <div class="col-sm-10"> <h4> 検索結果 <?php echo $global_nums;?>件 </h4> </div>
        </div>
	<div class="row">
          <div class="col-sm-2"></div>
          <div class="col-sm-8"> <?php	
		if ($global_nums != 0){	echo
	   "<table class='table table-bordered'>
		<thead><tr>	<th>書籍ID</th>
				<th>書籍名</th>
				<th>著者名</th>
		</tr></thead>
		<tbody> ";
		for ( $row=0; $row<$global_nums;  $row++ ) {
		   echo "<tr>";
		   for ( $col=0; $col<3; $col++ ) {
			echo "<td>";
			echo $global_books[$row][$col];
			echo "</td>";
		   }
		   echo "</tr>";
		}
		}}	
		echo "</tbody></table>"; } ?></div>
          <div class="col-sm-2"></div>	    
        </div>
      </form>
    </div>
</body>
</html>
