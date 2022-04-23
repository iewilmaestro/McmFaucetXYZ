<?php
error_reporting(0);
const 
b = "\033[1;34m",
c = "\033[1;36m",
d = "\033[0m",
h = "\033[1;32m",
k = "\033[1;33m",
m = "\033[1;31m",
n = "\n",
p = "\033[1;37m",
u = "\033[1;35m";

function Run($u, $h = 0, $p = 0){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $u);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_COOKIE,TRUE);
	if($p){
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
	}
	if($h){
		curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
	}
	curl_setopt($ch, CURLOPT_HEADER, true);
	$r = curl_exec($ch);
	$c = curl_getinfo($ch);
	if(!$c) return "Curl Error : ".curl_error($ch); else{
		$hd = substr($r, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		$bd = substr($r, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		curl_close($ch);
		return array($hd,$bd)[1];
	}
}
function answer($x,$y,$z){
	if($x + $y == $z){
		return "add";
	}elseif($x - $y == $z){
		return "sub";
	}elseif($x * $y == $z){
		return "multiply";
	}else{
		return 0;
	}
}
function S($namadata){
	if(file_exists($namadata)){
		$data = file_get_contents($namadata);
	}else{
		$data = readline("Input ".$namadata." > ");
		file_put_contents($namadata,$data);
	}
	return $data;
}
s('Cookie');s('User_agent');
$line = u.str_repeat('~',50)."\n";

titel:
system("clear");
print h."Script By ".p."iewil\n";
print $line;

$h[] = "Host : mcmfaucets.xyz";
$h[] = "cookie: ".s('Cookie');
$h[] = "user-agent: ".s('User_agent');


$r = Run('https://mcmfaucets.xyz/user/dashboard',$h);
$user = explode('</span>',explode('<span class="user-name font-weight-bolder">',$r)[1])[0];//iewilmaestro
$balance = explode('</h4>',explode("id='balanc'>",$r)[1])[0];//0
print h."Username : ".k.$user."\n";
print h."Balance  : ".k.$balance."\n";
print $line;

mangkat:
while(true){
	$r = Run('https://mcmfaucets.xyz/user/faucet',$h);
	if(preg_match('/Cloudflare/',$r) || preg_match('/Firewall/',$r)){
		print m."kena kopler, update cookie maseh\n";
		unlink('Cookie');
		s('Cookie');
		goto titel;
	}
	$time = explode('</h3>',explode("id='timer'>",$r)[1])[0];//00:58
	if(!$time=="Ready TO Claim"){
		goto mad;
	}
	$csrf = explode('"',explode('name="csrf_token" value="',$r)[1])[0];
	$hidden = explode('"',explode('<input type="hidden" value="',$r)[1])[0];
	$ques = explode('</h1>',explode('<h1>Question: ',$r)[1])[0];//52 ___ 8 = 416
	$q = explode(' ',$ques);
	$x = $q[0];
	$y = $q[2];
	$z = $q[4];
	$answer = answer($x,$y,$z);
	if($answer <= null ){
		goto mangkat;
	}
	$data = "csrf_token=".$csrf."&answer=".$answer."&hidden=".$hidden;
	Run('https://mcmfaucets.xyz/user/faucet/verify',$h,$data);
	$r = Run('https://mcmfaucets.xyz/user/dashboard',$h);
	$bal = explode('</h4>',explode("id='balanc'>",$r)[1])[0];
	if($balance < $bal){
		print h."Success Claim Faucet\n";
		print c."New Balance  : ".k.$bal."\n";
		print $line;
	}
	sleep(5);
	mad:
	$r = Run('https://mcmfaucets.xyz/user/dashboard',$h);
	if(preg_match('/Cloudflare/',$r) || preg_match('/Firewall/',$r)){
		print m."kena kopler, update cookie maseh\n";
		unlink('Cookie');
		s('Cookie');
		goto titel;
	}
	$bal = explode('</h4>',explode("id='balanc'>",$r)[1])[0];
	$r = Run('https://mcmfaucets.xyz/user/madclaim',$h);
	$time = explode('</h3>',explode("id='timer'>",$r)[1])[0];//00:58
	if(!$time=="Ready TO Claim"){
		goto mangkat;
	}
	$csrf = explode('"',explode('name="csrf_token" value="',$r)[1])[0];
	$hidden = explode('"',explode('<input type="hidden" value="',$r)[1])[0];
	$ques = explode('</h1>',explode('<h1>Question: ',$r)[1])[0];//52 ___ 8 = 416
	$q = explode(' ',$ques);
	$x = $q[0];
	$y = $q[2];
	$z = $q[4];
	$answer = answer($x,$y,$z);
	if($answer <= null ){
		goto mad;
	}
	$data = "csrf_token=".$csrf."&answer=".$answer."&hidden=".$hidden;
	Run('https://mcmfaucets.xyz/user/madclaim/verify',$h,$data);
	
	$r = Run('https://mcmfaucets.xyz/user/dashboard',$h);
	$balance = explode('</h4>',explode("id='balanc'>",$r)[1])[0];//0
	if($bal < $balance){
		print h."Success Claim MadFaucet\n";
		print c."New Balance  : ".k.$balance."\n";
		print $line;
	}
	sleep(5);
}
