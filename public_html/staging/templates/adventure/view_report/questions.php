<h1>Questions</h1>

<table class="borders compact collapsed top" style="background:white">
  <tr>
    <td>&nbsp;</td>{HEADER_V:}
    <td title="{C_TEXT|htmlspecialchars}"><img title="{C_TEXT|htmlspecialchars}"
    alt="{C_TEXT|htmlspecialchars}" src="assets/vertical-text.php?text={C_TEXT|urlencode}"></td>{:HEADER_V}{HEADER_H:}
    <td>{C_TEXT|htmlspecialchars}</td>{:HEADER_H}
  </tr>
{ROWS}
{QUESTION:}
  <tr>
    <td>{C_FULL_NAME}</td>{ANSWER:}
    <td>{C_ANSWER_TEXT}</td>{:ANSWER}
  </tr>
{:QUESTION}
</table>
