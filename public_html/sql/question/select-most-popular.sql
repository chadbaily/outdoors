select
    qu.c_uid,
    qu.c_text,
    count(*) as num
from [_]question as qu
    inner join [_]adventure as ad on qu.c_adventure = ad.c_uid
    inner join [_]adventure_activity as this_aa
        on this_aa.c_adventure = {adventure,int}
    inner join [_]adventure_activity as aa
        on aa.c_adventure = ad.c_uid and aa.c_activity = this_aa.c_activity
where qu.c_deleted <> 1
    and ad.c_deleted <> 1
    and aa.c_deleted <> 1
    and this_aa.c_deleted <> 1
group by qu.c_text
order by num desc
limit {limit,int}
