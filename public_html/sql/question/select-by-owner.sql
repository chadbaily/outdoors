select
    ad.c_title as ad_title,
    ad.c_uid as t_adventure,
    qu.c_text,
    qu.c_type,
    qu.c_uid as t_question
from [_]question as qu
    inner join [_]adventure as ad on ad.c_uid = qu.c_adventure
where qu.c_owner = {owner,int,,,0,c_owner}
    and qu.c_deleted <> 1
    and ad.c_deleted <> 1
order by {orderby,none,,0,ad.c_uid}
