<?php
class Model_Users extends \Model_Crud
{
    protected static $_table_name = 'users';

        public static function find_username($username)
        {
            $existing_user = \DB::select('id')
                            ->from(self::$_table_name)
                            ->where('username', '=', $username)
                            ->execute()
                            ->current();
            return $existing_user;
        }
}

?>