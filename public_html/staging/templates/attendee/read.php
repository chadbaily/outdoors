<h1>Attendee Details</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<table class="collapsed elbowroom verticalHeaders">
  <tr>
    <th>Member</th>
    <td><a href="members/member/read/{me_uid}">{c_full_name}</a></td>
  </tr>
  <tr>
    <th>Adventure</th>
    <td><a href="members/adventure/read/{ad_uid}">{c_title}</a></td>
  </tr>
  <tr>
    <th>Amount Paid</th>
    <td>${c_amount_paid}</td>
  </tr>
  <tr>
    <th>Date Joined</th>
    <td>{c_joined_date}</td>
  </tr>
  <tr>
    <th>Status</th>
    <td>{c_status|bitmaskString,'status_id'}</td>
  </tr>
</table>

{actions,{PAGE},{OBJECT},default}

</div>
