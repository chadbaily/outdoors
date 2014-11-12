select
    qu.c_uid,
    qu.c_text,
    count(*) as num
from [_]question as qu
where qu.c_deleted <> 1
group by qu.c_text
order by num desc
limit {limit,int}
