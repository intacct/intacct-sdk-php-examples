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
 *  This example shows how to:
 *  1. Create a logger and handler using Monolog (third-party logger).
 *  2. Pass that logger to an IntacctClient instance.
 *  3. Execute a request that will throw some errors.
 *  4. Check transaction success, catch errors, and log them.
 *
 *  Prerequisites:
 *  - You installed the PHP SDK: https://github.com/Intacct/intacct.github.io/tools/php-sdk-tutorial/
 *  - You installed the Monolog logger: https://seldaek.github.io/monolog/
 *  - You set up a default profile in intacct-sdk-php-examples/.intacct/credentials.ini.
 *
 *  See https://github.com/Intacct/intacct.github.io/tools/php-sdk/logging-example/
 *  for detailed instructions on meeting the prerequisites and running this example.
 *
 */

// Load the dependencies for the SDK from the Composer vendor directory.
$loader = require __DIR__ . '\vendor\autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Intacct\Content;
use Intacct\IntacctClient;
use Intacct\Exception\ResultException;
use Intacct\Functions\Common\ReadByName;

/**
 * Create your handler and logger.
 */
$log_path = fopen(__DIR__ . '\intacct.html', 'a+'); // Create log file if it doesn't exist.
$log_handle = new StreamHandler($log_path);         // Pass the log file to the stream handler.
$log_handle->setFormatter(new Monolog\Formatter\HtmlFormatter()); // Use an HTML formatter.

$logger = new Logger('intacct-sdk-php-examples'); // Name the logger.

$logger->pushHandler($log_handle); // Pass the stream handler to the logger.

/**
 * Pass the logger and your credentials to the IntacctClient instance.
 */
try {
    // A template credential.ini is in intacct-sdk-php-examples. Update the default profile
    // with your information and put the file in a .intacct directory.

    $client = new IntacctClient([
        'profile_file' => __DIR__ . '\.intacct\credentials.ini', // Read credentials from file.
        'logger' => $logger                                      // Pass in the logger.
    ]);

    // Intacct function call.
    $readByName = new ReadByName();
    $readByName->setObjectName('GLENTRIES'); // Use incorrect name (should be GLENTRY).

    $content = new Content([$readByName]);  // Wrap function calls in a Content instance.

    // Call the client instance to execute the content.
    $response = $client->execute($content, true, '', false, []); // Set this as a transaction (2nd param is true)

    // Check whether the transaction was successful
    $response->getOperation()->getResult()->ensureStatusSuccess();

} catch (ResultException $e) {
    foreach ($e->getErrors() as $error) {
        $logger->error($error . "\n"); // Log the error messages.
    };
}

fclose($log_path);