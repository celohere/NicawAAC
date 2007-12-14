<?
/*
    Copyright (C) 2007  Nicaw

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/
include ("../include.inc.php");
//load account if loged in
$account = new Account($_SESSION['account']);
($account->load()) or die('You need to login first. '.$account->getError());
//load guild
$guild = new Guild($_REQUEST['guild_name']);
if (!$guild->load()) throw new Exception('Unable to load guild.');
if ($guild->getAttr('owner_acc') != $_SESSION['account']) die('Not your guild');
//retrieve post data
$form = new Form('kick');
//check if any data was submited
if ($form->exists()){
	$player = new Player($form->attrs['player']);
	if ($player->load()){
		if ($player->getAttr('account') != $guild->getAttr('owner_acc')){
			if ($guild->memberLeave($player->getAttr('id'))){
				$guild->save();
				//success
				$msg = new IOBox('message');
				$msg->addMsg('You have kicked '.htmlspecialchars($player->getAttr('name')));
				$msg->addRefresh('OK');
				$msg->show();
			}else $error = 'Cannot kick from guild';
		}else $error = 'You can\'t kick yourself. Use leave function';
	}else $error = 'Player cannot be loaded';
	if (!empty($error)){
		//create new message
		$msg = new IOBox('message');
		$msg->addMsg($error);
		$msg->addClose('OK');
		$msg->show();
	}
}else{
	//make a list of member characters
	$members = $guild->getAttr('members');
	foreach ($members as $member)
		$list[$member['name']] = $member['name'];
	$members = $guild->getAttr('invited');
	foreach ($members as $member)
		$list[$member['name']] = $member['name'];
	if (!isset($list)) die();

	//create new form
	$form = new IOBox('kick');
	$form->target = $_SERVER['PHP_SELF'].'?guild_name='.urlencode($_REQUEST['guild_name']);
	$form->addLabel('Kick Member');
	$form->addMsg('Select the character to kick.');
	$form->addSelect('player', $list);
	$form->addClose('Cancel');
	$form->addSubmit('Continue >>');
	$form->show();
}
?>