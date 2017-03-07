<?php
/**
 * Copyright 2017 Intacct Corporation.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"). You may not
 * use this file except in compliance with the License. You may obtain a copy
 * of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * or in the "LICENSE" file accompanying this file. This file is distributed on
 * an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

/**
 *  This example shows you how to do the following:
 *  1. Create an IntacctClient based on a credentials file.
 *  2. Use functions to create two CUSTOMER objects.
 *  3. Wrap the functions in Content instance.
 *  4. Execute the request and send the Content to the gateway.
 *
 */

$loader = require __DIR__ . '\vendor\autoload.php';

use Intacct\IntacctClient;
use Intacct\Content;
use Intacct\Functions\AccountsReceivable\CustomerCreate;
use Intacct\Exception\ResultException;
use Intacct\Exception\ResponseException;

// Wrap your calls in a try block to support error handling.
try {

    $client = new IntacctClient([
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
    ]);

    // Create CUSTOMER objects.
    $customerCreate1 = new CustomerCreate();
    $customerCreate1->setCustomerName("Joshua Granley");

    $customerCreate2 = new CustomerCreate();
    $customerCreate2->setCustomerName("Ria Jones");

    $content = new Content([$customerCreate1, $customerCreate2]);  // Wrap function calls in a Content instance.

    // Call the client instance to execute the Content.
    $response = $client->execute($content, false, '', false, []);

    // print_r($response);  // Optionally print response information.

    // Optionally iterate response
    //  $simpleXMLresponses = $response->getOperation()->getResults();
    //     foreach ($simpleXMLresponses as $data) {
    //        var_dump($data);
    //  }

} catch (ResultException $e) {
    print_r($e);
} catch (ResponseException $e) {
    print_r($e);
} catch (\Exception $e) {
    echo get_class($e) . ' ' . $e->getMessage();
}

