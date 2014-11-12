<h1>{C_SCREENNAME}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

You may also <a href="members/chat/create">create
a new chat identity</a>.</p>

<table class="verticalHeaders collapsed elbowroom verticalMargins">
  <tr>
    <th>Screen name</th>
    <td>{C_SCREENNAME}</td>
  </tr>
  <tr>
    <th>Type</th>
    <td>
      {TYPE:}
      {C_TITLE}
      {:TYPE}
    </td>
  </tr>
  <tr>
    <th>Owned by</th>
    <td><a href="members/member/read/{C_OWNER}">{C_FULL_NAME}</a>
    </td>
  </tr>
</table>

{actions,{PAGE},{OBJECT},default}

</div>
