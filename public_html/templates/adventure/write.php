<h1>Edit Adventure Details</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>You are editing <b>{C_TITLE}</b>.</p>

{SUCCESS:}
<p class='notice'>Your adventure details were successfully changed.</p>
{:SUCCESS}

{TOO_SMALL:}
<p class='error'>You may not reduce your adventure's size that much, because it
would force some current attendees to be put on the waitlist.  You already have
{SIZE} attendees, so your adventure's size can't be reduced below that.</p>
{:TOO_SMALL}

{ADDED:}
<p class='notice'>The following waitlisted attendee(s) were moved off the waitlist and onto
the adventure:</p>
<ol>
{MEMBER:}
  <li><b>{C_FULL_NAME}</b></li>
{:MEMBER}
</ol>
{:ADDED}

{FORM}

</div>
