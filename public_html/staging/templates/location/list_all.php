<h1>Locations</h1>

{form}

{SOME:}
<p>The following locations matched your criteria:</p>

<ol>
  {item:}
  <li><a href="members/location/read/{c_uid}">{c_title}</a></li>
  {:item}
</ol>
{:SOME}

{NONE:}
<p class="notice">No matches found.</p>
{:NONE}
