<h1>Membership Type &ldquo;{C_TITLE}&rdquo;</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>Membership Type <b>{C_TITLE}</b> ({C_DESCRIPTION}) has the following
properties:</p>

<table class="verticalHeaders collapsed elbowroom">
<tr><th>Begin Date:</th> <td>{C_BEGIN_DATE|_date_format,'M j, Y'}</td</tr>
<tr><th>Expiration Date:</th> <td>{C_EXPIRATION_DATE|_date_format,'M j, Y'}</td</tr>
<tr><th>Date to Show on Form:</th> <td>{C_SHOW_DATE|_date_format,'M j, Y'}</td</tr>
<tr><th>Date to Stop Showing on Form:</th> <td>{C_HIDE_DATE|_date_format,'M j, Y'}</td</tr>
<tr><th>Time Granted:</th> <td>{C_UNITS_GRANTED} {C_UNIT}(s)</td</tr>
<tr><th>Unit Cost:</th> <td>${C_UNIT_COST}</td</tr>
<tr><th>Total Cost:</th> <td>${C_TOTAL_COST}</td</tr>
<tr><th>Hidden:</th> <td>{C_HIDDEN}</td</tr>
<tr><th>Flexible:</th> <td>{C_FLEXIBLE}</td</tr>
</table>

{actions,{PAGE},{OBJECT},default}

</div>
