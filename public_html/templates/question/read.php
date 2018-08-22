<h1>Question Details</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<table class="verticalHeaders collapsed elbowroom">
  <tr>
    <th>Text</th>
    <td>{C_TEXT}</td>
  <tr>
  </tr>
    <th>Type</th>
    <td>{C_TYPE}</td>
  </tr>
  <tr>
    <th>Adventure</th>
    <td> 
      <a href="members/adventure/read/{C_ADVENTURE}">{ADVENTURE}</a>
    </td>
  </tr>
</table>

<h2>Answers</h2>

{SOME:}
<p>The following is a list of all answers to this question:</p>

<table class="cleanHeaders">
  <tr>
    <th>Member</th>
    <th>Answer</th>
  </tr>{ITEM:}
  <tr>
    <td>
      <a href="members/member/read/{T_MEMBER}">{C_FULL_NAME}</a>
    </td>
    <td>
      <a href="members/answer/read/{T_ANSWER}">{C_ANSWER_TEXT}</a>
    </td>
  </tr>{:ITEM}
</table>
{:SOME}

{NONE:}
<p class="notice">There are no answers for this question.</p>
{:NONE}

{actions,{PAGE},{OBJECT},default}

</div>
