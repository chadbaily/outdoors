<h1>Absence: {C_FULL_NAME}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<table>
  <tr>
    <td>Member</td>
    <td><a href="members/member/read/{T_MEMBER}">{C_FULL_NAME}</a></td>
  </tr>
  <tr>
    <td>Adventure</td>
    <td><a href="members/adventure/read/{T_ADVENTURE}">{C_TITLE}</a></td>
  </tr>
  <tr>
    <td>Severity</td>
    <td>{C_SEVERITY}</td>
  </tr>
  <tr>
    <td>Comment</td>
    <td>{C_COMMENT}</td>
  </tr>
</table>

{actions,{PAGE},{OBJECT},default}

</div>
