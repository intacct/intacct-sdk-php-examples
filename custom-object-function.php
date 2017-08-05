<?php
/**
 * Copyright 2017 Sage Intacct, Inc.
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

try {
    $logger->info('Executing create test object function to API');

    $create = new \Intacct\Example\TestObjectCreate();
    $create->setName('hello world');

    $response = $client->execute($create);
    $result = $response->getResult();

    $recordNo = intval($result->getData()[0]->{'id'});

    echo "Created record ID $recordNo" . PHP_EOL;

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
