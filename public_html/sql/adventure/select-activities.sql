select ac.c_title as ac_title
from [_]adventure_activity as aa
    inner join [_]activity as ac on ac.c_uid = aa.c_activity
where aa.c_adventure = {adventure,int}
