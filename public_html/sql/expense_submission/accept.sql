insert into [_]transaction
    (c_owner, c_creator, c_created_date, c_reimbursable, c_category,
     c_amount, c_from, c_to, c_expense)
select
    {member,int}, {member,int}, now(), ex.c_reimbursable, ex.c_category,
     ex.c_amount, {from,int}, {to,int}, ex.c_uid
from [_]expense as ex
where c_uid = {expense,int}
    and c_deleted <> 1
