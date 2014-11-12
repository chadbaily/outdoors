<h1>Membership Details</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<table class="verticalHeaders collapsed elbowroom">
  <tr>
    <th>Member</th>
    <td>
      <a href="members/member/read/{C_MEMBER}">{C_FULL_NAME}</a>
    </td>
  </tr>
  <tr>
    <th>Type</th>
    <td>
      <a href="members/membership_type/read/{C_TYPE}">{TYPE_TITLE}</a>
    </td>
  </tr>
  <tr>
    <th>Begin Date</th>
    <td>{C_BEGIN_DATE|_date_format,'M j, Y'}</td>
  </tr>
  <tr>
    <th>Expiration Date</th>
    <td>{C_EXPIRATION_DATE|_date_format,'M j, Y'}</td>
  </tr>
  <tr>
    <th>Status</th>
    <td>{STATUS_TITLE}</td>
  </tr>
  <tr>
    <th>Units Granted</th>
    <td>{C_UNITS_GRANTED}</td>
  </tr>
  <tr>
    <th>Unit of Time</th>
    <td>{C_UNIT}</td>
  </tr>
  <tr>
    <th>Total Cost</th>
    <td>${C_TOTAL_COST}</td>
  </tr>
  <tr>
    <th>Amount Paid</th>
    <td>${C_AMOUNT_PAID}</td>
  </tr>
</table>

{actions,{PAGE},{OBJECT},default}

</div>
