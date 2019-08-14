<?php
error_reporting(0);
class Curl {
    function __construct()
    {
       /*
        HELLO WORLD - NETWEZEN
       */
        $this->ch = curl_init();
    }
    public function get($url,$headers=false,$httpheader=false)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HEADER, $headers);
        if($httpheader != false)
        {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $httpheader);
        }
        $this->options();
        try
        {
            $this->objectResponse = curl_exec($this->ch);
            if($headers != false)
            {
                $header = substr($this->objectResponse, 0, curl_getinfo($this->ch, CURLINFO_HEADER_SIZE));
                $body = substr($this->objectResponse, curl_getinfo($this->ch, CURLINFO_HEADER_SIZE));
                return array($header,$body);
            } else {
                return $this->objectResponse;
            }
        } catch (Exception $e)
        {
            die("Exception: ".$e->getMessage()."");
        }
        $this->close();        
    }
    public function post($url,$body,$headers=false,$httpheader=false)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HEADER, $headers);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 60);
        if($httpheader != false)
        {
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, $httpheader);
        }
        $this->options();
        try {
            $this->objectResponse = curl_exec($this->ch);
            if($headers != false)
            {
                $header = substr($this->objectResponse, 0, curl_getinfo($this->ch, CURLINFO_HEADER_SIZE));
                $body = substr($this->objectResponse, curl_getinfo($this->ch, CURLINFO_HEADER_SIZE));
                return array($header,$body);
            } else {
                return $this->objectResponse;
            }
        } catch(Exception $e)
        {
            die("Caught  Exception: ".$e->getMessage()."");
        }
        $this->close();
    }
    protected function close()
    {
        return curl_close($this->ch);
    }
    private function options()
    {
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        return $this->ch;
    }
    private function data()
	{
		$get = $this->get('https://fakenametool.net/random-name-generator/random/id_ID/indonesia/1',1);
	    preg_match('/<span>(.*?)<\/span>/', $get[1], $name);
	    if(isset($name[1]))
	    {
	    	$snama = explode(" ", strtolower($name[1]));
	    	$email = $snama[0].mt_rand(11111,99999)."@grr.la";
	    	return array('name' => $name[1], 'email' => $email);
	    } else {
	    	return false;
	    }
	}
	protected function headers()
	{
		$headers = array();
        $headers[] = 'Origin: https://passport.jd.id';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'X-Requested-With: XMLHttpRequest';
        $headers[] = 'Connection: keep-alive';

        return $headers;
	}
   public function register($password)
	{
		$data = $this->data();
		$body = 'ReturnUrl=ReturnUrl%3Dhttps%253A%252F%252Fm.jd.id%252Fuser%252Fmain%26_ga%3D2.152177849.1001472682.1553859131-1287252081.1553859131&spreadUserPin=&cpsPin=&phone=&email='.$data['email'].'&password='.$password.'&smsCode=&eid=&fp=&mode=EMail';
		$send = $this->post('https://passport.jd.id/register', $body,1, $this->headers());

		if(json_decode($send[1],1)['success'] == true)
		{
			$optToken = json_decode($send[1],1)['data']['optToken'];
			return $data['email'];
		} else {
			return false;
		}
	}
	public function verif($url)
	{
		$send = $this->get($url,1);
		if(preg_match('/registrasi berhasil/', $send[1]))
		{
			return true;
		} else if(preg_match('/melewati batas waktu/', $send[1])) {
			return 'continue';
		} else {
			return false;
		}
	}
}
class jdClass
{
	function __construct()
	{
		// YarzCode
	}
	private function data()
	{
		$get = $this->curl('https://fakenametool.net/random-name-generator/random/id_ID/indonesia/1');
	    preg_match('/<span>(.*?)<\/span>/', $get[1], $name);
	    if(isset($name[1]))
	    {
	    	$snama = explode(" ", strtolower($name[1]));
	    	$email = $snama[0].mt_rand(11111,99999)."@grr.la";
	    	return array('name' => $name[1], 'email' => $email);
	    } else {
	    	return false;
	    }
	}
	public function register()
	{
		$data = $this->data();
		$body = 'ReturnUrl=ReturnUrl%3Dhttps%253A%252F%252Fm.jd.id%252Fuser%252Fmain%26_ga%3D2.152177849.1001472682.1553859131-1287252081.1553859131&spreadUserPin=&cpsPin=&phone=&email='.$data['email'].'&password=otptoken123&smsCode=&eid=&fp=&mode=EMail';
		$send = $this->curl('https://passport.jd.id/register', $body, $this->headers());

		if(json_decode($send[1],1)['success'] == true)
		{
			$optToken = json_decode($send[1],1)['data']['optToken'];
			return $data['email'];
		} else {
			return false;
		}
	}
	public function getEmail($email)
	{
		$check = @explode("onClick=\"openEmail('$email',",$this->curl('http://mailnesia.com/mailbox/'.$email));
		if(count($check)>=1)
		{
			$uid = @explode(');', $check[1])[0];
			$getMsg = $this->curl('http://mailnesia.com/mailbox/'.$email.'?newerthan='.$uid.'&noheadernofooter=ajax');
			$getVerif = htmlspecialchars_decode(@explode('"',@explode('<a style="color:#333;" href="',$getMsg)[1])[0]);
			if(empty($getVerif))
			{
				return false;
			} else {
				return $getVerif;
			}
		}
	}
	public function cookies()
	{
		$cok = $this->curl('https://passport.jd.id/login?ReturnUrl=https://m.jd.id/user/main&_ga=2.92854101.999676485.1553857876-377239275.1545190939');
		preg_match_all('/Set-Cookie: (.*?);/', $cok[0], $cookies);
		preg_match("/'(.*?)':'(.*?)'/", $cok[1], $ada);
		$fill = $ada[1].'='.$ada[2];
		$cokz = '';
		foreach($cookies[1] as $cok)
		{
			$cokz .= $cok."; ";
		}
		return array($cokz, $fill);
	}
	public function login($email,$password)
	{
		$cok = $this->cookies();
		$body = 'ReturnUrl=https%253A%252F%252Fm.jd.id&publicM=&publicE=&publicUuid=&account='.$email.'&password='.$password.'&validateCode=&eid=SWVPDERF6MVSXGQTU6BV6D6DNI3EX4SUIKXNWZ7MUWNO626NXHSGTZWNLZO7RCETE6BKAQ23M7VRH43HBL237XZV6U&fp=&'.$cok[1];
		$head = $this->headers();
		$head[] = 'Cookie: '.$cok[0];
		$login = $this->curl('https://passport.jd.id/loginService', $body, $head);
		if(json_decode($login[1],1)['success'] == true)
		{
		   preg_match_all('/Set-Cookie: (.*?);/', $login[0], $cookies);
		   $cokz='';
		   foreach($cookies[1] as $cok)
		   {
			$cokz .= $cok."; ";
		   }
		   return array($cokz, 'ok');
		}else{
			return 'Error login';
		}
	}
	public function jdGPC($cookie,$phone)
	{
		$headers = array();
		$headers[] = 'Accept: text/javascript, application/javascript, application/ecmascript, application/x-ecmascript, */*; q=0.01';
		$headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
		$headers[] = 'Referer: https://c.jd.id/ls/confirm_order.html?count=1&supportGooglePlay=1&skuId=505324695';
		$headers[] = 'Sec-Fetch-Mode: cors';
		$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36';
		$headers[] = 'Cookie: '.$cookie;
		$headers[] = 'X-Requested-With: XMLHttpRequest';

		$submitPhone = $this->curl('https://c.jd.id/ls/changeMobile.html?callback=', 'phoneNo='.$phone, $headers);
		$xCurl = $this->curl('https://c.jd.id/ls/submitOrder.html?callback=','skuId=505324695&count=1&supportGooglePlay=1',$headers);

		if(json_decode($xCurl[1])->success == true)
		{
			preg_match('/transactionNo=(.*?)&/', json_decode($xCurl[1])->model->payUrl, $trxNo);
            preg_match('/uid=(.*?)&/', json_decode($xCurl[1])->model->payUrl, $eid);
            $oid = json_decode($xCurl[1])->model->orderId;
            $getPromo = $this->curl('https://jdpay.jd.id/api/checkPromoInfo', 'transactionNo='.$trxNo[1].'&payType=5&bankName=gopay&cardNo=&bizId=gopay&payAmount=22000.00', $headers);
            $promoId = json_decode(json_decode($getPromo[1]))->data->rewardId;
            $heade = array();
            $heade[] = 'Sec-Fetch-Mode: cors';
            $heade[] = 'Sec-Fetch-Site: same-origin';
            $heade[] = 'Origin: https://jdpay.jd.id';
            $heade[] = 'Accept-Language: en-US,en;q=0.9';
            $heade[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36';
            $heade[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
            $heade[] = 'Accept: application/json, text/javascript, */*; q=0.01';
            $heade[] = 'X-Requested-With: XMLHttpRequest';
            $heade[] = 'Cookie: '.$cookie;
            $heade[] = 'Connection: keep-alive';
		    $body = 'eid='.$eid[1].'&transactionNo='.$trxNo[1].'&payMethodMain=EWT&payMethodSub=EWT_GO_PAY&promotionResourceId='.$promoId.'&couPon=true&optAmount=12000';
		    //eid=SWVPDERF6MVSXGQTU6BV6D6DNI3EX4SUIKXNWZ7MUWNO626NXHSGTZWNLZO7RCETE6BKAQ23M7VRH43HBL237XZV6U&transactionNo=2019081201133827282097057587631&payMethodMain=EWT&payMethodSub=EWT_GO_PAY&promotionResourceId=2981329&couPon=true&optAmount=12000
            $getPaymentInfo = $this->curl("https://jdpay.jd.id/api/ewtPay", $body,$heade);
            if(json_decode(json_decode($getPaymentInfo[1]))->success == true)
            {
            	 return array("amount" => json_decode(json_decode($getPaymentInfo[1]))->data->amount, "tref" => str_replace(array("gojek://gopay/merchanttransfer?","&amount=12000&activity=GP:RR","tref="),array("","",""), json_decode(json_decode($getPaymentInfo[1]))->data->deeplinkRedirectUrl), "orderId" => trim($oid));
            } else {
            	 return array("amount" => "22000.00");
            }
		} else {
			return array("failed" => true);
		}
	}
	public function getInfoOrder($id,$cookie)
	{
        $headers = array();
		$headers[] = 'Accept: text/javascript, application/javascript, application/ecmascript, application/x-ecmascript, */*; q=0.01';
		$headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
		$headers[] = 'Referer: https://c.jd.id/ls/confirm_order.html?count=1&supportGooglePlay=1&skuId=505324695';
		$headers[] = 'Sec-Fetch-Mode: cors';
		$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.100 Safari/537.36';
		$headers[] = 'Cookie: '.$cookie;
		$headers[] = 'X-Requested-With: XMLHttpRequest';
		$getInfo = $this->curl('https://uorder.jd.id/user/order/orderDetail','orderId='.$id.'&dataType=json',$headers);
		//print_r($getInfo[1]);
		if(json_decode($getInfo[1])->success == true)
		{
			return array('code' => json_decode($getInfo[1])->redeemCodeVo->redeemCode, 'sn' => json_decode($getInfo[1])->redeemCodeVo->pmcTransId);
		} else {
			return false;
		}
	}
	function bayar($tref,$token,$pin)
	{
         $headers = array();
		 $headers[] = 'Authorization: Bearer '.$token;
         $headers[] = 'pin: '.$pin;
         $secret = '83415d06-ec4e-11e6-a41b-6c40088ab51e';
         $headers[] = 'Content-Type: application/json';
         $headers[] = 'X-AppVersion: 2.28.2';
         $headers[] = "X-Uniqueid: ac94e5d0e7f3f".rand(111,999);
         $headers[] = 'X-Location: -6.180495,106.824992';

         $xPay = $this->curl("https://api.gojekapi.com/v2/authorize_payment_request", '{"promotion_ids":[],"reference_id":"'.$tref.'","token":"eyJ0eXBlIjoiR09QQVlfV0FMTEVUIiwiaWQiOiIifQ=="}', $headers);

         if(json_decode($xPay[1])->success == true)
         {
         	return 'ok';
         } else {
         	//print_r($xPay);
            preg_match('/"message":"(.*?)"/', $xPay[1], $isWrongMessage);
         	return array('success' => false, "msg" => $isWrongMessage[1]);
         }

	}
	protected function headers()
	{
		$headers = array();
        $headers[] = 'Origin: https://passport.jd.id';
        $headers[] = 'Accept-Language: en-US,en;q=0.9';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Accept: application/json, text/plain, */*';
        $headers[] = 'X-Requested-With: XMLHttpRequest';
        $headers[] = 'Connection: keep-alive';

        return $headers;
	}
	public function curl($url, $post=false, $httpheader=false)
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        if($post != false)
        {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if($httpheader != false)
        {
        	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        }
	    $response = curl_exec($ch);
	    $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
	    $body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        curl_close($ch);
        return array($header, $body);
	}
}

function curl($url, $post=false, $httpheader=false)
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        if($post != false)
        {
			$httpheader[] = "Content-Length: ".strlen($post);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if($httpheader != false)
        {
        	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
        }
	    $response = curl_exec($ch);
        curl_close($ch);
        return $response;
}
$class = new \Curl;
$jdClass = new \jdClass;
$serverFile = "server.files";
$passwordSettings = "otptoken123";

if(!file_exists($serverFile))
{
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    $headers[] = 'X-AppVersion: 2.28.2';
    $headers[] = "X-Uniqueid: ac94e5d0e7f3f".rand(111,999);
    $headers[] = 'X-Location: -6.180495,106.824992';
    Awal:
	echo "[-] Penginputan Server...\n";
	echo "Phone: ";
	$phone = trim(fgets(STDIN));
    $sendServerOTP = $class->post('https://api.gojekapi.com/v3/customers/login_with_phone', '{"phone":"'.$phone.'"}',1, $headers);
    $dataServer = json_decode($sendServerOTP[1]);
    if($dataServer->success == true)
    {
        $secret = '83415d06-ec4e-11e6-a41b-6c40088ab51e';
        inputOTP:
    	echo "OTP: ";
        $otp = trim(fgets(STDIN));
	    $sends = $class->post('https://api.gojekapi.com/v3/customers/token', '{"scopes":"gojek:customer:transaction gojek:customer:readonly","grant_type":"password","login_token":"'.$dataServer->data->login_token.'","otp":"'.$otp.'","client_id":"gojek:cons:android","client_secret":"'.$secret.'"}',1, $headers);
	    $datas = @json_decode($sends[1]);
	    if($datas->success == true)
	    { 
	    	file_put_contents($serverFile, $datas->data->access_token);
	    	echo "Server Token telah Tersimpan.\n";
	    	echo "Go to Menu (y/N): ";
	    	if(trim(fgets(STDIN)) == 'y')
	    	{
	    		goto menu;
	    	} else {
	    		die();
	    	}
	    } else { 
	    	echo "Ups! OTP Salah coba Input lagi ya...";
	    	goto inputOTP;
	    }
        } else {
        	echo "Ups! Gagal mengirim kode OTP, Silahkan coba kembali...\n\n";
        	goto Awal;
        }
} else {
	menu:
	$serverToken = file_get_contents($serverFile);
	$headersGojek = array();
    $headersGojek[] = 'Content-Type: application/json';
    $headersGojek[] = 'X-AppVersion: 2.28.2';
    $headersGojek[] = "X-Uniqueid: ac94e5d0e7f3f".rand(111,999);
    $headersGojek[] = 'X-Location: -6.180495,106.824992';
    $headersGojek[] = 'Authorization: Bearer '.$serverToken;

    $ProfileDetail = $class->get("https://api.gojekapi.com/wallet/profile/detailed", 1, $headersGojek);

    if(preg_match('/Your session is expired/', $ProfileDetail[1]))
    {
    	echo "Ups! Session kamu sudah expired. Login lagi yaa...\n\n";
    	unlink($serverFile);
    	goto Awal;
    }
    $menuList = array("1","2");
    echo "\nList Menu:\n1. PAY Google Play\n";
    InputMenu:
    echo "Select menu: ";
    $selectedMenu = trim(fgets(STDIN));

    if(!in_array($selectedMenu,$menuList))
    {
    	echo "Ups! Menu tidak tersedia, Input lagi...\n";
    	goto InputMenu;
    } else {
    	if($selectedMenu == '1')
    	{
    		echo "Input PIN GO-PAY: ";
    		$pinGOPAY = trim(fgets(STDIN));
    		echo "Input Nomor (Penerima Voucher): ";
    		$nomorPenerima = trim(fgets(STDIN));
    		echo "Input Jumlah Pembelian : ";
    		$quantityOrder = trim(fgets(STDIN));
    		echo "Input File to Save GPC : ";
    		$saveFiles = trim(fgets(STDIN));
    		$i=1;
    		$redCode = '';
    		while($i<$quantityOrder+1) {
    			   echo "Proses ke ".$i." dari ".$quantityOrder."\n";
    		        Register:
    		        echo "\n[1/6] Proccessing register to JD.ID\n";
    		        $registerJD = trim($class->register($passwordSettings));
					$email = str_replace("@grr.la","",$registerJD);
					$headers = array();
						$headers[] = "Host: www.guerrillamail.com";
						$headers[] = "Connection: close";
						$headers[] = "Origin: https://www.guerrillamail.com";
						$headers[] = "Authorization: ApiToken 567305d8e530343ed62745f7eb90ee4851cc3f70ae89190112a12ae128922195";
						$headers[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";						
						$headers[] = "Accept: application/json, text/javascript, */*; q=0.01";
						$headers[] = "Save-Data: on";
						$headers[] = "X-Requested-With: XMLHttpRequest";
						$headers[] = "User-Agent: Mozilla/5.0 (Linux; Android 5.1.1; SAMSUNG SM-G935FD) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.101 Safari/537.36";
						$headers[] = "Referer: https://www.guerrillamail.com/";
						$headers[] = "Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7";
						$headers[] = "Cookie: PHPSESSID=ou5s2as0cejuoomgiq".rand(12345678,99999999);
					$gg = curl('https://www.guerrillamail.com/ajax.php?f=set_email_user', "email_user=$email&lang=en&site=guerrillamail.com&in=+Set+cancel", $headers);
					if($registerJD !== false)
    		        {
    		        	echo "[2/6] Register sukses email : ".trim($registerJD)."\n";
      		        	urlDapetin:
    		        	echo "[3/6] Getting Verification Email...\n";
						$a = 1;
						while(true){
							echo "\r".$a++." Seconds";
							$check = @json_decode(curl('https://www.guerrillamail.com/ajax.php?f=get_email_list&offset=0&site=guerrillamail.com&in='.$email.'&_=1565625019954', false, $headers),true)['list'][0];
							if($check['mail_from']=="no-reply@jd.id")
							{
								break;
							} else {
								continue;
							}
						}
						$id = $check['mail_id'];
						$getMsg = @json_decode(curl('https://www.guerrillamail.com/ajax.php?f=fetch_email&email_id='.$id.'&site=guerrillamail.com&in='.$email.'&_=1565625019959', false, $headers),true);
						$getVerif = @explode('">',@explode('style="font-weight:bold;"><a href="',$getMsg['mail_body'])[1])[0];
						if(empty($getVerif))
						{
							$verifUrl = false;
						} else {
							$verifUrl = $getVerif;
						}
    		        	$urlToVerif = $verifUrl;
    		        	if($verifUrl !== false)
    		        	{
    				        echo "\r[4/6] Mencoba Verifikasi.. URL Verifikasi : ".$urlToVerif."\n";
    				        $goVerif = $class->verif(trim($urlToVerif));
    				        if($goVerif == true)
    				        {
    				        	echo "[5/6] Verifikasi Berhasil. Mem proses pembelian...\n";
    				        	$login = $jdClass->login(trim($registerJD), $passwordSettings);
    				        	$goOrder = $jdClass->jdGPC($login[0],$nomorPenerima);
    				        	if($goOrder['amount'] == '12000.00')
    				        	{
    				        		Bayar:
    				        		$bayar = $jdClass->bayar($goOrder['tref'],$serverToken,$pinGOPAY);
    				        		if($bayar == 'ok')
    				        		{
    				        		    $ProfileDetail = $class->get("https://api.gojekapi.com/wallet/profile/detailed", 1, $headersGojek);
    				        		    $saldoGoPay = json_decode($ProfileDetail[1])->data->balance;
    				        			echo "[6/6] Pembayaran berhasil dilakukan, Sisa saldo GO-PAY saat ini: ".$saldoGoPay."\n";
    				        			sleep(10);
    				        			$infoOrder = $jdClass->getInfoOrder($goOrder['orderId'],$login[0]);
    				        			file_put_contents($saveFiles, "Kode: ".$infoOrder['code']." - SN : ".$infoOrder['sn']."\n", FILE_APPEND);
    				        			$redCode .= "Code: ".$infoOrder['code']." - SN : ".$infoOrder['sn']."\n";
    				        		} else {
    				        			echo "[6/6] Pembayaran gagal dilakukan karena ".$bayar['msg']."\n";
    				        		}
    				        	} else {
    				        		echo "Ups! Kami Skip. Tidak ada PROMO dalam akun ini.\n";
    				        	}
    				        } else {
    				        	if($goVerif == 'continue')
    				        	{
    				        		echo "Ups! Verifikasi error. Kita ulangi dari awal saja ya!...\n";
    				        		continue;
    				        	} else {
    				             	echo "Ups! Verifikasi gagal... Kita ulangi dari awal saja ya...\n\n";
    				        	    goto Register;
    				        	}
    				        }
    			        } else {
    			        	echo "Ups! Gagal dapetin url untuk verifikasi. Tenang coba lagi ya.\n";
    			            goto urlDapetin;
    		         	}
    		          } else {
    		        	echo "Ups! Register Gagal. Tenang Kita Coba lagi ya.\n";
    			        goto Register;
    		         }
    		         $i++;
    		     }
    		     echo "========== Daftar Code GPC ============\n";
    		     echo $redCode;
    		     echo "========== Daftar Code GPC ============\n";
    		     echo "Script shutdown... Tugas telah selesai thanks for buying :) \n";
    		 }
    		}
    	}
