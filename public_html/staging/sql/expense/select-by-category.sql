select
    cat.c_title,
    ex.c_uid,
    ex.c_expense_date,
    ex.c_adventure,
    ex.c_merchant,
    ex.c_description,
    ex.c_amount,
    ex.c_report,
    me.c_full_name
from [_]expense as ex
    inner join [_]expense_category as cat on cat.c_uid = ex.c_category
    inner join [_]expense_report as rep on rep.c_uid = ex.c_report
    inner join [_]member as me on me.c_uid = rep.c_member
where
    ({begin,date} is null or ex.c_expense_date >= {begin,date})
    and ({end,date} is null or ex.c_expense_date <= {end,date})
    and ex.c_category = {category,int}
    and ({reimbursable,int} is null
        or ex.c_reimbursable)
    and ({status,int} is null or ex.c_status = {status,int})
    and ex.c_deleted <> 1
    and rep.c_deleted <> 1
    and cat.c_deleted <> 1
    and me.c_deleted <> 1
order by ex.c_expense_date
