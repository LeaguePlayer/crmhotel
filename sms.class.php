<?php
	 /****
	 * Скрипт для отправки СМС-сообщений через шлюз avisosms.ru
	 * Автор: Михаил Асташкевич
	 * Контакты: ICQ 417672423, Skype asmisha
	 *****/
	
	class SMSClass{
		private $User, $Pass;
		private $Gateway = 'http://api.avisosms.ru/sms/xml/1';
		private $Curl;
		
		private function CurlInit(){
			$ch = curl_init();
            
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			
			$this->Curl = $ch;
		}
		
		private function Send($URL, $POST){
			curl_setopt($this->Curl, CURLOPT_URL, $URL);
			curl_setopt($this->Curl, CURLOPT_POSTFIELDS, $POST);
			return curl_exec($this->Curl);
		}
		
		private function ParseResponse($Resp){
			if (preg_match('#<error>(.*)</error>#U', $Resp, $Result)){
				return array('Error' => $Result[1], 'MessageID' => 0);
			}else{
				preg_match_all('#<message.*id="([^"]*)">#U', $Resp, $Result);
				return array('Error' => false, 'MessageID' => $Result[1]);
			}
		}
		
		public function __construct($UserName, $Password){
			$this->User = $this->Encode($UserName);
			$this->Pass = $this->Encode($Password);
			$this->CurlInit();
		}
		
		private function Encode($s){
			return htmlentities($s, ENT_NOQUOTES, 'UTF-8');
		}
		
		public function SendSMS($Array){
			$msg = 
				"<request>\
				<send_messages>";
			foreach($Array as $i){
				$Destination = $this->Encode($i['Phone']);
				$Text = $this->Encode($i['Text']);
				$From = $this->Encode((isset($i['From']) ? $i['From'] : 'AvisoSMS'));
				$msg .= 
				"<message>\
					<destination_address>$Destination</destination_address>\
					<text>$Text</text>\
					<source_address>$From</source_address>\
				</message>";
			}
			$msg .= 
				"</send_messages>\
				<user>".$this->User."</user>\
				<password>".$this->Pass."</password>\
				</request>";
				
			$Answer = $this->Send($this->Gateway, $msg);
			return $this->ParseResponse($Answer);
		}
		
		public function GetBalance(){
			$msg = 
				"<request>\
				<get_sms_balance />\
				<user>".$this->User."</user>\
				<password>".$this->Pass."</password>\
				</request>";
			$Resp = $this->Send($this->Gateway, $msg);
			preg_match('#<sms_balance>(.*)</sms_balance>#U', $Resp, $Result);
			return $Result[1];
		}
		
		public function GetBillingBalance(){
			$msg = 
				"<request>\
				<get_billing_balance />\
				<user>".$this->User."</user>\
				<password>".$this->Pass."</password>\
				</request>";
			$Resp = $this->Send($this->Gateway, $msg);
			preg_match('#<billing_balance>(.*)</billing_balance>#U', $Resp, $Result);
			return $Result[1];
		}
		
		public function GetMessageStatus($MessageID){
			$MessageID = $this->Encode($MessageID);
			$msg = 
				"<request>\
				<get_messages_states>
				<message_id>$MessageID</message_id>
				</get_message_states>
				<user>".$this->User."</user>\
				<password>".$this->Pass."</password>\
				</request>";
			$Resp = $this->Send($this->Gateway, $msg);
			preg_match('#<message[^>]*>([^<>]*)</message>#', $Resp, $Result);
			return $Result[1];
		}
	}
?>
