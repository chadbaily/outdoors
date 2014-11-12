insert into [_]email_recipient(c_email, c_recipient, c_created_date)
select distinct {email,int}, me.c_uid, current_date
from [_]member as me
    inner join [_]attendee as at on at.c_member = me.c_uid
where
    at.c_adventure = {adventure,int}
    and at.c_deleted <> 1
    and me.c_deleted <> 1
    and (
        me.c_uid = {leader,int}
        or {status,int} is null
        or at.c_status & {status,int} = {status,int});
