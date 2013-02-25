<?
   $wiky=new wiky;
   $wikiParser=new wikiParser();
?>
<div class="userdata">
<table width=100%>
<tr>
  <th colspan=2><div class="username"><?= $char_info['char_name'];?></div><th>
</tr>
<tr class="email">
  <td class="char_info-header">Played by:</td>
  <td><?= $char_info['realname'];?>(<?= $char_info['username'];?>)</td>
</tr>
<tr class="email">
  <td class="char_info-header">System:</td>
  <td><?= $char_info['system'];?></td>
</tr>
<tr class="email">
  <td class="char_info-header">Basic Description:</td>
  <td><?= $char_info['base_desc'];?></td>
</tr>
<th colspan=2><div class="username">Character Sheet</div><th>
<tr>
<td colspan=2 class="simple_sheet">
  <? echo $wiky->parse($char_info['sheet']);?>
</td>
</tr>
<th colspan=2><div class="username">History</div><th>
<tr>
<td colspan=2 class="simple_sheet">
  <? echo $wiky->parse($char_info['history']);?>
</td>
</tr>
<tr>
  <th colspan=2><div class="username"><?= $char_info['char_name'];?>'s Conditions</div><th>
</tr>
<? error_log(count($conditions));
  if (count($conditions)==0): ?>
<tr> <td>This character has no conditions attached to him</td></tr>
<? else: ?>
<tr> <th>Condition</th><th>Description</th></tr>
  <? foreach ($conditions as $condition):
  ?>
      <tr> <td>
<?= $condition["description"]; ?></a></td><td>
<?= $condition["value"]; ?></td></tr>
  <? endforeach;?>
<? endif; ?>
</table>
