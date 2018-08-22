<h1>Withdraw Attendee</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{SUCCESS:}
<p class="notice">You have successfully withdrawn {C_FULL_NAME} from this
adventure.
<br><i>&raquo; Return to the adventure's <a
href="members/adventure/view_report/{C_ADVENTURE}">report
page</a></a>.</p>
{:SUCCESS}

{MOVED:}
<p class="notice">{MEMBER_NAME} was moved from the waitlist onto the
adventure.</p>
{:MOVED}

{NO_MOVED:}
<p class="notice">There were no waitlisted attendees, so no one was joined
automatically.</p>
{:NO_MOVED}

{CONFIRM:}
<p>You are withdrawing <b>{C_FULL_NAME}</b> from the adventure <b>{C_TITLE}</b>.
Choose what to do with the waitlist:</p>

<form action="members/attendee/withdraw/{OBJECT}" method="GET">

<p>
  <input type="radio" value="true" name="waitlist" id="waitlist1">
  <label for="waitlist1">Automatically join the first waitlisted
  member.</label><br>
  <input type="radio" value="false" name="waitlist" id="waitlist2">
  <label for="waitlist2">Leave the waitlist as is.</label>
</p>

<input type="submit" name="continue" value="Continue">
</form>
{:CONFIRM}

</div>
