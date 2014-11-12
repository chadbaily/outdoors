<h1>{C_FULL_NAME}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{ALL:}
{MEMBER:}
<table class="verticalHeaders collapsed elbowroom">
  <tr>
    <th>Name</th>
    <td>{C_FULL_NAME}</td>
  </tr>{EMAIL:}
  <tr>
    <th>Email</th>
    <td><a href="mailto:{C_EMAIL}">{C_EMAIL}</a></td>
  </tr>{:EMAIL}{BIRTHDATE:}
  <tr>
    <th>Birthdate</th>
    <td>{C_BIRTH_DATE|_date_format,'M j, Y'}</td>
  </tr>{:BIRTHDATE}{GENDER:}
  <tr>
    <th>Gender</th>
    <td>{C_GENDER}</td>
  </tr>{:GENDER}{PASSWORD:}
  <tr>
    <th>Password</th>
    <td>{C_PASSWORD}</td>
  </tr>
  <tr>
    <th>Groups</th>
    <td>{C_GROUP_MEMBERSHIPS|bitmaskString,'group_id'}</td>
  </tr>{:PASSWORD}{:MEMBER}{ADDRESS:}
  <tr>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th>Address
      {ADDR_ALL:}
        <br><a href="members/address/list_owned_by">&raquo; all addresses</a>
      {:ADDR_ALL}
    </th>
    <td>
      <a href="members/address/read/{T_ADDRESS}">{C_TITLE}</a>
      <br>{C_STREET}
      <br>{C_CITY}, {C_STATE} {C_ZIP}
    </td>
  </tr>{:ADDRESS}{PHONES:}
  <tr>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th>
      Phone Numbers
      {PHONE_ALL:}
      <br><a href="members/phone_number/list_owned_by">&raquo; all numbers</a>
      {:PHONE_ALL}
    </th>
    <td>{PHONE:}
      <div><a href="members/phone_number/read/{T_PHONE_NUMBER}">&raquo;</a> {C_PHONE_NUMBER}</div>{:PHONE}
    </td>
  </tr>{:PHONES}{IDENTITIES:}
  <tr>
    <td></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th>
      Chat Identities
      {CHAT_ALL:}
      <br><a href="members/chat/list_owned_by">&raquo; all identities</a>
      {:CHAT_ALL}
    </th>
    <td>{CHAT:}
      <div>
        <img src="assets/{C_ABBREVIATION}.png" height="16" width="16" title="{C_TYPE}" alt="{C_TYPE}">
        <a href="members/chat/read/{T_CHAT}">{C_SCREENNAME}</a>
        {:CHAT}
    </td>
  {:IDENTITIES}
</table>

{actions,{PAGE},{OBJECT},default}

{:ALL}

{NONE:}
<p class="notice">Sorry, but this member has chosen to remain entirely
private.</p>
{:NONE}

{ADV:}
<h2>Adventures</h2>
<p>{C_FULL_NAME} has attended the following adventures:</p>
<table class="ruled collapsed compact elbowroom cleanHeaders">
  <tr>
    <th>Title</th>
    <th>Location</th>
    <th>Date</th>
  </tr>
{ROW:}
  <tr class="{CLASS}">
    <td>
      <a href="members/adventure/read/{C_UID}">{C_TITLE}</a>
    </td>
    <td>
      <a href="members/location/read/{LOC_UID}">{LOC_TITLE}</a>
    </td>
    <td nowrap>{C_START_DATE|_date_format,'M j, Y'}</td>
  </tr>
{:ROW}
</table>
{:ADV}

</div>
