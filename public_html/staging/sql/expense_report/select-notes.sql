select
    me.c_full_name,
    exn.*
from [_]expense_report_note as exn
    inner join [_]member as me on exn.c_creator = me.c_uid
where c_report = {report,int}
    and exn.c_deleted <> 1
    and me.c_deleted <> 1
order by exn.c_created_date
