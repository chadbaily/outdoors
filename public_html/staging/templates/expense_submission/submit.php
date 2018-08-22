<h1>Submit Expenses</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>Since expenses are not marked as submitted until you explicitly mark them as
submitted, you need to check that this submission contains <b>exactly</b> the
expenses you are actually submitting for reimbursal.  Later, when you receive
the reimbursal, you will use the "Accept" tab to record that the expenses were
accepted and reimbursed.</p>

<form action="members/expense_submission/submit/{OBJECT}" method="POST">

<p><input type="submit" name="submitted" value="Mark Expenses as Submitted"></p>

</form>

</div>
