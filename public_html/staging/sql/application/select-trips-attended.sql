select
  ad.c_start_date,
  ad.c_uid,
  ad.c_title,
  loc.c_title as c_location
from [_]application as app
inner join [_]member as me on me.c_uid = app.c_member
inner join m_attendee as att on me.c_uid = att.c_member
inner join m_adventure as ad on att.c_adventure = ad.c_uid
inner join m_location as loc on loc.c_uid = ad.c_destination
where ad.c_start_date <= now() and att.c_status & 1 = 1 and ad.c_status = 4 and
  app.c_uid = {application,int}
