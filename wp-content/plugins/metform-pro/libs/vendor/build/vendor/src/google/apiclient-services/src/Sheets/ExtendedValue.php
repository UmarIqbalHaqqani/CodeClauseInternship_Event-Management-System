<?php

/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */
namespace MetFormProVendor\Google\Service\Sheets;

class ExtendedValue extends \MetFormProVendor\Google\Model
{
    public $boolValue;
    protected $errorValueType = ErrorValue::class;
    protected $errorValueDataType = '';
    public $formulaValue;
    public $numberValue;
    public $stringValue;
    public function setBoolValue($boolValue)
    {
        $this->boolValue = $boolValue;
    }
    public function getBoolValue()
    {
        return $this->boolValue;
    }
    /**
     * @param ErrorValue
     */
    public function setErrorValue(ErrorValue $errorValue)
    {
        $this->errorValue = $errorValue;
    }
    /**
     * @return ErrorValue
     */
    public function getErrorValue()
    {
        return $this->errorValue;
    }
    public function setFormulaValue($formulaValue)
    {
        $this->formulaValue = $formulaValue;
    }
    public function getFormulaValue()
    {
        return $this->formulaValue;
    }
    public function setNumberValue($numberValue)
    {
        $this->numberValue = $numberValue;
    }
    public function getNumberValue()
    {
        return $this->numberValue;
    }
    public function setStringValue($stringValue)
    {
        $this->stringValue = $stringValue;
    }
    public function getStringValue()
    {
        return $this->stringValue;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(ExtendedValue::class, 'MetFormProVendor\\Google_Service_Sheets_ExtendedValue');
