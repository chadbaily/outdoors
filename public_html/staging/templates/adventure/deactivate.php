<h1>Deactivate Adventure</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>You are deactivating <b>{C_TITLE}</b>.</p>

{START:}
<p class='error'>This adventure's start date is not in the future.  You cannot
deactivate this adventure.</p>
{:START}

{INACTIVE:}
<p class="error">This adventure is not active.</p>
{:INACTIVE}

{SUCCESS:}
<p class="notice">This adventure has been deactivated.</p>
{:SUCCESS}

{CONFIRM:}
<p>Please confirm that you wish to deactivate this adventure:</p>
<form action="members/adventure/deactivate/{OBJECT}" method="GET">
<input type="submit" name="continue" value="Continue">
</form>
{:CONFIRM}

{FORM}

</div>
