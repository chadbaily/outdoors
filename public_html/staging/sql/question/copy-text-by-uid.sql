-- Update the target table so its c_text matches the c_text of another question
-- (source).  To do this, we need a reference question, which is just one of the
-- questions with the same c_text as the target.
update
    [_]question as target,
    [_]question as source,
    [_]question as reference,
    [_]adventure as ad
set target.c_text = source.c_text
where target.c_text = reference.c_text
    and reference.c_uid = {target,int}
    and source.c_uid = {source,int}
    and target.c_adventure = ad.c_uid
    and ad.c_end_date < current_date
