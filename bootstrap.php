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

$loader = require __DIR__ . '/vendor/autoload.php';

use Intacct\OnlineClient;
use Intacct\ClientConfig;
use Monolog\Formatter\HtmlFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

try {
    $handler = new StreamHandler(__DIR__ . '/logs/intacct.html');

    $handler->setFormatter(new HtmlFormatter());

    $logger = new Logger('intacct-sdk-php-examples');
    $logger->pushHandler($handler);

    $clientConfig = new ClientConfig();
    $clientConfig->setProfileFile(__DIR__ . '/.credentials.ini');
    $clientConfig->setLogger($logger);

    $client = new OnlineClient($clientConfig);
} catch (Exception $ex) {
        $logger->error('An exception was thrown', [
            get_class($ex) => $ex->getMessage(),
        ]);
        echo get_class($ex) . ': ' . $ex->getMessage();
}