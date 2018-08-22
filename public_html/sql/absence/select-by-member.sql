select ad.c_title, ab.c_uid, ab.c_severity, ab.c_comment
from [_]absence as ab
    inner join [_]attendee as at on ab.c_attendee = at.c_uid
    inner join [_]adventure as ad on at.c_adventure = ad.c_uid
where at.c_member = {member,int}
    and at.c_deleted <> 1
    and ab.c_deleted <> 1
    and ad.c_deleted <> 1
