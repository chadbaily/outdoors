<h1>Adventures</h1>

{SOME:}
<p>Here are the upcoming adventures:</p>
<table class="ruled compact cleanHeaders" width="99%">
  <tr>
    <th>Title</th>
    <th>Location</th>
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
    <td nowrap>{C_START_DATE|_date_format,'D, M j \a\t g:i A'}</td>
  </tr>
{:ROW}
</table>
{:SOME}

{NONE:}
<p class="notice">There are no upcoming adventures in the database.</p>
{:NONE}

<!--Begin Rotating Image Block -->
<p>
<script type="text/javascript" language="javascript">
document.write('<img src="images/rotating_images/image'
               + Math.floor(Math.random() * 25)
               + '.jpg" class="thumbnail-right" width="300" height="225">');
</script>
<!--End Rotating Image Block -->

<p>
Choose an action:</p>

<ol>
  <li><a href="members/adventure/list_all">View All Adventures</a></li>
  <li><a href="members/adventure/list_all?criteria=joined">View Adventures You've Joined</a></li>
  <li><a href="members/adventure/list_all?criteria=past">View Past Adventures</a></li>
  {LIST_OWNED:}
  <li><a href="members/adventure/list_all?criteria=owned">View Adventures You Are Leading</a></li>
  {:LIST_OWNED}
  {INACTIVE:}
  <li><a href="members/adventure/list_all?criteria=inactive">View Inactive Adventures</a></li>
  {:INACTIVE}
  {CREATE:}
  <li><a href="members/adventure/create">Create A New Adventure</a></li>
  {:CREATE}
</ol>
