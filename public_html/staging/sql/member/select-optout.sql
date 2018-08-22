select
    concat('activity_',act.c_uid) as c_type_and_id,
    act.c_title
from [_]activity_category as act
where act.c_deleted <> 1
union all
select
    concat('email_',e.c_uid) as c_type_and_id,
    e.c_title
from [_]email_category as e
where e.c_deleted <> 1
order by c_type_and_id
