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
 *  This example demonstrates the following:
 *  1. Create a logger and handler (in this case for a file stream)
 *  2. Pass logger to IntacctClient
 *  3. Execute your request (logging will be performed)
 *  4. Check transaction success, catch errors and log them
 */

$loader = require __DIR__ . '\vendor\autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Intacct\Content;
use Intacct\IntacctClient;
use Intacct\Exception\ResultException;
use Intacct\Functions\Common\ReadByName;

/**
 * Create your handler and logger
 *
 */
$log_path = fopen(__DIR__ . '\intacct.log', 'a+');
$log_handle = new StreamHandler($log_path);

$logger = new Logger('intacct-sdk-php-examples');

$logger->pushHandler($log_handle);

/**
 * Give your logger to the IntacctClient
 */
try {

    $client = new IntacctClient([
        'profile_file' => __DIR__ . '\.intacct\credentials.ini',
        'logger' => $logger,
    ]);

    // Create your function to be executed
    $readByName = new ReadByName();
    $readByName->setObjectName('GLENTRIES');

    $content = new Content([$readByName]);  // Wrap function calls in a Content instance.

    // Call the client instance to execute the Content.
    $response = $client->execute($content, true, '', false, []); //We'll just set this as a true transaction

    // No error thrown yet...so let's find out if the transaction was successful
    $response->getOperation()->getResult()->ensureStatusSuccess();

} catch (ResultException $e) {
    foreach ($e->getErrors() as $error) {
        $logger->error($error . "\n"); //Just simply logs the error message.
    };
}

fclose($log_path);