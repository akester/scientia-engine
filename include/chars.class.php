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
			'TRIBAR' => '2261',
			'ARROW' => '2192',
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