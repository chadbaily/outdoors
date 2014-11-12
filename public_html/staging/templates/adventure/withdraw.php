<h1>Withdraw From Adventure</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>You are withdrawing from <b>{C_TITLE}</b>.</p>

{DEADLINE:}
<p class='error'>This adventure's withdrawal deadline has already
passed.  Please contact the adventure's leader directly as soon as
possible.</p>
{:DEADLINE}

{ALREADY:}
<p class='error'>You cannot withdraw from this adventure because you
are not attending it.</p>
{:ALREADY}

{CONFIRM:}
<p>Please confirm that you wish to withdraw from this adventure:</p>
<form action="members/adventure/withdraw/{OBJECT}" method="GET">
<input type="hidden" name="continue" value="1">
<input type="submit" value="Continue">
</form>
{:CONFIRM}

{LEADER:}
<p class='error'>You cannot withdraw from this adventure because you
are the leader.</p>
{:LEADER}

{SUCCESS:}
<p class='notice'>You have successfully withdrawn from this adventure.</p>
{:SUCCESS}

</div>
