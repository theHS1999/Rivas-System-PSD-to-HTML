<?php
/** * login.php * login class file * * @copyright    Copyright (C) 2012 Rivas Systems Inc. All rights reserved. * @versin    1.00 2012-07-23 * @author    Behnam Salili * */
require_once dirname(dirname(__file__)) . '/database/database.php';

class Login
{
    private $username;
    private $password;

    public function __construct($usrnm, $passwd)
    {
        $this->username = $usrnm;
        $this->password = $passwd;
    }

    public function login_user()
    {
        $res = Database::execute_query("SELECT `id`, `name`, `last-name`, `username`, `access-level`, `access-level-id`, `center-author-ids`, `hygiene-unit-author-ids` FROM `users` WHERE `username` = '" . Database::filter_str($this->username) . "' AND `password` = '" . $this->hashIt($this->password) . "';");
        if (Database::num_of_rows($res) > 0) {
            $row = Database::get_assoc_array($res);
            switch ($row['access-level']) {
                case 'State Manager' :
                    return array("userId" => $row['id'], "name" => $row['name'], "lastname" => $row['last-name'], "username" => $row['username'], "acl" => $row['access-level'], "acl-id" => $row['access-level-id'], "centerAuthorIds" => $row['center-author-ids'], "hygieneUnitAuthorIds" => $row['hygiene-unit-author-ids']);
                    break;
                case 'Town Manager' :
                    try {
                        $res1 = Database::execute_query("SELECT * FROM `town-manager-acl` WHERE `user-id` = '" . $row['id'] . "';");
                        $row1 = Database::get_assoc_array($res1);
                        return array("userId" => $row['id'], "name" => $row['name'], "lastname" => $row['last-name'], "username" => $row['username'], "acl" => $row['access-level'], "acl-id" => $row['access-level-id'], "acl-details" => $row1, "centerAuthorIds" => $row['center-author-ids'], "hygieneUnitAuthorIds" => $row['hygiene-unit-author-ids']);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'Center Manager' :
                    try {
                        $res1 = Database::execute_query("SELECT * FROM `center-manager-acl` WHERE `user-id` = '" . $row['id'] . "';");
                        $row1 = Database::get_assoc_array($res1);
                        return array("userId" => $row['id'], "name" => $row['name'], "lastname" => $row['last-name'], "username" => $row['username'], "acl" => $row['access-level'], "acl-id" => $row['access-level-id'], "acl-details" => $row1, "centerAuthorIds" => $row['center-author-ids'], "hygieneUnitAuthorIds" => $row['hygiene-unit-author-ids']);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'Unit Manager' :
                    try {
                        $res1 = Database::execute_query("SELECT * FROM `unit-manager-acl` WHERE `user-id` = '" . $row['id'] . "';");
                        $row1 = Database::get_assoc_array($res1);
                        return array("userId" => $row['id'], "name" => $row['name'], "lastname" => $row['last-name'], "username" => $row['username'], "acl" => $row['access-level'], "acl-id" => $row['access-level-id'], "acl-details" => $row1, "centerAuthorIds" => $row['center-author-ids'], "hygieneUnitAuthorIds" => $row['hygiene-unit-author-ids']);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                case 'Hygiene Unit Manager' :
                    try {
                        $res1 = Database::execute_query("SELECT * FROM `hygiene-unit-manager-acl` WHERE `user-id` = '" . $row['id'] . "';");
                        $row1 = Database::get_assoc_array($res1);
                        return array("userId" => $row['id'], "name" => $row['name'], "lastname" => $row['last-name'], "username" => $row['username'], "acl" => $row['access-level'], "acl-id" => $row['access-level-id'], "acl-details" => $row1, "centerAuthorIds" => $row['center-author-ids'], "hygieneUnitAuthorIds" => $row['hygiene-unit-author-ids']);
                    } catch (Exception $e) {
                        return array('err_msg' => $e->getMessage());
                    }
                    break;
                default:
                    return array("userId" => $row['id'], "name" => $row['name'], "lastname" => $row['last-name'], "username" => $row['username'], "acl" => $row['access-level'], "acl-id" => $row['access-level-id'], "centerAuthorIds" => $row['center-author-ids'], "hygieneUnitAuthorIds" => $row['hygiene-unit-author-ids']);
                    break;
            }
        } else return 'false';
    }

    private function hashIt($inp)
    {
        return hash_hmac('sha512', $inp, $this->username);
    }

    public function hashItTwo($inp)
    {
        return hash_hmac('sha512', $inp, $this->username);
    }
}

?>
