<h1>View Absences for {C_FULL_NAME}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{NONE:}
<p class="notice">{C_FULL_NAME} has no absences.</p>
{:NONE}

{SOME:}

<p>Click on the adventure's title to view more details about this absence, edit
this absence, and so forth.</p>

<table class="cleanHeaders">
  <tr><th>Adventure</th><th>Severity</th><th>Comment</th></tr>
  {ABSENCE:}
  <tr>
    <td><a href="members/absence/read/{C_UID}">{C_TITLE}</a></td>
    <td>{C_SEVERITY}</td>
    <td>{C_COMMENT}</td>
  </tr>
  {:ABSENCE}
</table>
{:SOME}

</div>
