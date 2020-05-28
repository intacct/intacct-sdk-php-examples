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

namespace Intacct\Example;

use Intacct\Xml\XMLWriter;
use InvalidArgumentException;

class TestObjectCreate extends AbstractTestObject
{

    /**
     * @param XMLWriter $xml
     */
    public function writeXml(XMLWriter &$xml)
    {
        $xml->startElement('function');
        $xml->writeAttribute('controlid', $this->getControlId());

        $xml->startElement('create');
        $xml->startElement(self::INTEGRATION_NAME); // Integration name in the system.

        if (!$this->getName()) {
            throw new InvalidArgumentException('Name field is required for create');
        }

        $xml->writeElement('name', $this->getName(), true);

        $xml->endElement(); // test_object
        $xml->endElement(); // create
        $xml->endElement(); // function
    }
}
