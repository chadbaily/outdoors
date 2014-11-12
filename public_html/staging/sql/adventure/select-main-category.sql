select ac.c_category, count(*) as num
from [_]adventure_activity as aa
    inner join [_]activity as ac on ac.c_uid = aa.c_activity
where aa.c_adventure = {adventure,int}
    and aa.c_deleted <> 1
    and ac.c_deleted <> 1
group by ac.c_category
order by ac.c_category desc, num desc
