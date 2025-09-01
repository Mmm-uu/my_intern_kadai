<?php
class Model_Actions extends \Model_Crud
{
    protected static $_table_name = 'actions';

    public static function get_all_actions()
    {
        $result = \DB::select('*')
                        ->from(self::$_table_name)
                        ->where('deleted', '=', 0)
                        ->order_by('id', 'ASC')
                        ->execute()
                        ->as_array();
        return $result;
    }

    public static function create_new_action($data)
    {
        $colorList = [
            '#ed5565',
            '#fc6e51',
            '#ffce54',
            '#a0d468',
            '#48cfad',
            '#4fc1e9',
            '#5d9cec',
            '#ac92ec',
            '#ec87c0'
        ];

        $color = $colorList[array_rand($colorList)];

        list($insert_id, $rows) = \DB::insert(self::$_table_name)->set([
                                        'name'      => $data['name'],
                                        'frequency' => $data['frequency'],
                                        'color'     => $color,
                                    ])->execute();

        $added_action = \DB::select('*')
                            ->from(self::$_table_name)
                            ->where('id', '=', $insert_id)
                            ->execute()
                            ->current();
        return $added_action;
    }

    public static function edit_action($data)  //$dataはnameとfrequency
    {
        \DB::update(self::$_table_name)
            ->value('name', $data['name'])
            ->value('frequency', $data['frequency'])
            ->where('id', '=', $data['id'])
            ->execute();

        $edited_action = \DB::select('name', 'frequency')
                            ->from(self::$_table_name)
                            ->where('id', '=', $data['id'])
                            ->execute()
                            ->current();
        return $edited_action;
    }

    public static function delete_action($data) //$data = actions_dataの形
    {
        \DB::update(self::$_table_name)
            ->value('deleted', 1)
            ->where('id', '=', $data['id'])
            ->execute();
    }

}