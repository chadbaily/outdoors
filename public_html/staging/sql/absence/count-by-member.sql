select count(*) as num
from [_]absence as ab
    inner join [_]attendee as att on ab.c_attendee = att.c_uid
where att.c_member = {member,int}
    and ab.c_deleted <> 1
    and att.c_deleted <> 1
