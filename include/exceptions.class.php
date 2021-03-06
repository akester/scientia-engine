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
/**
 * Generic Exception Container.
 * @author andrew
 *
 */
class ScientiaGenericException extends Exception {
	/* Construct the parent exception. */
	public function __construct($message, $code = 0) {
		parent::__construct($message, $code);
	}
	
	/* Scientia Specific Exception Parameters */
}

/**
 * Thrown when a file is not found.
 * @author andrew
 *
 */
class ScientiaFileNotFound extends ScientiaGenericException {
	/* Construct the parent exception. */
	public function __construct($message, $code = 0) {
		parent::__construct($message, $code);
	}
	
	/* Take care of exception specific functions */
}

/**
 * Thrown on general database error
 * @author andrew
 *
 */
class ScientiaDataBaseError extends ScientiaGenericException {
	public function __construct($message, $code = 0) {
		parent::__construct($message, $code);
	}
	
	/* Take care of exception specific functions */
}
?>