<h1>View Members</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{NONE:}
<p class="notice">This email list, <b>{C_TITLE}</b>, has no subscribers.</p>
{:NONE}

{SOME:}
<p>Members that are subscribed to email list <b>{C_TITLE}</b>:</p>

<table class="compact cleanHeaders">
  <tr>
    <th>Name</th><th>Email</th><th>Password</th>
  </tr>
  <tr>{ROW:}
    <td><a href="members/subscription/read/{C_UID}">{C_FULL_NAME}</a></td>
    <td>{C_EMAIL}</td>
    <td>{C_PASSWORD}</td>
  </tr>{:ROW}
</table>
{:SOME}

</div>
