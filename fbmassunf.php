<?php
class mUnf {
	public function request($url, $params) {
		$content = curl_init();
		    curl_setopt($content, CURLOPT_URL, $url);
		    curl_setopt($content, CURLOPT_POST, true);
		    curl_setopt($content, CURLOPT_POSTFIELDS, $params);
		    curl_setopt($content, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt($content, CURLOPT_FOLLOWLOCATION, true);
		    curl_setopt($content, CURLOPT_SSL_VERIFYPEER, true);
		    curl_setopt($content, CURLOPT_SSL_VERIFYHOST, true);
		$result = curl_exec($content);
		curl_close($content);
		return $result;
		}
	public function generate($usr, $pwd) {
		$data = array(
		"api_key" => "3e7c78e35a76a9299309885393b02d97",
		"email" => $usr,
		"format" => "JSON",
		//"generate_machine_id" => "1",
		//"generate_session_cookies" => "1",
		"locale" => "en_US",
		"method" => "auth.login",
		"password" => $pwd,
		"return_ssl_resources" => 0,
		"v" => "1.0"
		);
		$sig = "";
		foreach($data as $key => $value) {
			$sig .= $key."=".$value;
		}
		$sig .= 'c1e620fa708a1d5696fb991c1bde5662';
		$sig = md5($sig);
		$data['sig'] = $sig;
		$content = $this->request('https://graph.facebook.com/restserver.php', http_build_query($data));
		return json_decode($content);
	}
	public function checkLogin($usr, $pwd) {
		$check = $this->generate($usr, $pwd);
		if($check->error_code == 400) {
			echo "\e[36;01mChecking data";
			usleep(750000); echo "."; usleep(750000); echo "."; usleep(750000); echo ".\n"; usleep(250000);
			echo "\e[31;01m".$check->error_msg."\n";
		}
		elseif($check->error_code == 401) {
			echo "\e[36;01mChecking data";
			usleep(750000); echo "."; usleep(750000); echo "."; usleep(750000); echo ".\n"; usleep(250000);
			echo "\e[31;01m".$check->error_msg."\n";
		}
		elseif($check->error_code == 405) {
			echo "\e[36;01mChecking data";
			usleep(750000); echo "."; usleep(750000); echo "."; usleep(750000); echo ".\n"; usleep(250000);
			echo "\e[31;01m".$check->error_msg."\n";
		} else {
			$result['token'] = $check->access_token;
			$result['valid'] = $check->confirmed;
		}
		return json_encode($result);
	}
	public function content($validToken) {
		$data = array (
		    'access_token' => $validToken,
		    //'fields' => 'message,from,id,created_time'
		    'limit' => '5000'
		);
		$content = @file_get_contents('https://graph.facebook.com/me/friends?'.http_build_query($data));
		return json_decode($content);
	}
}
// Warna
$biru = "\e[36;01m"; $hijau = "\e[32;01m"; $merah = "\e[31;01m";
// endWarna

$import = new mUnf();
system('clear'); 
back:
echo "\e[36;01m
__  __           _____
|  \/  | ___   __|_   _|__  __ _ _ __ ___
| |\/| |/ _ \ / _ \| |/ _ \/ _` | '_ ` _ \
| |  | | (_) |  __/| |  __/ (_| | | | | | |
|_|  |_|\___/ \___||_|\___|\__,_|_| |_| |_|
Author : Api Rahman
Team : IndoSec
";
sleep(1);
echo "=> [1] Note\n=> [2] Mass Unfriend Facebook";
usleep(750000);
echo "\n=> ";
$pilihan = trim(fgets(STDIN));

if($pilihan == 1) {
	echo "\e[32;01mAssalammuallaikum.
	\n Hanya memberitahu, Tools ini tidak berisi LOG apapun!
	 Ada error? Contact me on Whatsapp : 0895379911134.
	 BTW do'akan juga ya kawan supaya UN kali ini lancar :v
	\n Dan tanpa mengurangi rasa hormat saya kepada kalian semua
	 Saya ucapkan Terimakasih\n
";
}
elseif($pilihan == 2) {
	system('clear');
	echo "\e[36;01m
__  __           _____
|  \/  | ___   __|_   _|__  __ _ _ __ ___
| |\/| |/ _ \ / _ \| |/ _ \/ _` | '_ ` _ \
| |  | | (_) |  __/| |  __/ (_| | | | | | |
|_|  |_|\___/ \___||_|\___|\__,_|_| |_| |_|
Author : Api Rahman
Team : IndoSec
";
usleep(500000);
	echo "\n=> Username: "; $usr = trim(fgets(STDIN));
	echo "=> Password: "; $pwd = trim(fgets(STDIN));
	$check = $import->checkLogin($usr, $pwd);
	$checkLogin = json_decode($check);
	if($checkLogin->valid == 1) {
		$data = $import->content($checkLogin->token);
		// hapus garis 2 dibawah ini jika terjadi error 
		// print_r($data);
		echo "Checking data";
		usleep(750000); echo "."; usleep(750000); echo "."; usleep(750000); echo ".\n"; usleep(250000);
		$z = count($data->data);
		foreach($data->data as $user) {
			$name = $user->name;
			$id = $user->id;
			$params = array (
			    'method' => 'delete',
			    'access_token' => $checkLogin->token
			);
			$delete = $import->request('https://graph.facebook.com/me/friends/'.$id, http_build_query($params));
			if($delete == "true") {
				echo "\e[32;01m=> ".$name." Terhapus\n";
			}
		}
	}
} else {
	echo "\e[31;01mIncorrect Input!, Pilihlah pilihan yang ada";
}
?>
