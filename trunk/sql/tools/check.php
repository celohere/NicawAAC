<?
$account = new Account($_SESSION['account']);
if (in_array($_SESSION['account'],$cfg['admin_accounts']) && $account->exists() || in_array($_SERVER['REMOTE_ADDR'], $cfg['admin_ip'])){
		//pass =)
}else{   //no pass
	die('Acccess denied for current account.');
}
?>