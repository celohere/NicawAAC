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
require ('check.php');
$_SESSION['last_activity']=time();

//retrieve post data
$form = new Form('poll');
//check if any data was submited
if ($form->exists()){
	//make an array of options
    $options = explode("\n",trim($form->attrs['options']));
    if ($form->attrs['hidden'] == 'on') $hidden = true;
		else $hidden = false;
	$sql = new SQL();
	//store poll question
    $sql->myInsert('nicaw_polls',array('id' => null, 'minlevel' => (int)$form->attrs['level'], 'question' => $form->attrs['question'], 'startdate' => strToDate($form->attrs['startdate']), 'enddate' => strToDate($form->attrs['enddate']), 'hidden' => $hidden));
    $poll_id = $sql->PDO->lastInsertId();
	//store all poll options
	foreach($options as $option)
		$sql->myInsert('nicaw_poll_options',array('id' => null, 'poll_id' => $poll_id, 'option' => $option));
	//create news message
	$pollMsg = '<b>'.$form->attrs['question']."</b><br/>\n";
	$i = 0;
	foreach ($options as $option){
		$i++;
		$pollMsg.= $i.'. '.$option."<br/>\n";
	}
	$link = $cfg['server_href'].'voting.php?id='.$poll_id;
	$pollMsg.= "<br/>\n".'Voting ends on: '.date("jS F Y", strToDate($form->attrs['enddate'])).
	           "<br/>\n".'Characters of level '.(int)$form->attrs['level'].' or above may vote by clinking this link:'."<br/>\n".'<a href="'.$link.'>'.$link.'</a>';
	$sql->myInsert('nicaw_news',array('id' => null, 'title' => 'New Poll', 'creator' => 'PollMan', 'date' => strToDate($form->attrs['startdate']), 'text' => $pollMsg, 'html' => true));
	echo $sql->getError();
}else{
	//create new form
	$form = new IOBox('poll');
	$form->target = $_SERVER['PHP_SELF'];
	$form->addLabel('Create Poll');
	$form->addInput('question','text','',200);
	$form->addInput('level');
	$form->addInput('startdate','text',date('Y-m-d',time()));
	$form->addInput('enddate','text',date('Y-m-d',time()+604800));
	$form->addCode('One choise per line:');
	$form->addTextBox('options');
	$form->addCheckBox('hidden',false);
	$form->addClose('Cancel');
	$form->addSubmit('Save');
	$form->show();
}
?>