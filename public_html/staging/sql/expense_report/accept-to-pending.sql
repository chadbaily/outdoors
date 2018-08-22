update [_]expense
    set c_owner = {owner,int}
where c_report = {report,int}
    and c_deleted <> 1
