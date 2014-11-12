select distinct me.c_uid, me.c_full_name
from [_]application as app
    inner join [_]member as me on me.c_uid = app.c_member
where app.c_deleted <> 1
    and me.c_deleted <> 1
order by me.c_full_name
