<h1>Edit Report</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{SUCCESS:}
<p class="notice">Your changes were saved.</p>
{:SUCCESS}

<p>Use the following form to edit a report.</p>

{BAD:}
<p class="error">There were some disallowed words in the query.  Please remove
the following words from your query:</p>
<ol>
  {ITEM:}
  <li><tt>{WORD}</tt></li>
  {:ITEM}
</ol>
{:BAD}

{FORM}

</div>
