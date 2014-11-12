select sum(ex.c_amount) as total
from [_]expense as ex
    inner join [_]expense_submission_expense as ese on ex.c_uid = ese.c_expense
where c_submission = {submission,int}
    and ex.c_deleted <> 1
    and ese.c_deleted <> 1
