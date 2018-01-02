<?php
/**
 * Copyright 2018 Sage Intacct, Inc.
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

use Intacct\Functions\AccountsReceivable\CustomerCreate;
use Intacct\Functions\AccountsReceivable\CustomerDelete;
use Intacct\Functions\AccountsReceivable\CustomerUpdate;
use Intacct\Functions\Common\Read;
use Intacct\Functions\Common\ReadByName;

try {
    $logger->info('Executing CRUD customer functions to API');

    $create = new CustomerCreate();
    $create->setCustomerName('Joshua Granley');
    $create->setActive(false);

    $response = $client->execute($create);
    $result = $response->getResult();

    $customerId = strval($result->getData()[0]->{'CUSTOMERID'});
    $recordNo = intval($result->getData()[0]->{'RECORDNO'});

    echo "Created inactive customer ID $customerId" . PHP_EOL;

    $update = new CustomerUpdate();
    $update->setCustomerId($customerId);
    $update->setActive(true);

    $response = $client->execute($update);

    echo "Updated customer ID $customerId to active" . PHP_EOL;

    $read = new Read();
    $read->setObjectName('CUSTOMER');
    $read->setFields([
        'RECORDNO',
        'CUSTOMERID',
        'STATUS',
    ]);
    $read->setKeys([
        $recordNo,
    ]);

    $response = $client->execute($read);

    echo "Read customer ID $customerId" . PHP_EOL;

    $delete = new CustomerDelete();
    $delete->setCustomerId($customerId);

    $response = $client->execute($delete);

    echo "Deleted customer ID $customerId" . PHP_EOL;

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
