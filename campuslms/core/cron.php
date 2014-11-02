<?php
	ob_end_clean();
	header("Connection: close\r\n");
	header("Content-Encoding: none\r\n");
	ignore_user_abort(true); // optional
	ob_start();
	echo ('1');
	$size = ob_get_length();
	header("Content-Length: $size");
	ob_end_flush();     // Strange behaviour, will not work
	flush();            // Unless both are called !
	ob_end_clean();

	//CRON code HERE
?>