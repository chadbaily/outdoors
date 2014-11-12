-- Select all questions for a given adventure
select
    c_text,
    c_uid,
    c_type
from [_]question
where c_adventure = {adventure,int}
    and c_deleted <> 1
order by [_]question.c_created_date
