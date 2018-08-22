<h1>Change Owner</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>You are changing the owner for object <b>{OBJECT}</b> in table <b>{TABLE}</b>.</p> 

{SUCCESS:} <p class="notice">The object's new owner was saved.</p> {:SUCCESS}

<form action="members/{PAGE}/chown/{OBJECT}" method="POST">
  <select name="owner">{OWNER:}
    <option value="{C_UID}" {SELECTED}>{C_LAST_NAME}, {C_FIRST_NAME}</option>{:OWNER}
  </select>
  <input type="submit" value="Change Owner">
</form>

</div>
