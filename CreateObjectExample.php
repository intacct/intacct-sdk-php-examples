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
 *  2. Use functions to create one CUSTOMER object and update another.
 *  3. Wrap the functions in Content instance.
 *  4. Execute the request and send the Content to the gateway.
 *
 *  Note: An example that shows how to catch useful error info if using
 *  a transaction is also provided but commented out.
 *
 */

$loader = require __DIR__ . '\vendor\autoload.php';

use Intacct\IntacctClient;
use Intacct\Content;
use Intacct\Functions\AccountsReceivable\CustomerCreate;
use Intacct\Functions\AccountsReceivable\CustomerUpdate;
use Intacct\Exception\ResultException;
use Intacct\Exception\ResponseException;


// Wrap your calls in a try block to support error handling.
try {
    $client = new IntacctClient([
        'profile_file' => __DIR__ . '\.intacct\credentials.ini'
    ]);

    // Create CUSTOMER objects.
    $customerCreate = new CustomerCreate();
    $customerCreate->setCustomerName("Joshua Granley");

    // Update CUSTOMER object
    $customerUpdate = new CustomerUpdate();
    $customerUpdate->setCustomerId(10206);  // Update with valid Customer ID!
    $customerUpdate->setComments("Gold star customer!");

    $content = new Content([ // Wrap function calls in a Content instance.
        $customerCreate,
        $customerUpdate
    ]);

    // Call the client instance to execute the Content.
    $response = $client->execute($content);

     // Iterate response
      $simpleXMLresponses = $response->getOperation()->getResults();
         foreach ($simpleXMLresponses as $data) {
            var_dump($data);
      }

/*
    // Example for getting useful error information when using a transaction.
    // Call the client instance to execute the Content. Using a transaction
    $response = $client->execute($content, true, '', false, []);

    // Get array of result objects
    $results = $response->getOperation()->getResults();

    // Iterate results with code for returning error that cause rollback in
    // case the transaction fails.
    $i = 0;
    foreach ($results as $data) {
        if($data->getStatus() == "aborted" && count($data->getErrors()) >  0) {
            echo "Errors in result number $i have caused the rollback" . ":\n";
            var_dump($data);
        }
        else {
            var_dump($data);
        }
        $i++;
    }
*/

} catch (ResultException $e) {
    print_r($e);
} catch (ResponseException $e) {
    print_r($e);
} catch (\Exception $e) {
    echo get_class($e) . ' ' . $e->getMessage();
}

