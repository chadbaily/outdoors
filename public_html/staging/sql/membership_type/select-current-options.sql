select
    mt.c_uid, mt.c_title, mt.c_description,
    case when mt.c_flexible = 0
        then mt.c_begin_date
        else 'Activation'
    end as c_begin_date,
    case when mt.c_flexible = 0
        then mt.c_expiration_date
        else concat(mt.c_units_granted, ' ', mt.c_unit,
            case when mt.c_units_granted > 1 then 's' else '' end,
            ' later')
    end as c_expiration_date,
    mt.c_total_cost
from [_]membership_type as mt
where mt.c_hidden = 0
    and current_date between mt.c_show_date and mt.c_hide_date
    and mt.c_deleted <> 1
