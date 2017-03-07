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
 *  1. Open a session by creating an IntacctClient and providing login credentials
 *     loaded from a file.
 *  3. Configure a Read call on VENDOR objects and wrap this in a Content object.
 *  4. Use the IntacctClient instance to execute a request with the Content.
 *  5. Get a count of VENDOR objects from the returned result.
 *
 *  Prerequisites:
 *  - You have a working knowledge of PHP.
 *  - You meet the system requirements for the Intacct SDK for PHP.
 *  - You have a PHP IDE as well as the Composer PHP dependency manager.
 *  - You have installed the PHP SDK and its dependencies using Composer.
 *
 *  See https://github.com/Intacct/intacct.github.io/tools/php-sdk/getting-started/
 *  for detailed instructions on meeting the prerequisites and running this example.
 */

// Load the dependencies for the SDK from the Composer vendor directory.
// See the 'Getting Started' tutorial if you have not yet installed the SDK dependencies.
$loader = require __DIR__ . '\vendor\autoload.php';

use Intacct\Functions\Common\Read;
use Intacct\IntacctClient;
use Intacct\Content;
use Intacct\Exception\ResultException;
use Intacct\Exception\ResponseException;

// Wrap your calls in a try block to support error handling.
try {

    // Load the login credentials from a local config file. A template login.cfg is provided in
    // intacct-sdk-php-examples -- update with your information.
    $ini_array = parse_ini_file("login.cfg"); // Store this file in a secure location or use a
                                              // secure database or other secure methodology.

    // Create an IntacctClient instance to establish a session with your company.
    $client = new IntacctClient([
        'sender_id' => $ini_array['MY_INTACCT_SENDER_ID'],
        'sender_password' => $ini_array['MY_INTACCT_SENDER_PASSWORD'],
        'company_id' => $ini_array['MY_INTACCT_COMPANY'],
        'user_id' => $ini_array['MY_INTACCT_USER'],
        'user_password' => $ini_array['MY_INTACCT_USER_PASSWORD'],
    ]);

    $read = new Read('UniqueControlIdHere');  // A unique ID (GUID, timestamp, etc.) is recommended for recovery purposes.
    $read->setObjectName('VENDOR');           // Read all VENDOR objects.
    $content = new Content([$read]);          // Wrap function calls in a Content instance.

    // Call the client instance to execute the content.
    $response = $client->execute($content, false, '', false, []); // A GUID will be created automatically for
                                                                  // the empty request controlId (third parameter).
                                                                  // Useful for error recovery when modifying data.
    echo "\nRead function control ID: " . $response->getControl()->getControlId() . "\n";
    // Print the number of VENDOR objects.
    echo "Number of vendor objects: " . $response->getOperation()->getResult()->getCount() . "\n";

  // Exceptions are printed for demonstration purposes only -- more error handling is needed in
  // production code.
} catch (ResultException $e) {
    print_r($e);
} catch (ResponseException $e) {
    print_r($e);
} catch (\Exception $e) {
    echo get_class($e) . ' ' . $e->getMessage();
}