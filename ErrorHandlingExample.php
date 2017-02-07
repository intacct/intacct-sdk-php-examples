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
 *  This example demonstrates the following Exceptions:
 *  1. ResponseException for invalid login--incorrect sender_id
 *  2. OperationException for invalid login--incorrect user_password
 *  3. InvalidArgumentException for missing argument--Missing Customer Name on a Customer
 *  4. ResultException for invalid query--APBIL is set as the object instead of APBILL
 *  5. ResultException for transaction aborted--GLENTRIES doesn't exist
 */

$loader = require __DIR__ . '\vendor\autoload.php';

use Intacct\IntacctClient;
use Intacct\QueryClient;
use Intacct\Content;
use Intacct\Exception;
use Intacct\Exception\OperationException;
use Intacct\Exception\ResultException;
use Intacct\Exception\ResponseException;
use Intacct\Functions\AccountsReceivable\CustomerCreate;
use Intacct\Functions\Common\ReadByQuery;
use Intacct\Functions\Common\ReadByName;

/**
 * Example for invalid login, all information is incorrect.  ResponseException is thrown.
*/
try {

    $client = new IntacctClient([
        'profile_name' => 'wrong_sender_password',
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
    ]);

} catch (ResponseException $e) {
    // print_r($e);  //Feel free to look at everything returned

    // Exceptions are printed for demonstration purposes only -- more error handling is needed in
    // production code.
    echo "Example 1, " . $e->getMessage(). "\n";

    foreach ($e->getErrors() as $error) {
        echo $error . "\n"; //Just simply print out the error message.
    };

    echo "\n"; // blank to separate examples.
}

/**
 * Example for invalid login, user password is incorrect.  OperationException is thrown.
 */
try {

    $client = new IntacctClient([
        'profile_name' => 'wrong_user_password',
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
    ]);

} catch (OperationException $e) {
    // print_r($e);  //Feel free to look at everything returned

    // Exceptions are printed for demonstration purposes only -- more error handling is needed in
    // production code.
    echo "Example 2, " . $e->getMessage(). "\n";

    foreach ($e->getErrors() as $error) {
        echo $error . "\n"; //Just simply print out the error message.
    };

    echo "\n"; // blank to separate examples.
}

/**
 * Example for missing required parameter.  InvalidArgumentException is thrown.
 */
try {

    $client = new IntacctClient([
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
    ]);

    $customerCreate = new CustomerCreate();  // Attempting to create a CUSTOMER.
    $content = new Content([$customerCreate]);  // Wrap function calls in a Content instance.

    // Call the client instance to execute the Content.
    $response = $client->execute($content, false, '', false, []);

} catch (InvalidArgumentException $e) {

    // print_r($e);  //Feel free to look at everything returned

    // Exceptions are printed for demonstration purposes only -- more error handling is needed in
    // production code.
    echo "Example 3, " . $e->getMessage();

    echo "\n\n"; // blank to separate examples.
}

/**
 * Example for no data returned in query.  ResultException is thrown.
 */
try {

    $queryClient = new QueryClient([
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
    ]);

    $readByQuery = new ReadByQuery();

    $readByQuery->setObjectName('APBIL'); //typo on APBILL

    $readByQuery->setQuery("TOTALENTERED > 100"); //And let's query for total entered greater than 100

    $records = $queryClient->executeQuery($readByQuery); //Run that query

} catch (ResultException $e) {
    // print_r($e);  //Feel free to look at everything returned

    // Exceptions are printed for demonstration purposes only -- more error handling is needed in
    // production code.
    echo "Example 4, " . $e->getMessage(). "\n";

    foreach ($e->getErrors() as $error) {
        echo $error . "\n"; //Just simply print out the error message.
    };

    echo "\n"; // blank to separate examples.
}

/**
 * Example for transactional errors.  ResultException is thrown.
 */
try {

    $client = new IntacctClient([
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
    ]);

    $readByName = new ReadByName();
    $readByName->setObjectName('GLENTRIES');

    $content = new Content([$readByName]);  // Wrap function calls in a Content instance.

    // Call the client instance to execute the Content.
    $response = $client->execute($content, true, '', false, []); //We'll just set this as a true transaction

    // No error thrown yet...so let's find out if the transaction was successful
    $response->getOperation()->getResult()->ensureStatusSuccess();

} catch (ResultException $e) {
    // print_r($e);  //Feel free to look at everything returned

    // Exceptions are printed for demonstration purposes only -- more error handling is needed in
    // production code.
    echo "Example 5, " . $e->getMessage(). "\n";

    foreach ($e->getErrors() as $error) {
        echo $error . "\n"; //Just simply print out the error message.
    };

    echo "\n"; // blank to separate examples.
}