<?php
/*
 * Scientia - Free Knowledge
 *
 * Copyright (C) 2013 - Andrew Kester
 *
 *    This file is part of Scientia.
 *
 *    Scientia is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    Scientia is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with Scientia.  If not, see <http://www.gnu.org/licenses/>.
 */

/*
 * PHP is horrible when it comes to special charecters.  Rather than deal
 * with its built in functions, we roll our own.
 */
class scientiaSpecialChars {
	/**
	 * The array of the chars and thier names.
	 * @var array
	 */
	private $chars = array(
			/* 
			 * This should ONLY contain the UTF-16 code, the \u is prepended.
			 * The name should be in all caps (for convention's sake) and will
			 * have C_ prepended (ex C_TRIBAR).  Replace spaces with an _
			 */
			'INVERT_EXLAIM' => '00A1',
			'CENT_SIGN' => '00A2',
			'POUND_SIGN' => '00A3',
			'CURRENCY_SIGN' => '00A4',
			'YEN SIGN' => '00A5',
			'BROKEN_BAR' => '00A6',
			'SECTION_SIGN' => '00A7',
			'DIAERESIS' => '00A8',
			'COPYRIGHT_SIGN' => '00A9',
			'FEMININE_ORDINAL_INDICATOR' => '00AA',
			'LEFT_DOUBLE_ANGLE' => '00AB',
			'NOT_SIGN' => '00AC',
			'REGISTERED_SIGN' => '00AE',
			'MARCRON' => '00AF',
			'DEGREE_SIGN' => '00B0',
			'PLUS_MINUS_SIGN' => '00B1',
			'SUPERSCRIPT_TWO' => '00B2',
			'SUPERSCRIPT_THREE' => '00B3',
			'ACUTE_ACCENT' => '00B4',
			'MICRO_SIGN' => '00B5',
			'PILCROW_SIGN' => '00B6',
			'MIDDLE_DOT' => '00B7',
			'CEDILLA' => '00B8',
			'SUPERSCRIPT_ONE' => '00B9',
			'MASCULINE_ORDINAL_INDICATOR' => '00BA',
			'RIGHT_DOUBLE_ANGLE' => '00BB',
			'ONE_QUARTER' => '00BC',
			'ONE_HALF' => '00BD',
			'THREE_QUARTERS' => '00BE',
			'INVERT_QUESTION' => '00BF',
			'MULTIPLICATION_SIGN' => '00D7',
			'DIVISION_SIGN' => '00F7'
	);
	
	/**
	 * Return the current status of the chars.
	 * @return array
	 */
	public function getChars() {
		return $this->chars;
	}
	
	/**
	 * Add a char to the list.
	 * @param string $name The name that should be assigned.
	 * @param string $code The formatted char code.
	 * @param boolean $ow Allow overwriting of exisiting chars.
	 * @throws InvalidArgumentException
	 * @return boolean
	 */
	public function addChar($name, $code, $ow = false) {
		if (empty($name))
			throw new InvalidArgumentException('Char Name empty');
		if (empty($code))
			throw new InvalidArgumentException('Char Code Empty');
		if ($ow == false && array_key_exists($this->chars, $name))
			throw new InvalidArgumentException('Char exists.');
		$this->chars[$name] = $code;
		return true;
	}
	
	/**
	 * Remove a special charecter from the list.
	 * @param string $name The char to remove.
	 * @throws InvalidArgumentException
	 * @return boolean
	 */
	public function rmChar($name) {
		if (empty($name))
			throw new InvalidArgumentException('Char empty.');
		unset ($this->chars[$name]);
		return true;
	}
	
	/**
	 * Where the magic happens, encodes a charecter into it's UTF-8 Equivalent.
	 * @param string $code The code of the charecter to encode.
	 * @return string
	 */
	private function encodeChar($code) {
		$utf16 = '\u' . $code;
		return json_decode('"' . $utf16 . '"');
	}
	
	/**
	 * Store the configured chars into defined vars.
	 */
	public function storeChars() {
		foreach ($this->chars as $name => $code) {
			$globalName = 'C_' . $name;
			if (!defined($globalName))
				define($globalName, $this->encodeChar($code));
		}
		return true;
	}
}
?>