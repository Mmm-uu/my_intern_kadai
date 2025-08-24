<?php
class Model_Records extends \Model_Crud
{
    protected static $_table_name = 'records';

    public static function create_new_record($data)  //$dataはnameとfrequency
    {

        $id = \DB::select('id')
                ->from('actions')
                ->order_by('id', 'DESC')  //追加した項目だから1番下に存在する
                ->execute()
                ->get('id');

        \DB::insert(self::$_table_name)->set([
            'action_id' => $id,
            'date'      => \DB::expr('CURRENT_TIMESTAMP'),
            'status'    => 0,
            'next_at'   => \DB::expr("DATE_ADD(CURRENT_TIMESTAMP, INTERVAL {$data['frequency']} DAY)"),
        ])->execute();
    }

      
    //public static function add_new_record($today_actions_id)
    //{
     //   foreach ($today_actions as $action) {
            
      //      \DB::insert(self::$_table_name)->set([
       //         'action_id' => $action['id'],
       //         'date'      => \DB::expr('CURRENT_TIMESTAMP'),
        //        'status' => 0,
       //     ])->execute();
       // }
   // }

   public static function change_status_record($data)
   {
        \DB::update(self::$_table_name)
                ->value('status', $data['status'])
                ->where('action_id', '=', $data['action_id'])
                ->and_where(\DB::expr('DATE(records.date)'), '=', \DB::expr('CURDATE()'))
                ->execute();
   }

}

?>