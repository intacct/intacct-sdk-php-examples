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
 *  1.
 *
 *  See https://github.com/Intacct/intacct.github.io/tools/php-sdk/create-example/
 *  for detailed instructions on running this example.
 */

$loader = require __DIR__ . '\vendor\autoload.php';

use Intacct\IntacctClient;
use Intacct\Content;
use Intacct\Functions\AccountsReceivable\CustomerCreate;


/**
 *
 */
try {

    $client = new IntacctClient([
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
    ]);

    $customerCreate1 = new CustomerCreate();
    $customerCreate1->setCustomerName("Joshua Granley");

    $customerCreate2 = new CustomerCreate();
    $customerCreate2->setCustomerName("Ria Jones");

    $content = new Content([$customerCreate1, $customerCreate2]);  // Wrap function calls in a Content instance.

//    // Call the client instance to execute the Content.
//    $response = $client->execute($content, false, '', false, []);
//
//    $simpleXMLresponses = $response->getOperation()->getResults();
//    foreach ($simpleXMLresponses as $data) {
//        var_dump($data);
//    }

} catch (InvalidArgumentException $e) {

    // print_r($e);  //Feel free to look at everything returned

    // Exceptions are printed for demonstration purposes only -- more error handling is needed in
    // production code.
    echo "Example 3, " . $e->getMessage();

    echo "\n\n"; // blank to separate examples.
}

