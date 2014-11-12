insert into [_]email
    (c_owner,
     c_creator,
     c_subject,
     c_message,
     c_what_relates_to,
     c_created_date,
     c_related_uid)
values
    ({user,int},
     {user,int},
     {subject,varchar},
     {message,varchar},
     {what_relates_to,char},
     current_date,
     ifnull({related_uid,int}, 0));
