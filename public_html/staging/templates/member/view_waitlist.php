<h1>Waitlist Report for {C_FULL_NAME}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{some:}

<p>{C_FULL_NAME} has been waitlisted on the following adventures:</p>

<table class="ruled collapsed compact elbowroom cleanHeaders">
  <tr>
    <th>Title</th>
    <th>Location</th>
    <th>Date</th>
  </tr>
{row:}
  <tr>
    <td>
      <a href="members/adventure/read/{c_uid}">{c_title}</a>
    </td>
    <td>
      <a href="members/location/read/{loc_uid}">{loc_title}</a>
    </td>
    <td nowrap>{c_start_date|_date_format,'M j, Y'}</td>
  </tr>{:row}
</table>
{:some}

{none:}
<p class="notice">This member has not been waitlisted on any adventures.</p>
{:none}

</div>
