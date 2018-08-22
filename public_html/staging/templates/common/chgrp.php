<h1>Change Group</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>You are changing the group for object <b>{OBJECT}</b> in table <b>{TABLE}</b>.  Note that the
object's group is much like the group setting in UNIX file ownerships (read the
<tt>ls</tt> man page for the best explanation of this) and is NOT the group the
object "belongs to" but is rather the group that owns the object.  If you want
to set group memberships on a member, use the "Change Secondary Groups" option.</p>

<p class="notice"><b>Please read this</b>.  If you want to change which groups a
member is in, you are barking up the wrong tree.  Use the "Change Secondary
Groups" option from that member's details page.</p>

{SUCCESS:}
<p class="notice">The object's new group was saved.</p>
{:SUCCESS}

<form action="members/{PAGE}/chgrp/{OBJECT}"
method="POST">
<select name="group">
{GROUP:}
<option value="{C_UID}" {SELECTED}>{C_TITLE}</option>
{:GROUP}
</select>
<input type="submit" value="Change Group">
</form>

</div>
