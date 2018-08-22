select 
  c_title,
  app.c_created_date,
  c_yearinschool,
  c_yearsleft,
  c_club,
  c_leadership,
  coalesce(c_climbing,'') c_climbing,
  coalesce(c_kayaking,'') c_kayaking,
  coalesce(c_biking,'') c_biking,
  coalesce(c_hiking,'') c_hiking,
  coalesce(c_caving,'') c_caving,
  coalesce(c_snowsports,'') c_snowsports,
  coalesce(c_other,'') c_other,
  coalesce(c_whyofficer,'') c_whyofficer,
  case c_purchasing when 0 then '' else 'checked="checked"' end as c_purchasing,
  case c_treasurer when 0 then '' else 'checked="checked"' end as c_treasurer,
  case c_quartermaster when 0 then '' else 'checked="checked"' end as c_quartermaster,
  case c_advisor when 0 then '' else 'checked="checked"' end as c_advisor,
  c_member,
  c_full_name,
  c_email
from [_]application app
inner join [_]member me on me.c_uid = app.c_member
where app.c_uid = {application,int}

