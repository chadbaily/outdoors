update [_]expense as ex
    inner join [_]expense_submission_expense as ese on ex.c_uid = ese.c_expense
    inner join [_]expense_submission as es on es.c_uid = ese.c_submission
set ex.c_status = {submitted,int},
    es.c_status = {submitted,int}
where es.c_uid = {submission,int}
