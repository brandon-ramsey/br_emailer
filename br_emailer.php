<?php

/**
 * BR_Emailer class
 *
 * Emailer class for validating and cleaning email addresses and urls, cleaning common injection attempts in strings, building headers and parameters, and sending an email.
 *
 * PHP version 7
 *
 *	This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @category   CategoryName
 * @package    PackageName
 * @author     Brandon Ramsey <contact@brcoding.com>
 * @copyright  copyright 2018 Brandon Ramsey
 * @version    1
 * @since      File available since Release 1
 */

/**
 * 
 *
 * 
 */

	class BR_Emailer {
	/** $_email
	 * The email message!
	 *
	 * Email
	 * contains all the pieces for an email to be formed and sent
	 *
	 * @var array
	 * @access private
	 */				
		protected static $_email = array();

    /**	function __construct
	 *
     * <code>
     * require_once 'emailer.php';
     *
     * $e = new Emailer("emailTo@example.com", "emailFrom@example.com", "emailReplyTo@example.com", "Subject line", "Email Message",  "emailBCC@example.com", "emailC@example.com");
     * </code>
     * @param string $emailTo email destination 
     * @param string $emailFrom email source
     * @param string $emailReplyTo reply destination
     * @param string $subject the email subject line
     * @param string $message the email message
     * @param string $bcc the email blind carbon copy
     * @param string $cc the email carbon copy
     *
	 */			
		function __construct($emailTo, $emailFrom, $emailReplyTo, $subject, $message, $bcc, $cc){
			
			self::set_email($emailTo, $emailFrom, $emailReplyTo, $subject, $message, $bcc, $cc);			
			
		}
		
    /** function set_email
	 * Will set the email array using passed arguments
	 *
     * <code>
     * require_once 'emailer.php';
     *
     * $e = new Emailer();
	 * $e.set_email("emailTo@example.com", "emailFrom@example.com", "emailReplyTo@example.com", "Subject line", "Email Message",  "emailBCC@example.com", "emailC@example.com");
	 
     * </code>
     * @param string $emailTo email destination 
     * @param string $emailFrom email source
     * @param string $emailReplyTo reply destination
     * @param string $subject the email subject line
     * @param string $message the email message
     * @param string $bcc the email blind carbon copy
     * @param string $cc the email carbon copy
     *
	 */			
		public function set_email($emailTo, $emailFrom = false, $emailReplyTo = false, $subject, $message, $bcc = false, $cc = false){
		
			self::_set_emailTo($emailTo);
			self::_set_emailFrom($emailFrom);
			self::_set_emailReplyTo($emailReplyTo);
			self::_set_subject($subject);
			self::_set_message($message);
			self::_set_bcc($bcc);
			self::_set_cc($cc);

		}

    /** function _set_emailTo
	 * Will clean and set the email destination using passed argument
	 *
     * @param string //destination
	 */			
		protected function _set_emailTo($string){
		/**
		 * Who is the email going to?
		 *
		 * To:
		 * specifies the reciever of the email v2 should take a list
		 *
		 * @var string
		 * @format email || "string" < email >  NOTE: this may be dependent on your server, mine is configured to take quoted name with angle-bracketed email or email another format: email (string)
		 */			
			self::$_email['emailTo'] = self::clean_string($string);			
		}

    /** function _set_emailFrom
	 * Will clean and set the email destination using passed argument
	 *
     * @param string //email source
	 */			
		protected function _set_emailFrom($string){
	    /**
		 * Who is sending the email?
		 *
		 * From:
		 * specifies the sender of the email
		 *
		 * @var string
		 * @format email || "string" < email >  NOTE: this may be dependent on your server, mine is configured to take quoted name with angle-bracketed email or email another format: email (string)
		 */			
			self::$_email['emailFrom'] = self::clean_string($string) ?: '';			
		}
		
    /** function _set_emailReplyTo
	 * Will clean and set the email reply-to using passed argument
	 *
     * @param string //reply destination
	 */			
		protected function _set_emailReplyTo($string){
		/**
		 * Who is the reply going to?
		 *
		 * Mail-Reply-To: && Reply-To:
		 * specifies the sender of the email
		 *
		 * @var string
		 * @format email || "string" < email >   --NOTE: this may be dependent on your server, mine is configured to take quoted name with angle-bracketed email or email another format: email (string)
		 */				
			self::$_email['emailReplyTo'] = self::clean_string($string) ?: '';			
		}

    /** function _set_subject
	 * Will clean and set the email subject using passed argument
	 *
     * @param string //the email subject line
	 */			
		protected function _set_subject($string){
		/**
		 * What is the email subject line?
		 *
		 * Subject:
		 * specifies the subject of the email
		 *
		 * @var string
		 * @format string
		 */				
			self::$_email['subject'] = self::clean_string($string);
		}

    /** function _set_message
	 * Will clean and set the email message using passed argument
	 *
     * @param string //the email message
	 */			
		protected function _set_message($string){
		/**
		 * What is the email message?
		 *
		 * message:
		 * specifies the email message
		 *
		 * @var string
		 * @access private
		 * @format string
		 */			
			self::$_email['message'] = nl2br(self::clean_string($string));			
		}

    /** function _set_bcc
	 * Will clean and set the email blind carbon copy destination/s using passed argument
	 *
     * @param string  //the email blind carbon copy
	 */			
		protected function _set_bcc($string){
		/**
		 * Who is getting a carbon copy of the email message?
		 *
		 * CC:
		 * specifies who gets a carbon copy of the email message
		 *
		 * @var string
		 * @format string
		 */				
			self::$_email['bcc'] = self::clean_string($string) ?: '';			
		}
	
    /** _set_cc
	 * Will clean and set the email carbon copy destination/s using passed argument
	 *
     * @param string //the email carbon copy
	 */		
		protected function _set_cc($string){
		/**
		 * Who is getting a blind carbon copy of the email message?
		 *
		 * CC:
		 * specifies who gets a 
		 * Who is getting a blind carbon copy of the email message
		 *
		 * @var string
		 * @access private
		 * @format string
		 */				
			self::$_email['cc'] = self::clean_string($string) ?: '';			
		}
		
    /** _load_email
	 * Checks the $_email array for valid entries
	 *
	 * @return true or a list of invalid entries
	 */				
		protected function _load_email(){
			
			$errors = array();
			
			if(self::$_email['emailTo'] === false){
				array_push($errors, "Invalid emailTo.");
			}
				
			if(self::$_email['emailFrom'] === false && self::$_email['emailFrom'] !== ''){
				array_push($errors, "Invalid emailFrom.");
			}
			
			if(self::$_email['emailReplyTo'] === false && self::$_email['emailReplyTo'] !== ''){
				array_push($errors, "Invalid emailReplyTo.");
			}
			
			if(self::$_email['subject'] === false ){
				array_push($errors, "Invalid subject.");
			}
			
			if(self::$_email['message'] === false ){
				array_push($errors, "Invalid message.");
			}
			
			if(self::$_email['bcc'] === false && !self::$_email['bcc'] !== ''){
				array_push($errors, "Invalid bcc.");
			}
			
			if(self::$_email['cc'] === false && !self::$_email['cc'] !== ''){
				array_push($errors, "Invalid cc.");
			}
			
			if(!empty($errors)){
				return implode('\r\n', $errors);
			}
			
			return true;
			
		}

    /** validate_email
	 * Validates an email address and optionally removes illegal characters
	 *
	 * @param string //email address to be checked
	 * @param boolean //tells the function to remove illegal characters or not
	 * @return valid email or false
	 */			
		public function validate_email($string, $clean = false){
			
			if($clean){
				$string = filter_var($string, FILTER_SANITIZE_EMAIL); //remove invalid email characters
			}				
			return filter_var($string, FILTER_VALIDATE_EMAIL); //returns the email if valid, false if not
			
		}
    
	/** validate_url
	 * Validates a web address and optionally removes illegal characters
	 *
	 * @param string //web address to be checked (expects http/s://)
	 * @param boolean //tells the function to remove illegal characters or not
	 * @return valid web address or false
	 */			
		public function validate_url($string, $clean = false){
			
			if($clean){
				$string = filter_var($string, FILTER_SANITIZE_URL, FILTER_FLAG_SCHEME_REQUIRED); //remove invalid url characters				
			}
			return filter_var($string, FILTER_VALIDATE_URL); //returns the url if valid, false if not
			
		}
	
	/** validate_email_list
	 * Validates a list of email addresses and optionally removes illegal characters
	 *
	 * @param string //email addresses to be checked (comma seperated list)
	 * @param boolean //tells the function to remove illegal characters or not
	 * @return valid list of email addresses or false
	 */			
		public function validate_email_list($string, $clean = false){
			
			$list = explode(",", trim($string));
			foreach($list as $k => $address){
				if(self::validate_email(self::clean_string(trim($address), $clean))){
					$list[$k] = self::validate_email(self::clean_string(trim($address), $clean));
				}
				else{
					unset($list[$k]);
				}
			}
			
			if(empty($list)){
				return false;
			}
			
			$list = implode(", ", $list);			
			return $list;
		}
		
	/** clean_string
	 * Cleans possible injection attempts from a string
	 *
	 * @param string
	 * @return a clean string
	 */			
		public function clean_string($string){		
			$string = trim($string); //Clean off whitespace on ends
			$string = preg_replace('=((<CR>|<LF>|0x0A/%0A|0x0D/%0D|\\n|\\r|content-type:|mime-version:|multipart/mixed|Content-Transfer-Encoding:|bcc:|cc:|to:|from:)\S).*=i', null, $string);//clean out injection attempts
			return $string; //send it back
		}

	/** _build_headers
	 * Builds the header field of the email using $_email
	 *
	 * @return valid header as a string
	 */			
		protected function _build_headers(){			
 			$headers = array();
			if(!empty(self::$_email['emailFrom'])){
				array_push($headers, 'From: ' . self::$_email['emailFrom']);
			}
			if(!empty(self::$_email['emailReplyTo'])){
				array_push($headers, 'Reply-To: ' . self::$_email['emailReplyTo']);
			}
			
			array_push($headers, 'X-Mailer: PHP ' . phpversion());
			
			if(!empty(self::$_email['bcc'])){
				array_push($headers, 'cc: ' . self::$_email['bcc']);
			}
			if(!empty(self::$_email['cc'])){
				array_push($headers, 'Bcc: ' . self::$_email['cc']);
			}

			return implode("\r\n",$headers);
			
		}
		
	/** send_email
	 * Initiates the send_mail process ensuring $_email is populated and captures and returns result of the attempt
	 *
     * <code>
     * require_once 'emailer.php';
     *
     * Emailer::send_email("emailTo@example.com", "emailFrom@example.com", "emailReplyTo@example.com", "Subject line", "Email Message",  "emailBCC@example.com", "emailC@example.com");
     * </code>
	 *
     * @param string $emailTo email destination 
     * @param string $emailFrom email source
     * @param string $emailReplyTo reply destination
     * @param string $subject the email subject line
     * @param string $message the email message
     * @param string $bcc the email blind carbon copy
     * @param string $cc the email carbon copy
	 * @return valid list of email addresses or false
	 */			
		public function send_email(){
			if(empty(self::$_email)){
				if(func_num_args() !== 7){
					return nl2br('Incorrect number of arguments! \r\n i.e. Emailer::send_email("exampleTo@domain.com", "exampleFrom@domain.com", "exampleReplyTo@domain.com", "subject string", "message string", "exampleBcc@domain.com", "exampleCc@domain.com");');
				}
				self::set_email(...func_get_args());
			}

			return self::_send_email();
				
		}

	/** _send_email
	 * Validates the email and sends if valid 
	 *
	 * @return true or a list of invalid fields
	 */			
		private function _send_email(){
			
			$valid = self::_load_email();
			if($valid === true){
				return mail(self::$_email['emailTo'], self::$_email['subject'], self::$_email['message'], self::_build_headers());
			}
			else{
				return nl2br($valid);
			}
			
		}
				
	}
		
?>
