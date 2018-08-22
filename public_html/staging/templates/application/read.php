<h1>{c_title|smiley|nl2br|_linkify|htmlspecialchars}</h1>

<table class="verticalHeaders collapsed elbowroom compact " style="width:100%">
  <tr>
    <th style="width:15%">Applicant Name</th>
    <td>
      <a href="members/member/read/{c_member}">{c_full_name}</a>
    </td>
  </tr>
  <tr>
    <th style="width:15%">Applicant Email</th>
    <td>
      <a href="mailto:{c_email}">{c_email}</a>
    </td>
  </tr>
  <tr>
    <th style="width:15%">Submitted On</th>
    <td>{c_created_date|_date_format,'D n/j/y \a\t g:i A'}</td>
  </tr>
  <tr>
    <th style="width:15%">Year in School</th>
    <td>{c_yearinschool}</td>
  </tr>
  <tr>
    <th style="width:15%">Years Left</th>
    <td>{c_yearsleft}</td>
  </tr>
  <tr>
    <th style="width:15%">Similar Club Experience</th>
    <td>{c_club|smiley|nl2br|_linkify|htmlspecialchars}</td>
  </tr>
  <tr>
    <th style="width:15%">Leadership Experience</th>
    <td>{c_leadership|smiley|nl2br|_linkify|htmlspecialchars}</td>
  </tr>
  <tr>
    <th style="width:15%">Climbing Experience</th>
    <td>{c_climbing|smiley|nl2br|_linkify|htmlspecialchars}</td>
  </tr>
  <tr>
    <th style="width:15%">Kayaking Experience</th>
    <td>{c_kayaking|smiley|nl2br|_linkify|htmlspecialchars}</td>
  </tr>
  <tr>
    <th style="width:15%">Biking Experience</th>
    <td>{c_biking|smiley|nl2br|_linkify|htmlspecialchars}</td>
  </tr>
  <tr>
    <th style="width:15%">Hiking/Backpacking Experience</th>
    <td>{c_hiking|smiley|nl2br|_linkify|htmlspecialchars}</td>
  </tr>
  <tr>
    <th style="width:15%">Caving Experience</th>
    <td>{c_caving|smiley|nl2br|_linkify|htmlspecialchars}</td>
  </tr>
  <tr>
    <th style="width:15%">Snowsports Experience</th>
    <td>{c_snowsports|smiley|nl2br|_linkify|htmlspecialchars}</td>
  </tr>
  <tr>
    <th style="width:15%">Other Experience</th>
    <td>{c_other|smiley|nl2br|_linkify|htmlspecialchars}</td>
  </tr>
  <tr>
    <th style="width:15%">Why Become an Officer?</th>
    <td>{c_whyofficer|smiley|nl2br|_linkify|htmlspecialchars}</td>
  </tr>
  <tr>
    <th style="width:15%">Interested in purchasing gear</th>
    <td><input type="checkbox" {c_purchasing} disabled="true" /></td>
  </tr>
  <tr>
    <th style="width:15%">Interested in treasurer</th>
    <td><input type="checkbox" {c_treasurer} disabled="true" /></td>
  </tr>
  <tr>
    <th style="width:15%">Interested in quartermaster</th>
    <td><input type="checkbox" {c_quartermaster} disabled="true" /></td>
  </tr>
  <tr>
    <th style="width:15%">Interested advising leaders</th>
    <td><input type="checkbox" {c_advisor} disabled="true" /></td>
  </tr>
</table>

{HEADERS:}
<hr />
<h2>Trips Led</h2>
{:HEADERS}
{tripsled:}
<table class="ruled compact collapsed cleanHeaders">
  <tr>
    <th>Title</th>
    <th>Location</th>
    <th>Date</th>
    <th style="width:10%">Number of Attendees</th>
  </tr>{tripled:}
  <tr {CLASS}>
    <td><a href="members/adventure/read/{c_uid}">{c_title}</a></td>
    <td>{c_location}</td>
    <td>{c_start_date|_date_format,'m/d/Y'}</td>
    <td style="width:10%;text-align:center">{num_attendees}</td>
  </tr>{:tripled}
</table>
<p>{num_led} total trips led</p>
{:tripsled}

{NONELED:}
<p class="notice">No trips were led by this member.</p>
{:NONELED}

{HEADERS:}
<hr />
<h2>Trips Attended</h2>
{:HEADERS}
{tripsatt:}
<table class="ruled compact collapsed cleanHeaders">
  <tr>
    <th>Title</th>
    <th>Location</th>
    <th>Date</th>
  </tr>{tripatt:}
  <tr>
    <td><a href="members/adventure/read/{c_uid}">{c_title}</a></td>
    <td>{c_location}</td>
    <td>{c_start_date|_date_format,'m/d/Y'}</td>
  </tr>{:tripatt}
</table>
<p>{num_att} total trips attended</p>
{:tripsatt}

{NONEATTENDED:}
<p class="notice">No trips were attended by this member.</p>
{:NONEATTENDED}


