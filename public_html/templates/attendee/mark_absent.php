<h1>Mark Attendee as Absent</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{INSTRUCTIONS:}
<p>You are marking <b>{C_FULL_NAME}</b> as absent from adventure
<b>{C_TITLE}</b>.  Include a comment and rate how severe {C_FIRST_NAME}'s absence
was, based on such factors as:</p>

<ol>
  <li>Whether {C_FIRST_NAME} tried to notify you that s/he would be absent</li>
  <li>Whether {C_FIRST_NAME}'s absence negatively impacted other members, such as
  not being available to drive after promising to do so, or keeping a waitlisted
  member from joining the adventure</li>
  <li>Whether {C_FIRST_NAME} had a valid reason for not showing up</li>
</ol>

{FORM}

{:INSTRUCTIONS}

{SUCCESS:}
<p class="notice">You have successfully marked <b>{C_FULL_NAME}</b> as absent from
adventure <b>{C_TITLE}</b>.</p>
{:SUCCESS}

{ALREADY:}
<p class="error"><b>{C_FULL_NAME}</b> is already recorded as absent from
adventure <b>{C_TITLE}</b>.</p>
{:ALREADY}

</div>
