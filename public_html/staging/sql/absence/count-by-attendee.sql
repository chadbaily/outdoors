select count(*) as num
from [_]absence
where c_attendee = {attendee,int}
    and c_deleted <> 1
