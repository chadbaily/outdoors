<h1>Expense Report Notes</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<table class="cleanHeaders top">
  <tr>
    <th>Date</th>
    <th>Member</th>
    <th>Status</th>
  </tr>{note:}
  <tr>
    <td>{c_created_date|_date_format,'n/j/y'}</td>
    <td nowrap>
      <a href="members/member/read/{c_creator}">{c_full_name}</a>
    </td>
    <td>{c_new_status|bitmaskString,'status_id'}</td>
  </tr>{:note}
</table>

</div>
