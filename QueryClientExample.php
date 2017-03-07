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
 *  This example shows how to:
 *  1. Open a session by creating a query client that reads your Web Services and company credentials
 *     from a local credentials file.
 *  2. Create a query that operates on APBILL objects.
 *  3. Run the query then parse and print the results.
 *
 *  See https://github.com/Intacct/intacct.github.io/tools/php-sdk/query-example/
 *  for detailed instructions on running this example.
 */

// Load the dependencies for the SDK from the Composer vendor directory.
$loader = require __DIR__ . '\vendor\autoload.php';

use Intacct\Functions\Common\ReadByQuery;
use Intacct\QueryClient;

// Create a QueryClient instance to establish a session with your company and perform a query.
try {
    // Read login credentials from a local ini file (you can use a remote file if you prefer).
    // A template credential.ini is provided in intacct-sdk-php-examples -- update *only* the
    // default profile with your information and put the file in a .intacct directory that you create.
    $queryClient = new QueryClient([
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
    ]);

    $readByQuery = new ReadByQuery();             // Construct a ReadByQuery instance --
    $readByQuery->setObjectName('APBILL');        // on APBILL objects.
    $readByQuery->setQuery("TOTALENTERED > 100"); // Query for totals greater than 100.


    $records = $queryClient->executeQuery($readByQuery); // Run the query.

    echo "Number of APBILL objects: " . $records->count() . "\n\n";  // Print the number of records.

    foreach ($records as $record) {                                                          // For each record in the array show:
        echo "Record Created: " . $record['WHENCREATED'] . "\n";                             // Creation date
        echo "Amount posted: " . $record['TOTALENTERED'] . " " . $record['CURRENCY'] . "\n"; // Total amount and currency type
        echo "Name: " . $record['VENDORNAME'] . "\n\n";                                      // VENDOR name
    }

// Exceptions are printed for demonstration purposes only -- more error handling is needed in production code.
} catch (\Intacct\Exception\ResultException $e) {
    print_r($e);
} catch (\Intacct\Exception\ResponseException $e) {
    print_r($e);
} catch (\Exception $e) {
    echo get_class($e) . ' ' . $e->getMessage();
}