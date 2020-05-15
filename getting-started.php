<?php

/**
 * Copyright 2020 Sage Intacct, Inc.
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

require __DIR__ . '/bootstrap.php';

use Intacct\Functions\Common\Read;

try {
    $read = new Read();
    $read->setObjectName('CUSTOMER');
    $read->setFields([
                         'CUSTOMERID',
                         'NAME',
                         'RECORDNO',
                         'STATUS',
                     ]);
    $read->setKeys([
                       33          // Replace with the record number of a customer in your company
                   ]);

    $logger->info('Executing query to Intacct API');
    $response = $client->execute($read);
    $result = $response->getResult();

    $logger->debug('Query successful', [
        'Company ID'          => $response->getAuthentication()
                                          ->getCompanyId(),
        'User ID'             => $response->getAuthentication()
                                          ->getUserId(),
        'Request control ID'  => $response->getControl()
                                          ->getControlId(),
        'Function control ID' => $result->getControlId(),
        'Total count'         => $result->getTotalCount(),
        'Data'                => json_decode(json_encode($result->getData()), 1),
    ]);

    echo "Result: ";
    echo sprintf("%s%s", json_encode($result->getData()), PHP_EOL);
} catch ( \Intacct\Exception\ResponseException $ex ) {
    $logger->error('An Intacct response exception was thrown', [
        get_class($ex) => $ex->getMessage(),
        'Errors'       => $ex->getErrors(),
    ]);
    echo 'Failed! ' . $ex->getMessage();
} catch ( \Exception $ex ) {
    $logger->error('An exception was thrown', [
        get_class($ex) => $ex->getMessage(),
    ]);
    echo get_class($ex) . ': ' . $ex->getMessage();
}