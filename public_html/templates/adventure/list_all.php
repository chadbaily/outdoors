<h1>View Adventures</h1>

{ALL:}
{form}
<p>The following are all adventures in the database:</p>
{:ALL}

{OWNED:}
<p>The following are all adventures you're leading:</p>
{:OWNED}

{JOINED:}
<p>The following are all adventures you've joined:</p>
{:JOINED}

{PAST:}
<p>The following are all past adventures for a year.  You may also <a href="members/adventure/list_all?criteria=past&all=true">see all adventures</a>.</p>
{:PAST}

{UPCOMING:}
<p>The following are all upcoming adventures:</p>
{:UPCOMING}

{SOME:}
<table class="compact ruled cleanHeaders">
  <tr>
    <th>Title</th>
    <th>Location</th>
    {ALL:}<th>Leader</th>{:ALL}
    <th>Date</th>
  </tr>
{ROW:}
  <tr>
    <td>
      <a href="members/adventure/read/{C_UID}">{C_TITLE}</a>
    </td>
    <td>
      <a href="members/location/read/{LOC_UID}">{LOC_TITLE}</a>
    </td>
    {ALL:}
    <td>
      <a href="members/member/read/{ME_UID}">{C_FULL_NAME}</a>
    </td>
    {:ALL}
    <td nowrap>{C_START_DATE|_date_format,'{FORMAT}'}</td>
  </tr>
{:ROW}
</table>
{:SOME}

{NONE:}
<p class="notice">Sorry, no adventures found.</p>
{:NONE}
