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
 * This advanced example shows how to do the following:
 * 1. Login to your company through QueryClient
 * 2. Execute a query on a APBILL and parse the results
 */

$loader = require __DIR__ . '\vendor\autoload.php';

use Intacct\Functions\Common\ReadByQuery;
use Intacct\QueryClient;

try {

    // Loading login credentials from local ini file, but you can get this file from somewhere else (not local).
    // Setup an QueryClient (which does a login)
    $queryClient = new QueryClient([
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
    ]);

    $readByQuery = new ReadByQuery();

    $readByQuery->setObjectName('APBILL');

    $readByQuery->setQuery("TOTALENTERED > 100"); //And let's query for total entered greater than 100

    $records = $queryClient->executeQuery($readByQuery); //Run that query

    echo "Number of APBILL objects: " . $records->count() . "\n\n";  //Print the number of records

    foreach ($records as $record) {
        echo "Record Created: " . $record['WHENCREATED'] . "\n"; //Just prints out when the record was created
        echo "Amount posted: " . $record['TOTALENTERED'] . " " . $record['CURRENCY'] . "\n"; // the total amount entered
        echo "Name: " . $record['VENDORNAME'] . "\n\n";            //and the VENDORNAME
    }

} catch (\Intacct\Exception\ResultException $e) {
    print_r($e); // Do more error handling here
} catch (\Intacct\Exception\ResponseException $e) {
    print_r($e); //Do more error handling here
} catch (\Exception $e) {
    echo get_class($e) . ' ' . $e->getMessage();  //Do more error handling here
}