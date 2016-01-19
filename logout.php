<?php
	require_once 'support/config.php';
	session_destroy();
	redirect('index.php');
?>