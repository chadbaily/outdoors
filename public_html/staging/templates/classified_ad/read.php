<h1>Ad: {C_TITLE}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p style="border:1px solid silver; padding:.5cm">{C_TEXT|nl2br|_linkify|htmlspecialchars}</p>

<table class="verticalHeaders collapsed elbowroom">
  <tr>
    <th>Posted by</th>
    <td>
      <a href="members/member/read/{C_OWNER}">{OWNER}</a>
    </td>
  </tr>
  <tr>
    <th>Date</th>
    <td>{C_CREATED_DATE|_date_format,'M j, Y'}</td>
  </tr>
  <tr>
    <th>Status</th>
    <td>{c_status|bitmaskString,'status_id'}</td>
  </tr>
</table>

{actions,{PAGE},{OBJECT},default}

</div>
