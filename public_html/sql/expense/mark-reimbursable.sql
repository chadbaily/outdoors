update [_]expense as ex
set ex.c_reimbursable = 1
where ex.c_uid = {expense,int}
