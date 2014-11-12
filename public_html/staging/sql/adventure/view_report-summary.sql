select
    sum(if(at.c_status & 1 = 1, 1, 0)) as joined,
    sum(if(at.c_status & 8 = 8, 1, 0)) as waitlisted
from [_]adventure as ad
    inner join [_]attendee as at on at.c_adventure = ad.c_uid
where ad.c_uid = {adventure,int}
    and ad.c_deleted = 0
    and at.c_deleted = 0
