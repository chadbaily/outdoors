select
    me.c_full_name,
    su.c_email,
    su.c_password,
    su.c_uid
from [_]member as me
    inner join [_]subscription as su on su.c_owner = me.c_uid
where su.c_list = {email_list,int}
    and su.c_deleted <> 1
    and me.c_deleted <> 1
