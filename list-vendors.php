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

use Intacct\Functions\Common\Query\Comparison\EqualTo\EqualToString;
use Intacct\Functions\Common\ReadByQuery;
use Intacct\Functions\Common\ReadMore;

try {
    $query = new ReadByQuery();
    $query->setObjectName('VENDOR');
    $query->setPageSize(2); // Keep the page size to just 2 for the example
    $query->setFields([
        'RECORDNO',
        'VENDORID',
    ]);

    $logger->info('Executing query to Intacct API');
    $response = $client->execute($query);
    $result = $response->getResult();

    $logger->debug('Query successful - page 1', [
        'Total count' => $result->getTotalCount(),
        'Data' => json_decode(json_encode($result->getData()), 1),
    ]);

    echo "Page 1 success! Number of vendor records found: " . $result->getTotalCount() . ". Number remaining: " . $result->getNumRemaining() . PHP_EOL;

    $i = 1;
    // Get pages 2 through 4
    while ($result->getNumRemaining() > 0 && $i <= 3 && $result->getResultId()) {
        $i++;
        $more = new ReadMore();
        $more->setResultId($result->getResultId());

        $response = $client->execute($more);
        $result = $response->getResult();

        $logger->debug('Read More successful - page ' . $i, [
            'Total remaining' => $result->getNumRemaining(),
            'Data' => json_decode(json_encode($result->getData()), 1),
        ]);
        echo "Page $i success! Records remaining: " . $result->getNumRemaining() . PHP_EOL;
    }

    echo "Successfully read $i pages" . PHP_EOL;

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
