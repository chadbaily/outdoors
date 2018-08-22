<h1>Member Directory</h1>

{form}

{SOME:}
<p>{NUM} members found.</p>

<table class="compact collapsed elbowroom cleanHeaders">
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Chat</th>
  </tr>{ROW:}
  <tr>
    <td><a href="members/member/read/{c_uid}">{c_last_name}, {c_first_name}</a></td>
    <td><a href="mailto:{c_email}">{c_email}</a></td>
    <td>{phone_number}</td>
    <td><img src="assets/{c_abbreviation}.png"> {c_screenname}</td>
  </tr>{:ROW}
  <tr>
    <td align="center" colspan="4">
      <a href="javascript:void(0);"
      onClick="prevPage(document.forms[0].offset);return false;">
      &laquo; prev page</a>
      <a href="javascript:void(0);"
      onClick="nextPage(document.forms[0].offset);return false;">
      next page &raquo;</a>
    </td>
  </tr>
</table>

{:SOME}

{NONE:}
<p class="notice">No members matched your criteria.</p>
{:NONE}
