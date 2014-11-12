<h1>Move Attendee to Waitlist</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{ALREADY:}
<p class="error">{C_FULL_NAME} is already waitlisted.</p>
{:ALREADY}

{SUCCESS:}
<p class="notice">You have successfully moved {C_FULL_NAME} onto the waitlist.</p>
{:SUCCESS}

{CONFIRM:}
<p>You are moving <b>{C_FULL_NAME}</b> onto the waitlist for adventure
<b>{C_TITLE}</b>.  Choose what to do:</p>

<form action="members/attendee/waitlist/{OBJECT}" method="GET">

<p>
  <input type="radio" value="front" name="where" id="where1">
  <label for="where1">Move the attendee to the <b>front</b> of the
    waitlist</label><br>
  <input type="radio" value="back" name="where" id="where2">
  <label for="where2">Move the attendee to the <b>back</b> of the
    waitlist</label>
</p>

<input type="submit" value="Move Attendee to Waitlist">
</form>
{:CONFIRM}

</div>
