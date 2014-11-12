select
    ex.*,
    cat.c_title as cat_title
from [_]expense as ex
    inner join [_]expense_category as cat on ex.c_category = cat.c_uid
where c_report = {report,int}
    and ex.c_deleted <> 1
    and cat.c_deleted <> 1
order by ex.c_expense_date
