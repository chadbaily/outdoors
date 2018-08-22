select
    ic.c_uid as cat_uid,
    ic.c_title as cat_title,
    ty.c_uid,
    ty.c_title
from [_]item_category as ic 
    inner join [_]item_type as ty on ty.c_category = ic.c_uid
where ({cat,int} is null or ic.c_uid = {cat,int})
    and ty.c_deleted = 0
    and ic.c_deleted = 0
order by ic.c_title, ty.c_title
