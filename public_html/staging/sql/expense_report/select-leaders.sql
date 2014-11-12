select distinct me.c_uid, me.c_full_name
from [_]expense_report as ex
    inner join [_]member as me on me.c_uid = ex.c_member
where ex.c_deleted <> 1
    and me.c_deleted <> 1
order by me.c_full_name
