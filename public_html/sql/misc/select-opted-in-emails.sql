select distinct me.c_email
from [_]member as me
    inner join [_]membership as ms on ms.c_member = me.c_uid
    left outer join [_]optout as op on op.c_member = me.c_uid
        and op.c_category = {category,int}
        and op.c_deleted <> 1
where (ms.c_status & {active,int} = {active,int})
    and ms.c_begin_date <= current_date
    and ms.c_expiration_date >= current_date
    and ms.c_deleted <> 1
    and me.c_deleted <> 1
    and op.c_uid is null
    and ({member,int} is null or me.c_uid <> {member,int})
