select
    ec.c_uid,
    ec.c_title,
    sum(case when (tr.c_to = {us,int}) then tr.c_amount else 0.00 end) as income,
    sum(case when (tr.c_from = {us,int}) then tr.c_amount else 0.00 end) as expenditures
from [_]expense_category as ec
    left outer join [_]transaction as tr on ec.c_uid = tr.c_category
        and tr.c_deleted <> 1
    left outer join [_]member as mto on tr.c_to = mto.c_uid
    left outer join [_]member as mfrom on tr.c_from = mfrom.c_uid
where ({begin,date} is null or tr.c_created_date >= {begin,date})
    and ({end,date} is null or tr.c_created_date <= {end,date})
    and ({name,char} is null or mto.c_uid is null or mfrom.c_uid is null
        or mto.c_full_name like {name,char}
        or mfrom.c_full_name like {name,char})
    and ec.c_deleted <> 1
    and mfrom.c_deleted <> 1
    and mto.c_deleted <> 1
group by ec.c_uid
