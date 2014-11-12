select
    me.c_full_name,
    me.c_uid as t_member,
    ad.c_title,
    ad.c_uid as t_address,
    c_primary
from [_]address as ad
    inner join [_]member as me on me.c_uid = ad.c_owner
where ad.c_deleted <> 1
    and me.c_deleted <> 1
order by {orderby,none,,0,me.c_last_name}
