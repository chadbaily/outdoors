select
    ad.c_uid,
    ad.c_title,
    ad.c_start_date, 
    lo.c_title as loc_title,
    lo.c_uid as loc_uid
from [_]attendee as at
    inner join [_]adventure as ad on ad.c_uid = at.c_adventure
    inner join [_]location as lo on ad.c_destination = lo.c_uid
where at.c_member = {member,int}
    and at.c_deleted <> 1
    and ad.c_deleted <> 1
    and lo.c_deleted <> 1
    and ({start,date} is null or ad.c_start_date >= {start,date})
    and ({end,date} is null or ad.c_start_date <= {end,date})
    and ({status,int} is null or at.c_status = {status,int})
order by ad.c_start_date desc
