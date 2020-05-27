<?php
$key = include('key.php');
// Google maps API key
function my_acf_init() {

	acf_update_setting('google_api_key', $key);
}

add_action('acf/init', 'my_acf_init');


?>
