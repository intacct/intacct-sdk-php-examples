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

namespace CustomObjects;

use Intacct\Xml\XMLWriter;
use InvalidArgumentException;


class MyCustomObjectCreate extends AbstractMyCustomObject
{
    /**
     * Write the function block XML
     *
     * @param XMLWriter $xml
     * @throw InvalidArgumentException
     */
    public function writeXml(XMLWriter &$xml)
    {
        $xml->startElement('function');
        $xml->writeAttribute('controlid', $this->getControlId());

        $xml->startElement('create');
        $xml->startElement('test_object'); // Integration name in the Intacct system.

        if (!$this->getName()) {
            throw new InvalidArgumentException('Custom name is required for create');
        }

        $xml->writeElement('name', $this->getName(), true);
        $xml->writeElement('description', $this->getDescription(), true);

        $xml->endElement(); // test_object
        $xml->endElement(); // create
        $xml->endElement(); // function
    }
}