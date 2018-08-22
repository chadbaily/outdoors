<h1>View Address Details</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<table class="verticalHeaders collapsed elbowroom">
  <tr>
    <th>Owner</th>
    <td><a href="members/member/read/{T_MEMBER}">{C_FULL_NAME}</a></td>
  </tr>
  <tr>
    <th>Title</th>
    <td>{C_TITLE}</td>
  </tr>
  <tr>
    <th>Street</th>
    <td>{C_STREET}</td>
  </tr>
  <tr>
    <th>City</th>
    <td>{C_CITY}</td>
  </tr>
  <tr>
    <th>State</th>
    <td>{C_STATE}</td>
  </tr>
  <tr>
    <th>Zip Code</th>
    <td>{C_ZIP}</td>
  </tr>
  <tr>
    <th>Country</th>
    <td>{C_COUNTRY}</td>
  </tr>
</table>

{actions,{PAGE},{OBJECT},default}

</div>
