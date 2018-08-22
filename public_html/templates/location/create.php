<h1>Create a Location</h1>

{SUCCESS:}
<p class="notice">Your new location was successfully added to the
database.</p>
{:SUCCESS}

<p>Use this page to create a location.  <b>Do not create a location frivolously.
Other members will use your location too.</b>  Unlike questions, locations are
globally available to everyone.  This means you should check carefully to make
sure the location you need doesn't exist.  If it does and it's not satisfactory,
consider editing it instead of cluttering the database with stuff that other
leaders will have to sort through.  But be careful not to edit something that
might affect the accuracy of another adventure.</p>

<p><b>Here's a list of all locations currently in the database.  Is yours already
here?</b></p>

<div style="height:150px; white-space: nowrap; border:1px solid black; overflow:auto">

<ol>
{LOCATIONS:}
    <li>
      <a href="members/location/read/{C_UID}">{C_TITLE|substr,0,50}</a>
    {C_DESCRIPTION|substr,0,40}
  </li>
{:LOCATIONS}
</ol>

</div>

<h1>Add A Location</h1>

<p>If you are sure you need to create a new location, use this form.</p>

{FORM}
