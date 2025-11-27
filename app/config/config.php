<?php 
define('VALIDATE_EDAD', true);

define('SECRET_ID_KEY', 'EstaVidaEsDuraPeroRecuerdaQueMasDuraEsTuVerdura2000#!@$',);

function encrypt_id($id){
	$encrypted = openssl_encrypt($id, 'AES-128-ECB', SECRET_ID_KEY);

	return strtr(base64_encode($encrypted), '+/=','-_');
}

function decrypt_id($encoded_id){
	$decoded = base64_decode(strtr($encoded_id,'-_', '+/='));

	$decrypted = openssl_decrypt($decoded, 'AES-128-ECB',SECRET_ID_KEY);

	return (int)$decrypted;
}
?>