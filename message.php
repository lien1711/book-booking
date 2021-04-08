<?php
	session_start();
	$message = $_SESSION[ 'message' ]; 
	$link = $_SESSION[ 'link' ];  
	echo "<script>";
	echo "alert('$message'); window.location.href='$link';</script>";
?>
