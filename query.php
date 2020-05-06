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

use Intacct\Functions\Common\Query;
use Intacct\Functions\Common\QuerySelect\SelectBuilder;

try {

   /* $batchnoAndState = new AndOperator([ ( new Filter('BATCHNO') )->greaterthanorequalto('1'),
                                         ( new Filter('STATE') )->equalto('Posted') ]);

    $journal = ( new Filter('JOURNAL') )->equalto('APJ');

    $filter = new OrOperator([ $journal, $batchnoAndState ]);

    $fields = ( new SelectBuilder() )->fields([ 'STATE' ])
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
*/
    $fields = ( new SelectBuilder() )->fields([ 'CUSTOMERID' ])
                                     ->count('RECORDNO')
                                     ->getFields();

    $res = ( new Query('unittest') )->select($fields)
                                    ->from('ARINVOICE')
                                    ->offset('1')
                                    ->pagesize('100');

    $logger->info('Executing query to Intacct API');
    $response = $client->execute($res);
    $result = $response->getResult();

    $json_data = json_decode(json_encode($result->getData()), 1);

    if ( $json_data && is_array($json_data) && sizeof($json_data >= 1)) {
        echo "Success! Total number of ARINVOICE objects:" . $result->getTotalCount();
        echo "\n\n";
        echo "Example ARINVOICE result: \n";
        foreach ( $json_data[0] as $key => $value ) {
            echo "    '$key' => '$value'\n";
        }
        echo "" . PHP_EOL;
    }

    $logger->debug('Query successful', [
        'Company ID' => $response->getAuthentication()->getCompanyId(),
        'User ID' => $response->getAuthentication()->getUserId(),
        'Request control ID' => $response->getControl()->getControlId(),
        'Function control ID' => $result->getControlId(),
        'Total count' => $result->getTotalCount(),
        'Data' => $json_data,
    ]);

} catch (\Exception $ex) {
    $logger->error('An exception was thrown', [
        get_class($ex) => $ex->getMessage(),
    ]);
    echo get_class($ex) . ': ' . $ex->getMessage();
}
