<h1>Membership History for {C_FULL_NAME}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{SOME:}

<p>The following is a list of all memberships for <b>{C_FULL_NAME}</b>.</p>

<table class="cleanHeaders compact">
  <tr>
    <th>Membership</th>
    <th>Created</th>
    <th>Total Cost</th>
    <th>Status</th>
    <th>Begins</th>
    <th>Expires</th>
  </tr>
  <tr>{membership:}
    <td><a href="members/membership/read/{c_uid}">{c_title}</a></td>
    <td nowrap>{c_created_date|_date_format,'M j, Y'}</td>
    <td align="right">${c_total_cost|number_format,2}</td>
    <td>{c_status|bitmaskString,'status_id'}</td>
    <td nowrap>{c_begin_date|_date_format,'M j, Y'}</td>
    <td nowrap>{c_expiration_date|_date_format,'M j, Y'}</td>
  </tr>{:membership}
</table>
{:SOME}

{NONE:}
<p class="notice">{C_FULL_NAME} has no memberships.</p>
{:NONE}

</div>
