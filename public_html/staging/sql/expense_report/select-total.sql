select sum(c_amount) as total
from [_]expense
where c_report = {report,int}
    and c_deleted <> 1
