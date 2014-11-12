select mt.c_title, ms.*
from [_]membership as ms
    inner join [_]membership_type as mt on ms.c_type = mt.c_uid
where ms.c_member = {member,int}
    and (ms.c_status & {inactive,int} = {inactive,int})
    and ms.c_deleted <> 1
    and mt.c_deleted <> 1
    and ifnull(ms.c_expiration_date, current_date) >= current_date
