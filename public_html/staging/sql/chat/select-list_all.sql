select
    me.c_full_name,
    me.c_uid as t_member,
    ct.c_title,
    ch.c_uid as t_chat,
    ch.c_screenname,
    if(ch.c_primary, 'Y', 'N') as c_primary
from
    [_]chat as ch
    inner join [_]member as me on me.c_uid = ch.c_owner
    inner join [_]chat_type as ct on ct.c_uid = ch.c_type
where ch.c_deleted <> 1
    and me.c_deleted <> 1
    and ct.c_deleted <> 1
order by {orderby,none,,0,me.c_last_name}
