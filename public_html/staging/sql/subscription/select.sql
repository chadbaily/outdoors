select
    su.c_uid as sub_uid,
    su.c_email,
    me.c_uid as me_uid,
    me.c_full_name,
    li.c_uid as li_uid,
    li.c_title
from [_]subscription as su
    inner join [_]member as me on me.c_uid = su.c_owner
    inner join [_]email_list as li on li.c_uid = su.c_list
where su.c_owner = {owner,int,,,0,su.c_owner}
    and su.c_deleted <> 1
    and me.c_deleted <> 1
    and li.c_deleted <> 1
