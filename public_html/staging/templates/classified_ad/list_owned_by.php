<h1>Classified Ads</h1>

<p>You can also <a
href="members/classified_ad/list_owned_by">view your own
ads</a> or <a href="members/classified_ad/create">create a new
ad</a>.</p>

{SOME:}
<table class="cleanHeaders compact top">
  <tr>
    <th>Title</th>
    <th>Date</th>
    <th>Status</th>
  </tr>{ROW:}
  <tr>
    <td>
      <a href="members/classified_ad/read/{C_UID}">{C_TITLE}</a>
    </td>
    <td>
      {C_CREATED_DATE|_date_format,'M j, Y'}
    </td>
    <td>
      {c_status|bitmaskString,'status_id'}
    </td>
  </tr>{:ROW}
</table>
{:SOME}

{NONE:}
<p>There are no ads at this time.</p>
{:NONE}
