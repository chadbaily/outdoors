<h1>Officer Applications</h1>

{FORM}
<br>

{SOME:}
<table class="borders collapsed" width="100%">
  <tr>
    <th colspan="2"></th>
    <th colspan="2">Num Attended</th>
    <th colspan="2">Num Led</th>
  <tr>
    <th>Member</th>
    <th>Submitted On</th>
    <th>All Time</th>
    <th>In Past Year</th>
    <th>All Time</th>
    <th>In Past Year</th>
  </tr>{report:}
  <tr>
    <td width="20%">
      <a href="members/application/read/{c_uid}">
        {c_full_name}</a>
    </td>
    <td>{c_created_date|_date_format,'m/d/Y'}</a></td>
    <td style="text-align:center">{num_attended_all_time}</td>
    <td style="text-align:center">{num_attended_last_year}</td>
    <td style="text-align:center">{num_led_all_time}</td>
    <td style="text-align:center">{num_led_last_year}</td>
  </tr>{:report}
</table>
{:SOME}

{NONE:}
<p class="notice">No matching records found.</p>
{:NONE}
