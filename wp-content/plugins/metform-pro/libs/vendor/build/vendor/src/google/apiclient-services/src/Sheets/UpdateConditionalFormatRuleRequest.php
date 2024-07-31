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

class UpdateConditionalFormatRuleRequest extends \MetFormProVendor\Google\Model
{
    public $index;
    public $newIndex;
    protected $ruleType = ConditionalFormatRule::class;
    protected $ruleDataType = '';
    public $sheetId;
    public function setIndex($index)
    {
        $this->index = $index;
    }
    public function getIndex()
    {
        return $this->index;
    }
    public function setNewIndex($newIndex)
    {
        $this->newIndex = $newIndex;
    }
    public function getNewIndex()
    {
        return $this->newIndex;
    }
    /**
     * @param ConditionalFormatRule
     */
    public function setRule(ConditionalFormatRule $rule)
    {
        $this->rule = $rule;
    }
    /**
     * @return ConditionalFormatRule
     */
    public function getRule()
    {
        return $this->rule;
    }
    public function setSheetId($sheetId)
    {
        $this->sheetId = $sheetId;
    }
    public function getSheetId()
    {
        return $this->sheetId;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(UpdateConditionalFormatRuleRequest::class, 'MetFormProVendor\\Google_Service_Sheets_UpdateConditionalFormatRuleRequest');
