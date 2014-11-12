select me.c_email, me.c_uid, me.c_full_name
from [_]member as me
    inner join [_]attendee as att on att.c_member = me.c_uid
    inner join [_]adventure as ad on att.c_adventure = ad.c_uid
where me.c_deleted = 0
    and att.c_deleted = 0
    and ad.c_deleted = 0
    and att.c_status & {default,int} = {default,int}
    and ad.c_uid = {adventure,int}
    and ad.c_start_date <= ifnull({start,date}, current_date)
