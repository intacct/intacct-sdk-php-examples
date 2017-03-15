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
 *  This example is composed of several files:
 *  1. The program that sends a request that creates a custom object in the Intacct system (this file).
 *  2. An abstract class for a custom object (AbstractMyCustomObject).
 *  3. A concrete class that extends the abstract class and implements the writeXml() function (MyCustomObjectCreate).
 */

$loader = require __DIR__ . '\vendor\autoload.php';

use Intacct\IntacctClient;
use Intacct\Content;
use Intacct\Exception\ResultException;
use Intacct\Exception\ResponseException;
use CustomObjects\MyCustomObjectCreate;

try {

    $client = new IntacctClient([
        'profile_file' => __DIR__ . '\.intacct\credentials.ini'
    ]);
    
    // Create custom object.
    $customObject = new MyCustomObjectCreate();
    $customObject->setName("Test name");
    $customObject->setDescription("Test description");

    $content = new Content([
        $customObject,
    ]);  
    
    $response = $client->execute($content);
    $results = $response->getOperation()->getResults();
    
    foreach ($results as $data) {
            var_dump($data);
    }

} catch (ResultException $e) {
    print_r($e);
} catch (ResponseException $e) {
    print_r($e);
} catch (\Exception $e) {
    echo get_class($e) . ' ' . $e->getMessage();
}

