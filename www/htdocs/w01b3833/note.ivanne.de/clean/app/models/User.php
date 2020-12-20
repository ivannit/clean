<?php

class User {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($data) {
        $this->db->query('INSERT INTO users (username, email, password, since) '
                . 'VALUES (:username, :email, :password, CURDATE())');
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function login($email, $password) {
        $loggedIn = false;
        $this->db->query('SELECT userid, username, email, password, tempcode FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        if (password_verify($password, $row->password) && $row->tempcode == null) {
            $this->db->query('UPDATE users SET lastlogin = NOW() WHERE email = :email');
            $this->db->bind(':email', $email);
            if ($this->db->execute()) {
                $loggedIn = $row;
            }
        }
        return $loggedIn;
    }

    public function setTempCode($email) {
        $tempcode = $this->randomString();
        $this->db->query('UPDATE users SET tempcode = :tempcode, lastreset = NOW() WHERE email = :email');
        $this->db->bind(':tempcode', $tempcode);
        $this->db->bind(':email', $email);
        if ($this->db->execute()) {
            return $tempcode;
        } else {
            return '';
        }
    }

    public function resetPassword($hash, $email, $code) {
        $passwordReset = false;
        $this->db->query('SELECT tempcode, TIMESTAMPDIFF(MINUTE, lastreset, NOW()) as minutes FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        if ($row->tempcode == $code && $row->minutes < RESETTIME) {
            $this->db->query('UPDATE users SET password = :hash, tempcode = NULL, lastreset = NULL WHERE email = :email');
            $this->db->bind(':hash', $hash);
            $this->db->bind(':email', $email);
            if ($this->db->execute()) {
                $passwordReset = true;
            }
        }
        return $passwordReset;
    }

    public function randomString($length = 10) {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $chars_length = strlen($chars);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, $chars_length - 1)];
        }
        return $randomString;
    }

    public function userExists($email) {
        $this->db->query('SELECT 1 as one FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        if ($row->one == 1) {
            return true;
        } else {
            return false;
        }
    }

}
