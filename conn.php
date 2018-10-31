<?php
	$conn = oci_connect('tiany4', 'V00556465', 'localhost:20037/xe');
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
?>