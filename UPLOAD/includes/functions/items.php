<?php

function items($envato_data, $config, $item_id)
{
    if($item_id == 'ITEM_ID_1') { # <--- FILL IN A ITEM ID
        ##### ACTIONS FOR THIS ITEM - START #####

        # Do what ever you want for this item

        ##### ACTIONS FOR THIS ITEM - END #####

    } elseif($item_id == 'ITEM_ID_2') { # <--- FILL IN ANOTHER ITEM ID (If you have one)
        ##### ACTIONS FOR THIS ITEM - START #####

        # Do what ever you want for this item

        ##### ACTIONS FOR THIS ITEM - END #####

    } else {
        return FALSE;
    }
}