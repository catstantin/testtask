<?php

	error_reporting(E_ALL);

	include('core/autoload.php');

	$sender = new Sender();
	$error = $sender->run();
	
	if ($error === true) {
		header('location: /');
	}
	else {
		echo $error;
	}

?>