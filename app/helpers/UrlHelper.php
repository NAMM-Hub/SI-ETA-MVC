<?php
function redirect(string $path){
	$url = BASE_URL . $path;

	header("Location: {$url}");
	exit();
}