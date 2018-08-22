<h1>View Extended Properties</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<fieldset>
<legend>General Properties</legend>

<table class="collapsed elbowroom verticalHeaders">

  <tr>
    <th>Type</th><td>{PAGE}</td>
  </tr>

  <tr>
    <th nowrap>Unique Identifier</th><td>{OBJECT}</td>
  </tr>

  <tr>
    <th>Database Table</th><td>{PAGE}</td>
  </tr>

  <tr>
    <th>Owner</th><td><a
    href="members/member/read/{C_OWNER}">{OWNER_FIRST_NAME}
    {OWNER_LAST_NAME}</a></td>
  </tr>

  <tr>
    <th>Creator</th><td><a
    href="members/member/read/{C_CREATOR}">{CREATOR_FIRST_NAME}
    {CREATOR_LAST_NAME}</a></td>
  </tr>

  <tr>
    <th>Group</th><td><b>{GROUP}</b>
    <i>This is the group that owns the object.  Do not confuse this with
    a member's group membership.  They are totally separate.</i></td>
  </tr>

  <tr>
    <th>Created</th><td>{C_CREATED_DATE}</td>
  </tr>

  <tr>
    <th>Status</th>
    <td>{c_status|bitmaskString,'status_id'}</td>
  </tr>

</table>

</fieldset>
<fieldset>
<legend>Unix Permissions</legend>

<p>The system stores information about who is allowed to do what to this
object.  The object's UNIX-style permissions are {C_UNIXPERMS|decoct} octal, which
translates to {C_UNIXPERMS|decbin} in binary.</p>

<table class="borders collapsed">

  <tr>
    <td></td><th>Owner</th><th>Group</th><th>Other</th>
  </tr>

  <tr>
    <th>Read</th><td>{OWNER_READ}</td><td>{GROUP_READ}</td><td>{OTHER_READ}</td>
  </tr>

  <tr>
    <th>Write</th><td>{OWNER_WRITE}</td><td>{GROUP_WRITE}</td><td>{OTHER_WRITE}</td>
  </tr>

  <tr>
    <th>Delete</th><td>{OWNER_DELETE}</td><td>{GROUP_DELETE}</td><td>{OTHER_DELETE}</td>
  </tr>

</table>
</fieldset>

</div>
