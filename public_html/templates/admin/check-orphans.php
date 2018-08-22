<h1>Check For Orphaned Database Rows</h1>

<p>This page checks for orphaned rows in the database.  An orphaned row is a row
to which nothing refers.  <b>This does not mean that the row should be
deleted.</b>  It just means that nothing else in the database points to it.  You
will need to use your judgement and knowledge of the database to know what you
need to do with the orphaned row.</p>

<p>This is not a complete check of all tables in the database.  It only checks
some of the easy ones.  There are other tables that are at the top of the
parent-child hierarchy, such as <tt>t_action</tt>, that may have unused rows in
them, but it's not useful to check them.</p>

<hr />

{NONE:}
<p class='notice'>There were no results.  None of these values in the
database are orphaned.</p>
{:NONE}

{SOME:}
<p class='notice'>There were {NUMROWS} orphaned rows in {NUMTABLES} tables.</p>

<form action="members/admin/delete-object" method="POST"
    onSubmit="return confirm('Are you sure you want to DELETE the selected objects?')">
<input type="hidden" name="page" value="admin">
<input type="hidden" name="action" value="delete-object">
{RESULTS}
<p align="right"><input type="submit" value="Delete Selected Objects"></p>
</form>

{:SOME}
