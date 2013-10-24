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
 * This library is a rehash of the php-digest authentication framework I
 * wrote some years ago.  The rehash is designed to remove the digest portion
 * of the framework and implement things in a bit nicer of format.
 */
/**
 * Library to take care of authentication
 * @author andrew
 *
 */
class scientiaAuth {
	private $realm = 'Scientia API';
	private $db;
	
	/* Table Config */
	private $authTable = 'users';
	
	/**
	 * Check to see if a user is blacklisted (status != 0)
	 * @param string $username
	 * @return boolean
	 */
	private function checkLogStatus($username) {
		$users = $this->db->query("SELECT `status` FROM $this->authTable WHERE
				`username` = '$username'");
		if ($users->num_rows != 1)
			/* We didn't get a user back, or we got multiple. */
			return false;
		$row = $users->fetch_assoc();
		if ($row['status'] != 0)
			return false;
		else
			return true;
	}
	
	/**
	 * Fetch the hash from a database.
	 * @param string $username
	 * @return boolean|unknown hash if exists, false otherwise
	 */
	private function getHash($username) {
		$hash = $this->db->query("SELECT `password` FROM $this->authTable WHERE
				`username` = '$username'");
		if ($hash->num_rows != 1)
			/* We didn't get a user back, or we got multiple. */
			return false;
		$row = $hash->fetch_assoc();
		return $row['password'];
	}
	
	/**
	 * Ensure a hash is correct
	 * @param string $stored
	 * @param string $password
	 */
	private function validateHash($stored, $password) {
		$h = new PasswordHash(8, FALSE);
		return $h->CheckPassword($password, $stored);
	}
	
	/**
	 * Prompt the user for authentication information.
	 */
	private function prompt() {
		header("WWW-Authenticate: Basic realm=\"{$this->realm}\"");
		header('HTTP/1.0 401 Unauthorized');
		exit();
	}
	
	/**
	 * Authenticate a user.
	 * @return string
	 */
	public function auth() {
		$this->db = new scientiaDB();
		/* Check that we have passed login info */
		if (empty($_SERVER['PHP_AUTH_USER']))
			$this->prompt();
		/* Check that the account is enabled */
		if (!$this->checkLogStatus($_SERVER['PHP_AUTH_USER']))
			$this->prompt();
		/* Check that the user exists in the database */
		$hash = $this->getHash($_SERVER['PHP_AUTH_USER']);
		if (!$hash)
			$this->prompt();
		/* Validate the hash */
		if (!$this->validateHash($hash, $_SERVER['PHP_AUTH_PW']))
			$this->prompt();
		
		/* All is well, allow the page to load */
		$this->db->close();
		
		return $_SERVER['PHP_AUTH_USER'];
	}
	
	/**
	 * Generate a hash for a password.
	 * @param string $password
	 */
	public function genHash($password) {
		$h = new PasswordHash(8, FALSE);
		return $h->HashPassword($password);
	}
}
?>