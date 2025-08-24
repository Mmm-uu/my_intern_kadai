<?php

namespace Fuel\Migrations;
class Create_records_table
{
    public function up()
    {
        \DBUtil::create_table('records', array(
            'id' => array(
                'type' => 'int',
                'null' => false,
                'auto_increment' => true,
                'unsigned' => true
            ),
            'action_id' => array(
                'type' => 'int',
                'null' => false,
                'unsigned' => true
            ),
            'date' => array(
                'type' => 'datetime',
                'null' => false,
                'default' => \DB::expr('CURRENT_TIMESTAMP')
            ),
            'status' => array(
                'type' => 'tinyint',
                'null' => false,
                'unsigned' => true,
                'default' => 0
            ),
            'next_at' => array(
                'type' => 'datetime',
                'null' => false,
                'default' => \DB::expr('CURRENT_TIMESTAMP')
            )
        ), array('id'));
    }

    public function down()
    {
        \DBUtil::drop_table('records');
    }
}
?>
