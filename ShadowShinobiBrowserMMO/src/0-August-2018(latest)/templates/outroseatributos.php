<?php
$template = <<<THEVERYENDOFYOU
<td><form action="outroseatributos.php?do=atributos" method="post">
<table>
<tr  bgcolor="#452202"><td colspan="2"><center><font color="white">Distribute Points</font></center></td></tr>
<tr bgcolor="#FFF1C7"><td >Agility<img src="images/raio.gif" title="Lightning Element"> </td><td>Add Points: <input type="text" name="agilidadep" size="10" maxlength="30" /></td></tr>
<tr bgcolor="#E4D094"><td >Luck<img src="images/agua.gif" title="Water Element"> </td><td>Add Points: <input type="text" name="sortep" size="10" maxlength="30" /></td></tr>
<tr bgcolor="#FFF1C7"><td>Determination<img src="images/fogo.gif" title="Fire Element"> </td><td>Add Points: <input type="text" name="determinacaop" size="10" maxlength="30" /></td></tr>
<tr bgcolor="#E4D094"><td  >Precision<img src="images/vento.gif" title="Wind Element"> </td><td>Add Points: <input type="text" name="precisaop" size="10" maxlength="30" /></td></tr>
<tr bgcolor="#FFF1C7"><td  >Intelligence<img src="images/terra.gif" title="Earth Element"> </td><td>Add Points: <input type="text" name="inteligenciap" size="10" maxlength="30" /></td></tr>
<tr bgcolor="#E4D094"><td colspan="2"><div class="buttons" style="margin-left: 3px;"><center><button type="submit" class="positive" name="submit"><img src="layoutnovo/dropmenu/b1.gif"> Add</button>
<button type="reset" class="negative" name="reset"><img src="layoutnovo/dropmenu/b3.gif"> Clear</button>
</center></div></td></tr>
</table></td></tr></table></center>
</form>
<b>Legend</b>:
<ul>
<li /><b>Agility</b>: Increases the chance of evading an enemy attack.
<li /><b>Luck</b>: Increases the chance of an item dropping.
<li /><b>Precision</b>: Increases the chance of landing a hit on the enemy.
<li /><b>Intelligence</b>: Increases the chance the enemy stays asleep.
<li /><b>Determination</b>: Increases the chance of critical hits on <font color=red><b>physical</b> attacks</font>.
<li />All attributes increase the power of Arts tied to their corresponding element.
</ul>
THEVERYENDOFYOU;
?>
