select
    me.c_full_name,
    me.c_uid as t_member,
    an.c_uid as t_answer,
    an.c_answer_text
from [_]answer as an
    inner join [_]member as me on me.c_uid = an.c_creator
where an.c_question = {question,int,,,0}
    and an.c_deleted <> 1
    and me.c_deleted <> 1
order by {orderby,none,,,0,an.c_uid}
