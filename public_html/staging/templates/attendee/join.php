<h1>Join Attendee</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>You are joining <b>{C_FULL_NAME}</b> to adventure <b>{C_TITLE}</b>.</p>

{SUCCESS:}
<p class="notice">You have successfully joined this attendee onto this
adventure.</p>
<p><i>&raquo; Return to the adventure's <a href="members/adventure/view_report/{C_ADVENTURE}">report
page</a></i>.</p>
{:SUCCESS}

{ALREADY:}
<p class="error">This attendee is already attending this adventure.</p>
{:ALREADY}

{CANTJOIN:}
<p class="error">Only the trip leader or an officer can take people off of the waitlist.</p>
{:CANTJOIN}

{CONFIRM:}
<p>Please confirm that you wish to move this attendee off the waitlist and onto
the adventure's roster.</p>
<form action="members/attendee/join/{OBJECT}" method="GET">
<input type="submit" name="continue" value="Continue">
</form>
{:CONFIRM}

</div>
