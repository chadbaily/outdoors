select
    ic.c_uid as cat_uid,
    ic.c_title as cat_title,
    ty.c_uid,
    ty.c_title,
    sum(case when (it.c_status = {missing,int}) then 0 else it.c_qty end)
        - sum(coalesce(
            case when (it.c_status & {checked_out,int}) then it.c_qty end,
            cg.c_qty,
            0
        )) as available
from [_]item_category as ic 
    inner join [_]item_type as ty on ty.c_category = ic.c_uid
    inner join [_]mutex as mu on mu.c_mutex in (0, 1)
    left outer join [_]item as it on it.c_type = ty.c_uid
        and mu.c_mutex = 0
        and it.c_deleted = 0
    left outer join [_]checkout_gear as cg on ty.c_uid = cg.c_type
        and mu.c_mutex = 1
        and cg.c_status = {checked_out,int}
        and cg.c_deleted = 0
where ({cat,int} is null or ic.c_uid = {cat,int})
    and ty.c_deleted = 0
    and ic.c_deleted = 0
group by ic.c_uid, ic.c_title, ty.c_uid, ty.c_title
order by ic.c_title, ty.c_title
