select
    co.*,
    ra.c_title, 
    me.c_uid as t_member,
    me.c_full_name
from [_]adventure_comment as co
    inner join [_]member as me on me.c_uid = co.c_owner
    inner join [_]rating as ra on ra.c_uid = co.c_rating
where co.c_adventure = {adventure,int}
    and co.c_deleted <> 1
    and me.c_deleted <> 1
    and ra.c_deleted <> 1
