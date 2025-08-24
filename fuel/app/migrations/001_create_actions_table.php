<?php

namespace Fuel\Migrations;
class Create_actions_table
{
    public function up()
    {
        \DBUtil::create_table('actions', array(
            'id' => array(
                'type' => 'int',
                'null' => false,
                'auto_increment' => true,
                'unsigned' => true
            ),
            'name' => array(
                'constraint' => 10,
                'type' => 'varchar',
                'null' => false
            ),
            'frequency' => array(
                'type' => 'int',
                'null' => false,
                'unsigned' => true
            ),
            'color' => array(
                'constraint' => 7,
                'type' => 'char',
                'null' => false
            ),
            'created_at' => array(
                'type' => 'datetime',
                'null' => false,
                'default' => \DB::expr('CURRENT_TIMESTAMP')
            ),
            'deleted' => array(
                'type' => 'tinyint',
                'null' => false,
                'unsigned' => true,
                'default' => 0
            )
        ), array('id'));
    }

    public function down()
    {
        \DBUtil::drop_table('actions');
    }
}
?>
