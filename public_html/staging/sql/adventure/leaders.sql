-- Gets a list of everyone who has ever led an adventure
select
    distinct me.c_uid, c_full_name
from [_]member as me
    inner join [_]adventure as ad on ad.c_owner = me.c_uid
where (ad.c_status & {active,int} = {active,int})
    and ad.c_deleted <> 1
    and me.c_deleted <> 1
order by me.c_last_name, me.c_first_name
