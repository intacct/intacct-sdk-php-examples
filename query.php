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

use Intacct\Functions\Common\Query;
use Intacct\Functions\Common\QueryFilter\AndOperator;
use Intacct\Functions\Common\QueryFilter\Filter;
use Intacct\Functions\Common\QueryFilter\OrOperator;
use Intacct\Functions\Common\QueryOrderBy\OrderBuilder;
use Intacct\Functions\Common\QuerySelect\SelectBuilder;
use Intacct\Xml\XMLWriter;

try {
    $xml = new XMLWriter();
    $xml->openMemory();
    $xml->setIndent(true);
    $xml->setIndentString('    ');
    $xml->startDocument();

    $batchnoAndState = new AndOperator([ ( new Filter('BATCHNO') )->greaterthanorequalto('1'),
                                         ( new Filter('STATE') )->equalto('Posted') ]);

    $journal = ( new Filter('JOURNAL') )->equalto('APJ');

    $filter = new OrOperator([ $journal, $batchnoAndState ]);

    $fields = ( new SelectBuilder() )->fields([ 'BATCHNO', 'STATE' ])
                                     ->count('RECORDNO')
                                     ->getFields();

    $order = ( new OrderBuilder())->descending('BATCHNO')->getOrders();

    $res = ( new Query('unittest') )->select($fields)
                                      ->from('GLBATCH')
                                      ->filter($filter)
                                      ->caseInsensitive(true)
                                      ->offset('1')
                                      ->pagesize('100')
                                      ->orderBy($order);

    $logger->info('Executing query to Intacct API');
    $response = $client->execute($res);
    $result = $response->getResult();

    $logger->debug('Query successful', [
        'Company ID' => $response->getAuthentication()->getCompanyId(),
        'User ID' => $response->getAuthentication()->getUserId(),
        'Request control ID' => $response->getControl()->getControlId(),
        'Function control ID' => $result->getControlId(),
        'Total count' => $result->getTotalCount(),
        'Data' => json_decode(json_encode($result->getData()), 1),
    ]);

    echo "Success! Number of GLBATCH objects found: " . $result->getTotalCount() . PHP_EOL;

} catch (\Exception $ex) {
    $logger->error('An exception was thrown', [
        get_class($ex) => $ex->getMessage(),
    ]);
    echo get_class($ex) . ': ' . $ex->getMessage();
}
