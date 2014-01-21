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
 * Index page. Controls the redeem process.
 *
 * @package Gewora/Easy nVerify
 * @author Gewora <admin@gewora.net>
 * @copyright Copyright (c) 2013 by Gewora Project Team
 * @license Apache 2.0
 * @access public
 */

if(isset($_POST["purchase_code"]) && !empty($_POST["purchase_code"]))
{
    # Include config file
    require_once 'includes/config.inc.php';

    # Include the items function
    require_once 'includes/functions/items.php';

    # Grab the purchase code
    $purchase_code = $_POST["purchase_code"];

    # Open the file which contains the used purchase code
    $used_purchase_codes_path = 'data/used_purchase_codes';

    # Make sure that the wile exists is writable
    if(!file_exists($used_purchase_codes_path)) file_put_contents($used_purchase_codes_path, '');
    if(!is_writable($used_purchase_codes_path)) {
        die('Unable to write to a needed file. Please check the chmod for the file: ' . $used_purchase_codes_path);
    }

    $used_purchase_codes = file_get_contents($used_purchase_codes_path);
    $used_purchase_codes = unserialize($used_purchase_codes);

    # Send the data to the envato API
    $url = "http://marketplace.envato.com/api/edge/" .$config['envato']['username']. "/" .$config['envato']['api_key']. "/verify-purchase:$purchase_code.json";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $json_res = curl_exec($ch);

    # Decode the JSON result and convert it to an array
    $res = json_decode($json_res, TRUE);

    # Check if the purchase code is valid
    if(isset($res['verify-purchase']['buyer'])) {
        # Purchase Code is valid
        $res['verify-purchase']['purchase_code'] = $purchase_code;
        $item_id = $res['verify-purchase']['item_id'];

        # Check if the purchase code has already been used
        if(isset($used_purchase_codes[$purchase_code])) {
            # The purchase code has already been used
            require_once 'templates/purchase_code_already_used.html';
        } else {
            # The purchase code has not been used yet
            # Call the items functions
            $items = items($res, $config, $item_id);

            # Execute the functions for this item
            # if there are any
            if($items !== FALSE) {
                # Functions have been executed successfully
                # Store the purchase code to avoid multiple uses
                $used_purchase_codes[$purchase_code] = time();
                $used_purchase_codes = serialize($used_purchase_codes);
                file_put_contents($used_purchase_codes_path, $used_purchase_codes);

                # Display the success message
                require_once 'templates/thanks.html';
            } else {
                # There is no function available for this item
                require_once 'templates/invalid_item.html';
            }
        }
    } else {
        # The purchase code is invalid

        # Display the error message
        require_once 'templates/invalid_purchase_code.html';
    }
} else {
    require_once 'templates/index.html';
}
