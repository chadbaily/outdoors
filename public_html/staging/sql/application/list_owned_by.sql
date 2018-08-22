select
    app.c_uid, app.c_title, app.c_created_date
from [_]application as app
where app.c_member = {member,int}
    and app.c_deleted <> 1
order by app.c_created_date DESC
