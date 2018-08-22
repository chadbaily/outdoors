select
    me.c_full_name,
    mn.*
from [_]member_note as mn
    inner join [_]member as me on mn.c_creator = me.c_uid
where c_member = {member,int}
    and mn.c_deleted <> 1
    and me.c_deleted <> 1
order by mn.c_created_date
