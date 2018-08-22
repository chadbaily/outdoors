<h1>{c_title}</h1>

<table class="tabbedBox" border="0" cellpadding="0" cellspacing="0">
{TABS}
</table>
<div class="box">

{view_answers:}
<p class="notice">You have joined this adventure.  If you wish, you may
<a href="members/attendee/view_answers/{at_uid}">
view and edit the answers you gave to the adventure's questions when you joined</a>.</p>
{:view_answers}

{comment_link:}
<p class="notice">You can
<a href="members/adventure/comment/{OBJECT}">comment
on this adventure</a> if you wish.</p>
{:comment_link}

{cancelled:}
<p class="notice">This adventure is cancelled.</p>
{:cancelled}

<p>{c_description|smiley|nl2br|_linkify|htmlspecialchars}</p>

<table class="verticalHeaders collapsed elbowroom compact classic">
  <tr>
    <th>Who</th>
    <td>
      <a href="members/member/read/{c_owner}">{c_full_name}</a>
      is leading this trip; there is space for {c_max_attendees} people.{full:}
      The trip is currently full, but you may join the waitlist.{:full}</p>
    </td>
  </tr>
  <tr>
    <th>Where</th>
    <td>
      <a href="members/location/read/{c_destination}">{destination_title}</a>{weather:}
      (<a target="_blank"
      href="http://www.weather.com/weather/print/{destination_zip}">Weather
      Forecast</a>){:weather}.  We depart from <a
      href="members/location/read/{c_departure}">{departure_title}</a>.
    </td>
  </tr>
  <tr>
    <th>Start</th>
    <td>{c_start_date|_date_format,'D n/j/y \a\t g:i A'}</td>
  </tr>
  <tr>
    <th>End</th>
    <td>{c_end_date|_date_format,'D n/j \a\t g:i A'}</td>
  </tr>
  <tr>
    <th>Signup&nbsp;Deadline</th>
    <td>{c_signup_date|_date_format,'D n/j \a\t g:i A'}</td>
  </tr>
  <tr>
    <th>Fee</th>
    <td>${c_fee|number_format,2}</td>
  </tr>
</table>

<p><b>Activity Categories:</b></p>

<ul>{cat:}
  <li>{ac_title}</li>{:cat}
</ul>

{attendees:}
<h2>Attendees</h2>
<table class="cleanHeaders compact">
  <tr>
    <th>Member</th>
    <th>Email</th>
  </tr>{attendee:}
  <tr>
    <td><a href="members/member/read/{c_uid}">{c_full_name}</a></td>
    <td><a href="mailto:{c_email}">{c_email}</a></td>
  </tr>{:attendee}
</table>
{:attendees}

{actions,{PAGE},{OBJECT},default}

</div>

{some:}
<h2>Comments</h2>

<p>Here's what attendees had to say about this adventure{comment_link:} (<a
href="members/adventure/comment/{OBJECT}">add your
own comment</a>){:comment_link}:</p>

<table width="100%" class="compact collapsed elbowroom cleanHeaders">
  <tr><th>Author</th><th>Comment</th></tr>
{comment:}
  <tr>
    <td width="20%" style="vertical-align:top">
      {show_name:}
      <a href="members/member/read/{T_MEMBER}">{c_full_name}</a><br>
      {:show_name}
      {hide_name:}Anonymous Coward<br>{:hide_name}
      Posted: {c_created_date|_date_format,'M j, Y'}<br>
      Rating: {c_title}
      <!-- photo goes here eventually -->
    </td>
    <td width="80%" style="vertical-align:top">
      <b>{c_subject|htmlspecialchars}</b><br>
      {c_text|nl2br|htmlspecialchars}
    </td>
  </tr>
    <td colspan="2"><hr size="1"></td>
  </tr>
{:comment}
</table>

{:some}

