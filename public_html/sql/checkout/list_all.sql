select
    co.c_uid,
    co.c_created_date,
    co.c_due_date,
    me.c_first_name,
    me.c_last_name,
    me.c_email,
    off.c_full_name as officer_name,
    co.c_status,
    sum(coalesce(it.c_qty, 0)) + sum(coalesce(cg.c_qty, 0)) as qty,
    sum(case when coit.c_status & {checked_out,int} = {checked_out,int} then it.c_qty else 0 end)
        + sum(case when cg.c_status & {checked_out,int} = {checked_out,int} then cg.c_qty else 0 end)
    as qty_out,
    case when co.c_due_date <= current_date then 'overdue' else '' end as overdue
from [_]checkout as co
    inner join [_]member as me on me.c_uid = co.c_member
    inner join [_]member as off on off.c_uid = co.c_creator
    inner join [_]mutex as m on m.c_mutex in (0, 1)
    left outer join [_]checkout_item as coit on c_mutex = 0
        and coit.c_checkout = co.c_uid
        and coit.c_deleted = 0
    left outer join [_]item as it on it.c_uid = coit.c_item
        and it.c_deleted = 0
    left outer join [_]checkout_gear as cg on c_mutex = 1
        and cg.c_checkout = co.c_uid
        and cg.c_deleted = 0
where ({status,int} is null or co.c_status & {status,int} = {status,int})
    and ({member,int} is null or co.c_member = {member,int})
    and ({begin,date} is null or co.c_created_date >= {begin,date})
    and ({end,date} is null or co.c_created_date <= {end,date})
    and ({due,date} is null or co.c_due_date <= {due,date})
    and ({type,int} is null or it.c_type = {type,int} or cg.c_type = {type,int})
    and ({item,int} is null or it.c_uid = {item,int})
    and co.c_deleted = 0
    and me.c_deleted = 0
    and off.c_deleted = 0
group by
    co.c_uid,
    co.c_created_date,
    me.c_first_name,
    me.c_last_name,
    me.c_email,
    off.c_full_name,
    co.c_status
order by co.c_created_date desc
