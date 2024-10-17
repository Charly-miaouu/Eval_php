<?php

namespace App\Entity;

class User {
    private $id;
    private $username;
    private $mail;
    private $password;
    private $media_object;
    private $created_at;
    private $last_connection;
    private $role_admin;

    public function __construct($username, $mail, $password, $media_object,  $id = null, $created_at = null, $last_connection = null, $role_admin = null) {
        $this->id = $id;
        $this->username = $username;
        $this->mail = $mail;
        $this->password = $password;
        $this->media_object = $media_object;
        $this->created_at = $created_at;
        $this->last_connection = $last_connection;
        $this->role_admin = $role_admin;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() : string {
        return $this->username;
    }

    public function getMail() : string {
        return $this->mail;
    }

    public function getPassword() : string {
        return $this->password;
    }

    public function getMediaObject() : string {
        return $this->media_object;
    }

    public function getCreatedAt() : ?string {
        return $this->created_at;
    }

    public function getLastConnection() : ?string {
        return $this->last_connection;
    }

    public function getRoleAdmin() : ?bool {
        return $this->role_admin;
    }
}
