select
    c_uid,
    c_title
from [_]location
where ({title,char} is null or c_title like {title,char})
    and c_deleted <> 1
order by c_title
