<h1>View Subscription Details</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<table class="verticalHeaders collapsed elbowroom">
  <tr>
    <th>Member</th>
    <td>
      <a href="members/member/read/{T_MEMBER}">{C_FULL_NAME}</a>
    </td>
  </tr>
  <tr>
    <th>List</th>
    <td>
      <a href="members/email_list/read/{T_EMAIL_LIST}">{C_TITLE}</a>
    </td>
  </tr>
  <tr>
    <th>Email address</th>
    <td>{C_EMAIL}</td>
  </tr>
  <tr>
    <th>Password</th>
    <td>{C_PASSWORD}</td>
  </tr>
</table>

{actions,{PAGE},{OBJECT},default}

</div>
