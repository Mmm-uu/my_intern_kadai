<?php

namespace Fuel\Migrations;
class Create_display_table
{
    public function up()
    {
        \DBUtil::create_table('display', array(
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
            'start_at' => array(
                'type' => 'datetime',
                'nill' => false,
                'default' => \DB::expr('CURRENT_TIMESTAMP')
            ),
            'current_streak' => array(
                'type' => 'int',
                'null' => false,
                'unsigned' => true,
                'default'  => 1
            ),
            'last_completed_at' => array(
                'type' => 'datetime',
                'null' => false,
                'default' => \DB::expr('CURRENT_TIMESTAMP')
            ),
            'next_target_at' => array(
                'type' => 'datetime',
                'null' => false,
            )
        ), array('id'));
    }

    public function down()
    {
        \DBUtil::drop_table('display');
    }
}
?>
