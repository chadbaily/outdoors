<h1>Your Officer Applications</h1>

{SOME:}
<table class="borders collapsed">
  <tr>
    <th>Date</th>
    <th>Title</th>
  </tr>{report:}
  <tr>
    <td>
      <a href="members/application/read/{c_uid}">
        {c_created_date|_date_format,'m/d/Y'}</a>
    </td>
    <td width="66%">{c_title}</td>
  </tr>{:report}
</table>
{:SOME}

{NONE:}
<p class="notice">You have not submitted any officer applications.</p>
{:NONE}
