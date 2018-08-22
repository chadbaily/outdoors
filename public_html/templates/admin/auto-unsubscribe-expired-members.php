{SOME:}
<h1>Success</h1>

<p>The following subscriptions were deleted:</p>

<table class="borders collapsed">
  <tr>
    <th>UID</th><th>Email Address</th>
  </tr>{ROW:}
  <tr>
    <td>{C_UID}</td>
    <td>{C_EMAIL}</td>
  </tr>{:ROW}
</table>

{:SOME}
{NONE:}
<h1>Nothing To Do</h1>

<p>There were no subscriptions to delete.</p>
{:NONE}
