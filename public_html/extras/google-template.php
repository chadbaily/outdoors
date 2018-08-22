<!-- Begin Upcoming Adventures -->
      <h4 class="boxed">A Few Upcoming Adventures</h4>
      <table class="compact collapsed elbowroom" width="100%">
        {UPCOMING:}<tr> 
          <td>{img}</td>
          <td nowrap>{c_start_date|_date_format,'M j'}</td>
          <td>
            <a href="members/adventure/read/{c_uid}">{c_title}</a><br>
          </td>
        </tr>{:UPCOMING}
      </table>
      <div align="right" class="tiny">
        <a href="members/adventure">More Adventures</a> |
        <a href="members/adventure/list_all?criteria=joined">Adventures You've Attended</a> |
        <a href="members/calendar">Calendar of Events</a>
        <br>
        <img src="assets/smiley-tiny.png" height="12" width="12" alt="Favorites"> These upcoming adventures match your
        <a href="members/member/choose_activities/{MEMBER}">interests</a>.
      </div>
<!-- End Upcoming Adventures -->