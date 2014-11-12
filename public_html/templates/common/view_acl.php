<h1>View Object ACL</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>Below is a list of all privileges that apply to object <b>{OBJECT}</b> in
table <b>{PAGE}</b>.  A privilege specifes <i>who</i> is allowed to take
<i>what action</i> on <i>what</i>.  If the privilege is granted globally, then
it is granted on every object in that table.  If it is granted to <i>self</i>,
then it specifies an action a member can perform on himself.</p>

<p>You may also <a
href="members/{PAGE}/add_privilege/{OBJECT}">define
a new privilege for this object</a>.</p>

{SOME:}
<table class="cleanHeaders top">
  <tr>
    <th>ID</th>
    <th>Who</th>
    <th>What Action</th>
    <th>Granted on What</th>
    <th>Statuses</th>
  </tr>
  {ROWS:}
  <tr>
    <td nowrap>{c_uid}</td>
    <td nowrap>{c_who_type} {c_who_uid} ({c_who})</td>
    <td nowrap>{c_action_title}</td>
    <td nowrap><b>{c_granted_on}</b> {c_table}({c_related_uid})</td>
    <td>{c_status|bitmaskString,'status_id'}</td>
  </tr>
  {:ROWS}
</table>
{:SOME}

{NONE:}
<p class="notice">There are no privileges for this object.</p>
{:NONE}

</div>
