<h1>History for Item {C_UID}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{some:}

<table class="cleanHeaders compact">
  <tr>
    <th>Checkout</th>
    <th>Member</th>
    <th>Date</th>
    <th>Current Status</th>
  </tr>
  <tr>{checkout:}
    <td><a href="members/checkout/read/{c_checkout}">{c_checkout}</a></td>
    <td><a href="members/member/read/{c_uid}">{c_full_name}</a></td>
    <td nowrap>{c_created_date|_date_format,'M j, Y'}</td>
    <td>{c_status|bitmaskString,'status_id'}</td>
  </tr>{:checkout}
</table>
{:some}

{none:}
<p class="notice">Item {C_UID} has no history.</p>
{:none}

</div>
