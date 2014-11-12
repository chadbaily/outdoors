<h1>Activate Membership</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{SUCCESS:}
<p class="notice">You have successfully activated this membership.</p>
{:SUCCESS}

{inactive:}
<p>You are about to activate a <b>{C_TITLE}</b> membership for {C_FULL_NAME}.  Here is
some information about this member and membership:</p>

<ol>
  <li>{C_FULL_NAME} created this membership on 
    {C_CREATED_DATE|_date_format,'M j, Y'}.  {C_FULL_NAME} is <b>{AGE}</b> old.</li>
  <li>The fee for this membership is <b>${C_TOTAL_COST}</b>.</li>
  {FLEX:}
  <li>This membership's begin and expiration dates are flexible.  That is, the
    dates depend on when the membership is activated.  This membership is valid
    for a period of <b>{C_UNITS_GRANTED} {C_UNIT}s</b> from when it is
    activated or from when the member's current memberships expire, whichever
    comes last.  You may override these values in the form below, if you
    choose, but make sure you know what you are doing.  If the begin date
    is in the future, it probably means the member has a membership that's valid
    until then.</li>
  {:FLEX}
  {INFLEX:}
  <li>This membership's begin and expiration dates are fixed.  You may override
    the begin and expiration dates in the form below, if you choose, but you
    generally should not.</li>
  {:INFLEX}
</ol>

{FORM}
{:inactive}

</div>
