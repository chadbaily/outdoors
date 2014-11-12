insert into [_]email
    (c_owner,
     c_creator,
     c_created_date,
     c_subject,
     c_message,
     c_what_relates_to,
     c_related_uid)
values
    ({user,int},
     {user,int},
     current_date,
     {subject,varchar},
     {message,varchar},
     {what_relates_to,char},
     ifnull({related_uid,int}, 0));
