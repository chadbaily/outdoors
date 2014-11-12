select
    max(qu.c_uid) as c_uid,
    qu.c_text,
    count(*) as num
from [_]question as qu
    inner join [_]adventure as ad on ad.c_uid = qu.c_adventure
-- Make sure we don't mess with adventures where that aren't over yet
where ad.c_end_date < current_date
    and qu.c_deleted <> 1
    and ad.c_deleted <> 1
group by qu.c_text
order by qu.c_text
