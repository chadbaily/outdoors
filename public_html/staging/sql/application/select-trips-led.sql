select
  ad.c_start_date,
  ad.c_uid,
  ad.c_title,
  loc.c_title as c_location,
  count(*) as num_attendees
from [_]application as app
inner join [_]member as me on me.c_uid = app.c_member
inner join [_]adventure as ad on ad.c_owner = me.c_uid
inner join [_]attendee as att on att.c_adventure = ad.c_uid
inner join [_]location as loc on loc.c_uid = ad.c_destination
where ad.c_status = 4
  and ad.c_start_date <= now() and app.c_uid = {application,int}
group by ad.c_uid
order by ad.c_start_date DESC
