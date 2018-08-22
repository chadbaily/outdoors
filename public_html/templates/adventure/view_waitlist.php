<h1>Adventure Report</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{ERROR:}
<p class="notice">You are not attending this adventure.</p>
{:ERROR}

{OFF:}
<p class="notice">You are not on the waitlist for this adventure.</p>
{:OFF}

{ON:}
<p>You are on the waitlist for this adventure.  There can be a
maximum of {C_MAX_ATTENDEES} attendees on this adventure; there are
{NUM_ATTENDEES} attendees and {NUM_WAITLISTED} of them are waitlisted.
{AHEAD_OF_ME} of these attendees joined this adventure before you.</p>

<p>The website manages the waitlist automatically.  When a non-waitlisted member
withdraws and there are members on the waitlist, the website will remove the
first waitlisted member (in order of joining) and add him or her to the
adventure.  If there is a good reason to do so, the adventure's leader can also
manually move members onto or off of the waitlist.  This may happen when more
drivers are needed and one of the waitlisted members has a car, for example.</p>
{:ON}

</div>
