<h1>Check Foreign Keys</h1>

<p>This page checks that values in the database correctly refer to a record in
their parent tables.  If you see any results below, that row in the named table
has a value that has no corresponding row in the parent table.  This should be
fixed as soon as possible.</p>

<hr />

{NONE:}
<p class='notice'>There were no results.</p>
{:NONE}

{SOME:}
<p class='notice'>There were {NUMROWS} bad rows in {NUMTABLES} tables.</p>

<form action="members/admin/delete-object" method="POST"
    onSubmit="return confirm('Are you sure you want to DELETE the selected objects?\r\n\r\nYou should only do this if you KNOW that this is the right solution to the problem.')">
{RESULTS}
<p align="right"><input type="submit" value="Delete Selected Objects"></p>
</form>

{:SOME}
