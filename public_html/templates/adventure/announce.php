<h1>Announce Adventure</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>Use this page to send an announcement email to the main club
mailing list.</p>

{START:}
<p class='error'>This adventure's start date is not in the future.
You cannot announce this adventure.</p>
{:START}

{ACTIVE:}
<p class="error">This adventure is not active.  You cannot announce
this adventure.</p>
{:ACTIVE}

{SUCCESS:}
<p class="notice">You have successfully announced your adventure to
the main mailing list.  The messages are queued and may take 5 minutes or so to
send.</p>
{:SUCCESS}

{CONFIRM:}
<p>Please confirm that you wish to announce this adventure:</p>
<form action="members/adventure/announce/{OBJECT}" method="GET">
<input type="submit" name="continue" value="Continue">
</form>
{:CONFIRM}

</div>
