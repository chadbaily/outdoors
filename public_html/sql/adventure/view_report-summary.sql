select
    count(*) as total,
    sum(if(at.c_status & {waitlisted,int} = {waitlisted,int}, 1, 0)) as waitlisted
from [_]adventure as ad
    inner join [_]attendee as at on at.c_adventure = ad.c_uid
where ad.c_uid = {adventure,int}
    and ad.c_deleted = 0
    and at.c_deleted = 0
