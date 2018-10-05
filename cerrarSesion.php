<?php 
	session_start();
	unset($_SESSION['identificado']);
	$_SESSION = array();
	setCookie(session_name(),"",time()-3600);
	session_destroy();
	header("Location: indice.html");
?>