insert into [_]item_note
(c_owner, c_creator, c_created_date, c_status, c_item, c_condition, c_note)
select
    {member,int},
    {member,int},
    now(), 
    it.c_status,
    it.c_uid,
    it.c_condition,
    coalesce({note,varchar,255}, "Checked in")
from [_]item as it
    inner join [_]checkout_item as co on it.c_uid = co.c_item
where co.c_uid = {checkout_item,int}
