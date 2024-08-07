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

class TextFormat extends \MetFormProVendor\Google\Model
{
    public $bold;
    public $fontFamily;
    public $fontSize;
    protected $foregroundColorType = Color::class;
    protected $foregroundColorDataType = '';
    protected $foregroundColorStyleType = ColorStyle::class;
    protected $foregroundColorStyleDataType = '';
    public $italic;
    protected $linkType = Link::class;
    protected $linkDataType = '';
    public $strikethrough;
    public $underline;
    public function setBold($bold)
    {
        $this->bold = $bold;
    }
    public function getBold()
    {
        return $this->bold;
    }
    public function setFontFamily($fontFamily)
    {
        $this->fontFamily = $fontFamily;
    }
    public function getFontFamily()
    {
        return $this->fontFamily;
    }
    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
    }
    public function getFontSize()
    {
        return $this->fontSize;
    }
    /**
     * @param Color
     */
    public function setForegroundColor(Color $foregroundColor)
    {
        $this->foregroundColor = $foregroundColor;
    }
    /**
     * @return Color
     */
    public function getForegroundColor()
    {
        return $this->foregroundColor;
    }
    /**
     * @param ColorStyle
     */
    public function setForegroundColorStyle(ColorStyle $foregroundColorStyle)
    {
        $this->foregroundColorStyle = $foregroundColorStyle;
    }
    /**
     * @return ColorStyle
     */
    public function getForegroundColorStyle()
    {
        return $this->foregroundColorStyle;
    }
    public function setItalic($italic)
    {
        $this->italic = $italic;
    }
    public function getItalic()
    {
        return $this->italic;
    }
    /**
     * @param Link
     */
    public function setLink(Link $link)
    {
        $this->link = $link;
    }
    /**
     * @return Link
     */
    public function getLink()
    {
        return $this->link;
    }
    public function setStrikethrough($strikethrough)
    {
        $this->strikethrough = $strikethrough;
    }
    public function getStrikethrough()
    {
        return $this->strikethrough;
    }
    public function setUnderline($underline)
    {
        $this->underline = $underline;
    }
    public function getUnderline()
    {
        return $this->underline;
    }
}
// Adding a class alias for backwards compatibility with the previous class name.
\class_alias(TextFormat::class, 'MetFormProVendor\\Google_Service_Sheets_TextFormat');
