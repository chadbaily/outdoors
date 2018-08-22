<h1>Phone Numbers</h1>

<p>The following is a list of all phone numbers in the database:</p>

<table>
  <tr>
    <th>Owner</th>
    <th>Title</th>
    <th>Number</th>
    <th>Primary?</th>
  </tr>{ITEM:}
  <tr>
    <td>
      <a href="members/member/read/{T_MEMBER}">{C_FULL_NAME}</a>
    </td>
    <td>
      <a href="members/phone_number/read/{T_PHONE_NUMBER}">{C_TITLE}</a>
    </td>
    <td>{C_PHONE_NUMBER}</td>
    <td>{C_PRIMARY}</td>
    </tr>{:ITEM}
</table>
