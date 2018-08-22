select ms.c_uid, mt.c_title, ms.c_total_cost,
    case 
        when mt.c_flexible then {start,date}
        else ms.c_begin_date
    end as c_begin_date,
    case
        when mt.c_flexible then case
            when ms.c_unit = 'day' then date_add({start,date}, interval ms.c_units_granted day)
            when ms.c_unit = 'month' then date_add({start,date}, interval ms.c_units_granted month)
            when ms.c_unit = 'year' then date_add({start,date}, interval ms.c_units_granted year)
        end
        else ms.c_expiration_date
    end as c_expiration_date
from [_]membership as ms
    inner join [_]membership_type as mt on ms.c_type = mt.c_uid
where ms.c_uid = {membership,int}
    and ms.c_deleted <> 1
    and mt.c_deleted <> 1
