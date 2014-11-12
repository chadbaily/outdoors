select
    me.c_uid, 
    me.c_first_name, 
    me.c_last_name,
    case when (ifnull({view_private,int}, 0) = 0 and me.c_email_hidden)
        then ''
        else me.c_email end as c_email,
    coalesce(ph.c_phone_number, '') as phone_number,
    coalesce(ct.c_abbreviation, 'blank') as c_abbreviation,
    coalesce(ch.c_screenname, '') as c_screenname
from 
    [_]member as me
    inner join [_]membership as ms on ms.c_member = me.c_uid
    left outer join [_]phone_number as ph 
        on ph.c_owner = me.c_uid
        and ph.c_primary = 1
        and ph.c_deleted <> 1
        and ({view_private,int} > 0 or ph.c_hidden = 0)
    left outer join [_]chat as ch
        on ch.c_owner = me.c_uid
        and ch.c_primary = 1
        and ch.c_deleted <> 1
        and ({view_private,int} > 0 or ch.c_hidden = 0)
    left outer join [_]chat_type as ct on ct.c_uid = ch.c_type
        and ct.c_deleted <> 1
where 
    ({view_inactive,int} > 0 or (
        ms.c_status & {active,int} <> 0
        and now() >= ms.c_begin_date 
        and now() <= ms.c_expiration_date
    ))
    and ({view_private,int} > 0 or me.c_hidden = 0)
    and ms.c_deleted <> 1
    and me.c_deleted <> 1
    and ({name,char} is null or me.c_full_name like {name,char})
    and ({email,char} is null or me.c_email like {email,char})
group by me.c_uid
order by {orderby,none,,,0,me.c_last_name}
limit {offset,int,,,0,0},{limit,int}
