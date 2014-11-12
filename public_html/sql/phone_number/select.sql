select
    me.c_full_name,
    me.c_uid as t_member,
    ph.c_title,
    ph.c_uid as t_phone_number,
    ph.c_phone_number,
    ph.c_primary
from [_]phone_number as ph
    inner join [_]member as me on me.c_uid = ph.c_owner
where ph.c_deleted <> 1
    and me.c_deleted <> 1
order by {orderby,none,,0,me.c_last_name}
