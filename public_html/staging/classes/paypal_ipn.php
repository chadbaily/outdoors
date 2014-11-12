<?php

class paypal_ipn {
	var $paypal_post_vars;
    var $paypal_url;
	var $paypal_response;
	var $timeout;

	var $error_email;
	var $email_headers;
	
	function paypal_ipn($paypal_post_vars,$paypal_url) {
		$this->paypal_post_vars = $paypal_post_vars;
        $this->paypal_url = $paypal_url;
		$this->timeout = 120;
	}

	function send_response() {
		$fp = fsockopen( $this->paypal_url, 80, $errno, $errstr, 120 ); 

		if (!$fp) { 
			$this->error_out("PHP fsockopen() error: " . $errstr);
		} else {
            $response = 'cmd=_notify-validate';
            foreach ($this->paypal_post_vars as $key => $value) {
                $value = urlencode(stripslashes($value));
                $response .= "&$key=$value";
            }

            // post back to PayPal system to validate
            $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
            $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $header .= "Content-Length: " . strlen($response) . "\r\n\r\n";
            $fp = fsockopen ('ssl://' . $this->paypal_url, 443, $errno, $errstr, 30);
            $this->paypal_post_vars['url'] = $cfg['paypal_url'];
            fputs($fp, $header . $response);

			$this->send_time = time();
			$this->paypal_response = ""; 

			// get response from paypal
			while (!feof($fp)) { 
				$this->paypal_response .= fgets( $fp, 1024 ); 

				if ($this->send_time < time() - $this->timeout) {
					$this->error_out("Timed out waiting for a response from PayPal. ($this->timeout seconds)");
				}
			}

			fclose( $fp );

		}

	}
	
	function is_verified() {
		if( ereg("VERIFIED", $this->paypal_response) )
			return true;
		else
			return false;
	} 

	function get_payment_status() {
		$this->error_out("Getting payment status");
		return $this->paypal_post_vars['payment_status'];
	}

	function error_out($message, $subject="ERROR") {

		$date = date("D M j G:i:s T Y", time());
		$message .= "\n\nThe following data was received from PayPal:\n\n";

		@reset($this->paypal_post_vars);
		while( @list($key,$value) = @each($this->paypal_post_vars)) {
			$message .= $key . ':' . " \t$value\n";
		}
		mail($this->error_email, "[$date] $subject", $message, $this->email_headers);

	}
} 

?>
