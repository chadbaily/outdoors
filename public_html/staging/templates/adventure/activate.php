<h1>Activate Adventure</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{LEADER:}
<p class='error'>This adventure's leader has not joined the adventure.  You
cannot activate this adventure.  If you are the leader, you should <a
href="members/adventure/join/{OBJECT}">join
the adventure</a> first, then activate it.</p>
{:LEADER}

{ACTIVE:}
<p class='error'>This adventure is already active.</p>
{:ACTIVE}

{SUCCESS:}
<p class="notice">This adventure has been activated and members can now join it.
The only thing left to do is <a
href="members/adventure/announce/{OBJECT}">announce
it</a> so people will know it's here.</p>
{:SUCCESS}

{CONFIRM:}
<p>Please confirm that you wish to activate this adventure.  Please check the
following:</p>
<ul>
<li>Have you proofread the adventure details?</li>
<li>Have you checked with the officers for approval, if necessary?</li>
<li>Do you understand the gear policy?</li>
<li>Do you understand the reimbursement policy?</li>
</ul>
<form action="members/adventure/activate/{OBJECT}" method="GET">
<input type="submit" name="continue" value="Continue">
</form>
{:CONFIRM}

{FORM}

</div>
