<h1>Phone Number: {C_PHONE_NUMBER}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>This page shows the details for a phone number.  You may also
<a href="members/phone_number/create">create
a new phone number</a>.</p>

<table class="verticalHeaders collapsed elbowroom">
  <tr>
    <th>Number</th>
    <td>{C_PHONE_NUMBER}</td>
  </tr>
  <tr>
    <th>Owned by</th>
    <td><a href="members/member/read/{C_OWNER}">{C_FULL_NAME}</a></td>
  </tr>
  <tr>
    <th>Title</th>
    <td>{C_TITLE}</td>
  </tr>
  <tr>
    <th>Type</th>
    <td>
      {TYPE:}
      <a href="members/phone_number_type/read/{T_PHONE_NUMBER_TYPE}">{C_TITLE}</a>
      {:TYPE}
    </td>
  </tr>
</table>

{actions,{PAGE},{OBJECT},default}

</div>
