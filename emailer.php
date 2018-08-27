<?php

	class EmailForm {
		
		private $emailTo; //Who is the email going to?
		private $emailFrom; //Who is sending the email?
		private $emailReturn; //Who is the reply going to?
		private $subject; //What is the subject line?
		private $comment; //What is the content of the email?
		private $cc; //Any Carbon Copies?
		private $bcc; //Any Blind Carbon Copies?
		
		//Build our email object
		function __construct($emailTo, $emailFrom, $emailReturn, $subject, $comment, $bcc, $cc){
			
			$this->emailTo = $this->clean_string($emailTo);		
			
			$this->emailFrom = $this->clean_string($emailFrom);
			
			$this->emailReturn = $this->clean_string($emailReturn);
			
			$this->subject = $this->clean_string($subject);
			
			$this->comment = $this->clean_string($comment);
			
			$this->bcc = $this->clean_string($bcc);
			
			$this->cc = $this->clean_string($cc);
			
		}
		
		public function validate_email($string, $clean = false){
			
			if($clean){
				$string = filter_var($string, FILTER_SANITIZE_EMAIL); //remove invalid email characters
			}			
			return filter_var($string, FILTER_VALIDATE_EMAIL); //returns the email if valid, false if not
			
		}
		
		public function validate_url($string, $clean = false){
			
			if($clean){
				$string = filter_var($string, FILTER_SANITIZE_URL, FILTER_FLAG_SCHEME_REQUIRED); //remove invalid url characters				
			}
			return filter_var($string, FILTER_VALIDATE_URL); //returns the url if valid, false if not
			
		}		
		
		protected function clean_string($string){		
			$string = trim($string); //Clean off whitespace on ends
			$string = preg_replace('=((<CR>|<LF>|0x0A/%0A|0x0D/%0D|\\n|\\r|content-type:|mime-version:|multipart/mixed|Content-Transfer-Encoding:|bcc:|cc:|to:|from:)\S).*=i', null, $string);//clean out injection attempts
			return $string; //send it back
		}
		
		private function build_headers(){			
 			$headers = array(
				'From: ' . $this->emailFrom,
				'Reply-To: ' . $this->emailReturn,
				'X-Mailer: PHP ' . phpversion(),
				'cc: ' . $this->bcc,
				'Bcc: ' . $this->cc
			); 
			
			return implode("\r\n",$headers);
			
		}
		
		private function build_parameters(){
			//$params = '-f' . $this->emailFrom;
			$params = '';
			return $params;
		}
				
		public function send_email(){
			
			return mail($this->emailTo, $this->subject, $this->comment, $this->build_headers(),  $this->build_parameters());

		}
		
		
	}
	

	
?>