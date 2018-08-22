select
    ad.c_uid,
    ad.c_title,
    ad.c_start_date, 
    lo.c_title as loc_title,
    lo.c_uid as loc_uid
from [_]adventure as ad
    inner join [_]location as lo on ad.c_destination = lo.c_uid
    inner join [_]attendee as at on at.c_adventure = ad.c_uid
where at.c_member = {member,int,,,0,at.c_member}
    and ad.c_deleted <> 1
    and at.c_deleted <> 1
    and lo.c_deleted <> 1
order by ad.c_start_date desc
