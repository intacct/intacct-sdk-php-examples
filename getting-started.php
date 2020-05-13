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

use Intacct\Functions\Common\ReadByQuery;

try {
    $query = new ReadByQuery();
    $query->setObjectName('VENDOR');
    $query->setPageSize(1); // Keep the count to just 1 for the example
    $query->setFields([
        'RECORDNO',
        'VENDORID',
        'NAME',
    ]);

    $logger->info('Executing query to Intacct API');
    $response = $client->execute($query);
    $result = $response->getResult();

    $logger->debug('Query successful', [
        'Company ID' => $response->getAuthentication()->getCompanyId(),
        'User ID' => $response->getAuthentication()->getUserId(),
        'Request control ID' => $response->getControl()->getControlId(),
        'Function control ID' => $result->getControlId(),
        'Total count' => $result->getTotalCount(),
        'Data' => json_decode(json_encode($result->getData()), 1),
    ]);

    echo "Success! Number of vendor objects found: " . $result->getTotalCount() . PHP_EOL;

} catch (\Intacct\Exception\ResponseException $ex) {
    $logger->error('An Intacct response exception was thrown', [
        get_class($ex) => $ex->getMessage(),
        'Errors' => $ex->getErrors(),
    ]);
    echo 'Failed! ' . $ex->getMessage();
} catch (\Exception $ex) {
    $logger->error('An exception was thrown', [
        get_class($ex) => $ex->getMessage(),
    ]);
    echo get_class($ex) . ': ' . $ex->getMessage();
}
