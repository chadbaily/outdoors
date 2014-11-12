select distinct
    co.c_member,
    me.c_first_name,
    me.c_last_name
from [_]checkout as co
    inner join [_]member as me on me.c_uid = co.c_member
where co.c_deleted <> 1
    and me.c_deleted <> 1
order by me.c_last_name, me.c_first_name
