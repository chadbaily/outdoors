<h1>Edit Questions for {C_TITLE}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

<p>Use questions to find out what gear you'll need, meal preferences, allergies,
and so forth.</p>

<p>You should <b>always</b> ask if there are any medical problems.</p>

<p>You are currently adding questions to the adventure <b>{C_TITLE}</b>.  When
you are done, the next step is to <a
href="members/adventure/join/{OBJECT}">join the
adventure yourself</a>, so you can activate it; or if you're editing an
adventure that has been posted already, if necessary you can <a
href="members/adventure/email_attendees/{OBJECT}">email
the attendees</a> to let them know that you changed the questions.</p>

{NOTICE:}
<p class="notice">Please notify the attendees if you edit the questions after
you have activated the adventure.  Tell them to log back in, view the adventure,
and they should see a link to edit their answers to the question (if needed).</p>
{:NOTICE}

<fieldset>
<legend>Most Popular Questions</legend>

Here are the most popular questions for this type of adventure.  Click on a question to
copy it to your adventure:

<div style="height:16em; overflow:auto">
<ol>
  {POPULAR:}
  <li><a href="members/question/copy/{Q}?adventure={A}">{TEXT}</a></li>
  {:POPULAR}
</ol>
</div>

</fieldset>

<fieldset>
<legend>Current Questions</legend>

<p>Here are all the questions you have added so far (you can edit or delete a
question by clicking on it):</p>

<a name="current">&nbsp;</a>
<table class="compact top">
  <tr>{EXISTING:}
    <td>{TEXT}</a>
    <td nowrap>
      <a href="members/question/delete/{Q}">delete</a> |
      <a href="members/question/write/{Q}">edit</a>
    </td>
  </tr>{:EXISTING}
</table>
</fieldset>

<fieldset>
<legend>Add A NEW Question</legend>

<p>Use this form to add a question to your adventure.</p>

<p class="error">Please, try to ask questions in the format &ldquo;Do you need a
pack?&rdquo; instead of &ldquo;Will you be bringing a pack&rdquo; or &ldquo;Do
you have your own pack.&rdquo;  This will help keep the database easy to manage.
Thanks!</p>

{SUCCESS:}
<p class="notice">Success. Your question, <b>{C_QUESTION_TITLE}</b>,
has been added to this adventure.</p>
{:SUCCESS}

{FORM}

<p>If you are done, the next step is to <a
href="members/adventure/join/{OBJECT}">join the
adventure yourself</a>, so you can activate it; or if you're editing an
adventure that already has been posted, if necessary you can <a
href="members/adventure/email_attendees/{OBJECT}">email
the attendees</a> to let them know that you changed the questions.</p>

</fieldset>

</div>
