select
    ex.*,
    ese.c_uid as ese_uid,
    cat.c_title as cat_title,
    me.c_full_name
from [_]expense as ex
    inner join [_]expense_category as cat on ex.c_category = cat.c_uid
    inner join [_]expense_submission_expense as ese on ex.c_uid = ese.c_expense
    inner join [_]expense_report as rep on rep.c_uid = ex.c_report
    inner join [_]member as me on me.c_uid = rep.c_member
where c_submission = {submission,int}
    and ex.c_deleted <> 1
    and cat.c_deleted <> 1
    and ese.c_deleted <> 1
    and rep.c_deleted <> 1
    and me.c_deleted <> 1
order by ex.c_expense_date
