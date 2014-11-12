select
    att.*
from [_]attendee as att
where att.c_adventure = {adventure,int}
    and ({status,int} is null or att.c_status = {status,int})
    and att.c_deleted <> 1
order by att.c_joined_date
