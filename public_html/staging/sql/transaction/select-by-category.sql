select
    cat.c_title,
    tr.c_uid,
    tr.c_created_date,
    tr.c_amount,
    mto.c_full_name as to_name,
    mfrom.c_full_name as from_name,
    coalesce(ex.c_description, tr.c_description) as description
from [_]transaction as tr
    inner join [_]expense_category as cat on cat.c_uid = tr.c_category
    inner join [_]member as mfrom on mfrom.c_uid = tr.c_from
    inner join [_]member as mto on mto.c_uid = tr.c_to
    left outer join [_]expense as ex on ex.c_uid = tr.c_expense
        and ex.c_deleted <> 1
where
    ({begin,date} is null or tr.c_created_date >= {begin,date})
    and ({end,date} is null or tr.c_created_date <= {end,date})
    and tr.c_category = {category,int}
    and ({name,char} is null or mto.c_uid is null or mfrom.c_uid is null
        or mto.c_full_name like {name,char}
        or mfrom.c_full_name like {name,char})
    and tr.c_deleted <> 1
    and cat.c_deleted <> 1
    and mfrom.c_deleted <> 1
    and mto.c_deleted <> 1
order by tr.c_created_date
