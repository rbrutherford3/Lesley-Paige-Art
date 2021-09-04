<?php

require_once 'credentialsrecaptchav3.php';

class recaptcha {
	
	public static function verify($response_label) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, [
			'secret' => RECPATCHA_SECRET_KEY_V3,
			'response' => $_POST[$response_label],
			'remoteip' => $_SERVER['REMOTE_ADDR']
		]);

		$resp = json_decode(curl_exec($ch));
		curl_close($ch);
		
		if ($resp->success) {
			if ($resp->score < 0.5) {
				die('It seems very likely that you are NOT human, so this ride will come to an abrupt stop');
			}
		} else {
			die('Unable to determine whether or not you are human!  Existentialism in the digital age...');
		}
		
		return $resp;
	}
	
	public static function javascript(string $formname, bool $token) {
		$html = '
		<script type="text/javascript" src="https://www.google.com/recaptcha/api.js"></script>
		<script type="text/javascript">
			function onSubmit(token) {';
		if ($token) {
			$html = $html . '
				document.getElementById("token").value = token';
			}
			$html = $html . '
				document.getElementById("' . $formname . '").submit();
			}
		</script>';
		return $html;
	}
	
	public static function tokeninput() {
		return '
				<input type="hidden" name="token" id="token" />';
	}
	
	public static function submitbutton(string $buttonname, string $buttonlabel, string $action, bool $hidden) {
		$html = '
				<button
					class="g-recaptcha" 
					type="submit" 
					name="' . $buttonname . '" 
					id="' . $buttonname . '" 
					data-sitekey="' . RECPATCHA_SITE_KEY_V3 . '" 
					data-callback=\'onSubmit\' 
					data-action=\'' . $action . '\'';
		if ($hidden) {
			$html = $html . '
					style="visibility: hidden;">';
		}
		else {
			$html = $html . '>';
		}
		$html = $html . '
					' . $buttonlabel . '
				</button>';
		return $html;
	}
}

?>