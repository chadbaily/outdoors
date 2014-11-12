select
    ec.c_uid,
    ec.c_title,
    sum(case when (ex.c_amount > 0) then ex.c_amount else 0.00 end) as expenses,
    sum(case when (ex.c_amount < 0) then -ex.c_amount else 0.00 end) as receipts
from [_]expense_category as ec
    left outer join [_]expense as ex on ec.c_uid = ex.c_category
        and ex.c_deleted <> 1
where ({begin,date} is null or ex.c_expense_date >= {begin,date})
    and ({end,date} is null or ex.c_expense_date <= {end,date})
    and ({reimbursable,int} is null or ex.c_reimbursable)
    and ({status,int} is null or ex.c_status is null
        or (ex.c_status = {status,int}))
    and ec.c_deleted <> 1
group by ec.c_uid
