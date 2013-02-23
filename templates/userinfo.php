<div class="userdata">
<table width=100%>
<tr>
  <th colspan=2><div class="username"><?= $userinfo['realname'];?>(<?= $userinfo['username'];?>)</div><th>
</tr>
<tr class="email"><td class="userinfo-header">Email:</td><td><?= $userinfo['email'];?></tr>
  <? if(isset($userinfo['aboutYou']) && !empty($userinfo['aboutYou'])) : ?>
<tr class="aboutYou"><td class="userinfo-header">About him:</td><td><?= $userinfo['aboutYou'];?></tr>
<? endif; ?>
  <? if(isset($userinfo['site']) && !empty($userinfo['site'])): ?>
<tr class="aboutYou"><tdclass="userinfo-header">Personal Website:</td><td><a href="<?= $userinfo['site'];?>"><?= $userinfo['site'];?></a></tr>
<? endif; ?>
</table>
<table width=100%>
<tr>
  <th colspan=3><div class="username"><?= $userinfo['realname'];?>(<?= $userinfo['username'];?>)'s Character Binder</div><th>
</tr>
<? if (count($binder)==0): ?>
<tr> <td>This user has no character's on its binder</td></tr>
<? else: ?>
<tr> <th>Character name</th><th>System</th><th>Basic Description</th></tr>
  <? foreach ($binder as $character):
  ?>
  <a href onClick='clonechar(<?= $character["id"] ;?>);'>
      <tr> <td>
<a href onClick='clonechar(<?= $character["id"] ;?>);'>
<?= $character["char_name"]; ?></a></td><td>
<?= $character["system"]; ?></td><td><?= $character["base_desc"];?></td></tr>
  <? endforeach;?>
<? endif; ?>
</table>
