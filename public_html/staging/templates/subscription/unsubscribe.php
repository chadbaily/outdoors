<h1>Unsubscribe From Mailing List</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{CONFIRM:}
<p>Please confirm that you wish to unsubscribe from this email list:</p>

<form action="members/subscription/unsubscribe/{OBJECT}" method="GET">
<input type="hidden" name="continue" value="1">
<input type="submit" value="Continue">
</form>
{:CONFIRM}

{SUCCESS:}
<p class='notice'>You have successfully unsubscribed from this email list.</p>
{:SUCCESS}

</div>
