<h1>Delete Object</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>Use this page to delete an object from the database.</p>

<p class="notice"><b>Warning:</b> Unless you know that you need to do this,
<b>don't do it</b>.</p>

<p>There are several choices for how to delete things.  To decide what to do,
think about whether you want to just mark the item as deleted (it stays in the
database, but the website ignores it) or actually delete it (gone forever).
Also think about whether you want this choice to apply to everything that's
linked to what you're deleting (a "cascading" delete).  To see how many items
will be affected if you choose this, look at the list below.</p>

<p>Generally, the only time you want to truly delete something is when the data
is wrong or bogus (someone was having too much fun making bullshit memberhips).
Most other things you just want to leave in the database so we have records of
them.</p>

{TODELETE:}
<h2>Objects to Delete</h2>

<p>If you choose the "cascade" option, the following objects will be deleted:</p>

<ol>
  <li>{OBJECTS}</li>
</ol>
{:TODELETE}
{CONFIRM:}
<p>Please read the above instructions carefully, then choose an action
below:</p>
{:CONFIRM}

{FORM:}
<form action="members/{PAGE}/delete/{OBJECT}" method="POST">

<p><b>Cascade the delete to related items?</b></p>
<input type="radio" name="cascade" id="cascade1" value="1"><label for="cascade1">Cascade</label><br>
<input type="radio" name="cascade" id="cascade0" value="0"><label for="cascade0">Don't cascade</label>

<p><b>Really delete, or just mark as deleted?</b></p>
<input type="radio" name="delete" id="delete1" value="1"><label for="delete1">Delete</label><br>
<input type="radio" name="delete" id="delete0" value="0"><label for="delete0">Don't delete</label>

<p><input type="submit" name="continue" value="Continue"></p>

</form>

{:FORM}

{DELETE:}
<p class="notice">You have deleted the object from the database.</p>
{:DELETE}

{MARK:}
<p class="notice">You have marked the item as deleted in the database.</p>
{:MARK}
</div>
