<h1>De-Dupe Questions</h1>

<p>Use the form below to de-dupe questions.  If you find a question you can
safely (without affecting the accuracy of the answers) change to something else,
please check the "FROM" box for it, and then check the "TO" box for the one
you want to change it to.</p>

<form action="members/admin/dedupe-questions" method="POST">
<table class='compact collapsed borders'>
  <tr><th>ID</th><th>Num</th><th>Text</th><th>From/To</th></tr>{ROW:}
  <tr>
    <td>
      <a href="members/question/write/{C_UID}">{C_UID}</a>
    </td>
    <td>{NUM}</td>
    <td>{C_TEXT}</td>
    <td nowrap>
      <input type="checkbox" name="from[]" value="{C_UID}">From
      <input type="radio" name="to" value="{C_UID}">To
    </td>
  </tr>{:ROW}
</table>

<p align="right"><input type="submit" value="Update questions"></p>

</form>
