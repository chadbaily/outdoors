-- This is not really the owner, but rather the MEMBER.  After the member that
-- creates the expense report accepts it, the owner gets changed to root.
select
    re.*,
    coalesce(sum(ex.c_amount), 0.00) as total
from [_]expense_report as re
    left outer join [_]expense as ex on ex.c_report = re.c_uid
        and ex.c_deleted <> 1
where re.c_member = {leader,int}
    and re.c_deleted <> 1
group by re.c_uid
order by re.c_created_date DESC
