<h1>Classified Ads</h1>

<p>You can also <a href="members/classified_ad/list_owned_by">view your own
ads</a> or <a href="members/classified_ad/create">create a new
ad</a>.</p>

{SOME:}
<table class="cleanHeaders compact top">{ROW:}
  <tr>
    <th>Posted By</th>
    <th>{C_TITLE}</th>
  </tr>
  <tr>
    <td>
      <a href="members/member/read/{C_OWNER}">{C_FULL_NAME}</a><br>
      <a href="mailto:{C_EMAIL}">{C_EMAIL}</a><br>
      {C_CREATED_DATE|_date_format,'M j, Y'}
    </td>
    <td>{C_TEXT|nl2br|_linkify|htmlspecialchars}</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>{:ROW}
</table>
{:SOME}

{NONE:}
<p>There are no ads at this time.</p>
{:NONE}
