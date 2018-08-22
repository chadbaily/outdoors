<h1>Subscribe to {C_TITLE}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{ALREADY:}
<p class='error'>You cannot subscribe to this email list because you are already
subscribed to it with the same email address you currently have.</p>
{:ALREADY}

{CONFIRM:}
<p>Please confirm that you wish to subscribe to this email list:</p>
<form action="members/email_list/subscribe/{OBJECT}" method="GET">
<input type="hidden" name="continue" value="1">
<input type="submit" value="Continue">
</form>
{:CONFIRM}

{SUCCESS:}
<p class='notice'>You have successfully subscribed to this email list.</p>
{:SUCCESS}

</div>
