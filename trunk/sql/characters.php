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
include ("include.inc.php");
$ptitle="Characters - $cfg[server_name]";
include("header.inc.php");
?>
<div id="content">
<div class="top">Character Lookup</div>
<div class="mid">
<form method="get" action="characters.php"> 
<input type="text" name="char"/> 
<input type="submit" value="Search"/> 
</form>
<?
if (!empty($_GET['char'])){
	$player = new Player($_GET['char']);
	if ($player->load()){
		echo '<hr/><table style="width: 100%"><tr><td><b>Name:</b> '.$player->getAttr('name')."<br/>\n";
		echo '<b>Level:</b> '.$player->getAttr('level')."<br/>\n";
		echo '<b>Magic Level:</b> '.$player->getAttr('maglevel')."<br/>\n";
		echo '<b>Vocation:</b> '.$cfg['vocations'][$player->getAttr('vocation')]['name']."<br/>\n";

		if ($player->isAttr('guild_name')){
			echo '<b>Guild:</b> '.$player->getAttr('guild_rank').' of <a href="guilds.php?id='.$player->getAttr(guild_id).'">'.$player->getAttr('guild_name').'</a><br/>'."\n";
		}
		
		$gender = Array('Female','Male');
		echo '<b>Gender:</b> '.$gender[$player->getAttr('sex')].'<br/>'."\n";
		if (!empty($cfg['temple'][$player->getAttr('city')]['name']))
			echo "<b>Residence</b>: ".ucfirst($cfg['temple'][$player->getAttr('city')]['name'])."<br/>";

		if ($player->isAttr('position')){
			echo "<b>Position: </b> ".$player->getAttr('position')."<br/>";
		}
		if ($player->getAttr('lastlogin') == 0)
			$lastlogin = 'Never';
		else
			$lastlogin = date("jS F Y H:i:s",$player->getAttr('lastlogin'));
		echo "<b>Last Login:</b> ".$lastlogin."<br/>\n";
		if ($player->getAttr('redskulltime') > 0) echo '<b>Frag time left:</b> '.ceil(($player->getAttr('redskulltime') - time())/60/60).' h</b><br/>';
		if ($cfg['show_skills']){
			echo "</td><td>";
			$sn = $cfg['skill_names'];
			for ($i=0; $i < count($sn); $i++){
				echo '<b>'.ucfirst($sn[$i]).':</b> '.$player->getSkill($i)."<br/>\n";
			}
			echo '</td></tr>';
		}
		echo '</table>';
		$account = new Account($player->getAttr('account'));
		if ($account->load())
			if (strlen($account->getAttr('comment'))>0){
				echo "<b>Comments</b><br/><div style=\"overflow:hidden\"><pre>".htmlspecialchars($account->getAttr('comment'))."</pre></div><br/>\n";
			}	
		echo '<hr/>';
		$deaths = $player->getDeaths();
		if ($deaths !== false && !empty($deaths)){
		echo '<b>Deaths</b><br/>';
			foreach ($deaths as $death){
				$killer = new Player($death['killer']);
				if ($killer->exists())
					$name = '<a href="characters.php?char='.$death['killer'].'">'.$death['killer'].'</a>';
				else
					$name = $death['killer'];
				echo '<i>'.date("jS F Y H:i:s",$player->getAttr('lastlogin')).'</i> Killed at level '.$death['level'].' by '.$name.'<br/>';
			}
		}
	}else{$error = "Unable to load player";}
}
?>
</div>
<div class="bot"></div>
</div>
<?include ("footer.inc.php");?>