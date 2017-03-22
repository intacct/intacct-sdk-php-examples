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
 * 1. Catch a ResponseException for an invalid login due to an incorrect Web Services sender ID or password.
 * 2. Catch an OperationException for an invalid login due to an incorrect company user ID or password.
 * 3. Catch an InvalidArgumentException caused by a missing parameter.
 * 4. Catch a ResultException caused by an invalid query.
 * 5. Catch a ResultException for an aborted transaction.
 *
 *  See https://github.com/Intacct/intacct.github.io/tools/php-sdk/errors-example/
 *  for detailed instructions on running this example.
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
 * Example 1 for invalid Web Services sender ID. ResponseException is thrown.
 * Uses the 'wrong_sender_id' profile in the .ini file.
 */
try {

    $client = new IntacctClient([
        'profile_name' => 'wrong_sender_password',
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
    ]);

} catch (ResponseException $e) {
    // print_r($e);  // Optionally see everything thrown.

    // Exceptions are printed for demonstration purposes only -- more error handling is needed in
    // production code.
    echo "Example 1, " . $e->getMessage(). "\n";

    foreach ($e->getErrors() as $error) {
        echo $error . "\n"; // Print the error message.
    };

    echo "\n"; // Separate examples.
}

/**
 * Example 2 for invalid company user ID.  OperationException is thrown.
 * Uses the 'wrong_user_id' profile in the .ini file.
 */
try {

    $client = new IntacctClient([
        'profile_name' => 'wrong_user_password',
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
    ]);

} catch (OperationException $e) {
    // print_r($e);  // Optionally see everything thrown.

    // Exceptions are printed for demonstration purposes only -- more error handling is needed in
    // production code.
    echo "Example 2, " . $e->getMessage(). "\n";

    foreach ($e->getErrors() as $error) {
        echo $error . "\n"; // Print the error message.
    };

    echo "\n"; // Separate examples.
}

/**
 * Example 3 for missing required parameter for object creation.  InvalidArgumentException is thrown.
 * Uses the default profile in the .ini file.
 */
try {

    $client = new IntacctClient([
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
    ]);

    $customerCreate = new CustomerCreate();  // Attempting to create a CUSTOMER without a NAME.
    $content = new Content([$customerCreate]);  // Wrap FunctionInterface(s) in a Content instance.

    // Call the client instance to execute the Content.
    $response = $client->execute($content, false, '', false, []);

} catch (InvalidArgumentException $e) {

    // print_r($e);  // Optionally see everything thrown.

    // Exceptions are printed for demonstration purposes only -- more error handling is needed in
    // production code.
    echo "Example 3, " . $e->getMessage();

    echo "\n\n"; // Separate examples.
}

/**
 * Example 4 for no data returned in query.  ResultException is thrown.
 * Uses the default profile in the .ini file.
 */
try {

    $queryClient = new QueryClient([
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
    ]);

    $readByQuery = new ReadByQuery(); // Construct a ReadByQuery instance

    $readByQuery->setObjectName('APBIL'); // Typo on 'APBILL'

    $readByQuery->setQuery("TOTALENTERED > 100"); // Query for totals greater than 100.

    $records = $queryClient->executeQuery($readByQuery); // Run the query.

} catch (ResultException $e) {
    // print_r($e);  // Optionally see everything thrown.

    // Exceptions are printed for demonstration purposes only -- more error handling is needed in
    // production code.
    echo "Example 4, " . $e->getMessage(). "\n";

    foreach ($e->getErrors() as $error) {
        echo $error . "\n"; // Print the error message.
    };

    echo "\n"; // Separate examples.
}

/**
 * Example 5 for transactional errors.  ResultException is thrown.
 * Uses the default profile in the .ini file.
 */
try {

    $client = new IntacctClient([
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
    ]);

    $readByName = new ReadByName();
    $readByName->setObjectName('GLENTRIES'); // Typo on 'GLENTRY'

    $content = new Content([$readByName]);  // Wrap FunctionInterface(s) in a Content instance.

    // Call the client instance to execute the Content.
    $response = $client->execute($content, true, '', false, []); // Second param is true for a transaction

    // No error thrown yet. Was the transaction successful?
    $response->getOperation()->getResult()->ensureStatusSuccess();

} catch (ResultException $e) {
    // print_r($e);  // Optionally see everything thrown.

    // Exceptions are printed for demonstration purposes only -- more error handling is needed in
    // production code.
    echo "Example 5, " . $e->getMessage(). "\n";

    foreach ($e->getErrors() as $error) {
        echo $error . "\n"; // Print the error message.
    };

    echo "\n"; // Separate examples.
}