<?php

/*
 * This file is part of Easy nVerify
 *
 * (c) 2014 Gewora.net
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Items functions. Controls custom actions for each item
 *
 * @package Gewora/Easy nVerify
 * @author Gewora <admin@gewora.net>
 * @copyright Copyright (c) 2013 by Gewora Project Team
 * @license Apache 2.0
 * @access public
 */


/**
 * Items
 *
 * Perform custom actions for each item
 *
 *
 * @param $envato_data - The data which has been fetched from envato
 * @param $config - The config (includes/config.inc.php)
 *
 * @return boolean - TRUE if actions have been performed
 * @return boolean - FALSE if there are no actions for this item
 *
 */
function items($envato_data, $config)
{
    $valid = FALSE;
    $item_id = $envato_data['verify-purchase']['item_id'];

    if($item_id == 'ITEM_ID_1') { # <--- FILL IN A ITEM ID
        ##### ACTIONS FOR THIS ITEM - START #####

        $custom = 'some custom data for this item 123456';

        ##### ACTIONS FOR THIS ITEM - END #####
        $valid = TRUE; # DO NOT REMOVE THIS

    } elseif($item_id == 'ITEM_ID_2') { # <--- FILL IN ANOTHER ITEM ID (If you have one)
        ##### ACTIONS FOR THIS ITEM - START #####

        $custom = 'another custom data for this item';

        ##### ACTIONS FOR THIS ITEM - END #####
        $valid = TRUE; # DO NOT REMOVE THIS
    }

    if($valid === TRUE) {
        # Connect to the database
        $mysqli = new mysqli($config['host'], $config['user'], $config['password'], $config['database']);
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        # Insert the purchase code data
        $time = time();
        $stmt = $mysqli->prepare('INSERT INTO purchases(purchase_code, item_id, buyer, custom, used_on) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sissi',
            $envato_data['verify-purchase']['purchase_code'],
            $envato_data['verify-purchase']['buyer'],
            $envato_data['verify-purchase']['item_id'],
            $custom,
            $time);
        $stmt->execute();
        $stmt->close();

    }
    return $valid;  # DO NOT REMOVE THIS
}

