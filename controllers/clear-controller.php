<?php
class ClearController extends Controller {

	public function run() {
		// get the base domain name 
		$domain = Cookies::getThisDomainName();
		// clear the nickname cookie
		setcookie('n','',time()-3600,'/', $domain);
		// clear the first name cookie
		setcookie('f','',time()-3600,'/', $domain);

		// refresh the page
		$url = $_SERVER['PHP_SELF'];
		$url = str_replace('.php', '', $url);
		$url = preg_replace('/\?.*$/', '', $url);
		if ($url == '/index') { $url = '/'; }
		header("Refresh: 0; url=$url");
	}
}
