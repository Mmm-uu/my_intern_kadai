<?php

class Controller_Dashboard extends Controller_Base
{
    public function before()
    {
        parent::before();
        if (!\Auth::check()){
            \Response::redirect('login');
        }
    }

    // public function action_first()
    // {
        // list(, $user_id) = \Auth::get_user_id();
        // $username = \Auth::get_screen_name();
        // return \View::forge('dashboard', ['username' => $username]);
    // }

    //モデルからデータとってきて、ビューのdashboard.phpに、データと部分ビューを渡す
    public function action_index()
    {
        list(, $user_id) = \Auth::get_user_id();
        $user_name = \Auth::get_screen_name();

        $actions = \Model_Actions::get_all_actions($user_id);
        $actions_view = \View::forge('index');

        $today_actions = \Model_Records::get_today_actions($user_id);
        $today_view = \View::forge('today');

        $display = \Model_Display::get_continuous_display($today_actions);
        $display_view = \View::forge('display');

        $setting_view = \View::forge('setting');

        $data = [
            'user_name' => $user_name,
            'actions' => $actions,
            'today_actions' => $today_actions,
            'display' => $display,
            'actions_view' => $actions_view,
            'today_view' => $today_view,
            'display_view' => $display_view,
            'setting_view' => $setting_view,
        ];
        
        return \View::forge('dashboard', $data, false);
    }

    //新しいレコードを作る
    //今日の行動のレコードが追加されて、未完了の状態が記録される
    //public function action_record($today_actions = null)
    //{
    //    if ($today_actions) {
    //        \Model_Records::add_new_record($today_actions['id']);
        
     //       \Response::redirect('dashboard');
      //  }
     //   return \View::forge('dashboard');
    //}

    //チェックボックスの状態を受け取る
    public function action_completed()
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true); //$dataは、action_idとstatus
        

        if (\Input::method() == 'POST' && isset($data['action_id']) && isset($data['status'])) {

            \Model_Records::change_status_record($data);
            $change_display = \Model_Display::change_display($data);

            return \Response::forge(
                json_encode([
                    'status' => 'success',
                    'change_display' => $change_display
                ]), 
                200, 
                ['Content-Type' => 'application/json']
            );
        }
        return \Response::forge(
            json_encode(['status' => 'error']), 
            400, 
            ['Content-Type' => 'application/json']
        );
    }
    

    //行動を追加する
    public function action_setting()
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);           //$dataはnameとfrequencyと

        if (\Input::method() == 'POST' && $data) {
        
            //バリデーション書く?
            list(, $user_id) = \Auth::get_user_id();
            $data['user_id'] = $user_id;

            //入力を直接使ってるから危険?
            $added_action = \Model_Actions::create_new_action($data); 
            $added_display = \Model_Display::create_display($added_action);
            $added_record = \Model_Records::create_new_record($data);//順番大事
            
            return \Response::forge(
                json_encode([
                    'status' => 'success',
                    'added_action' => $added_action,
                    'added_record' => $added_record,
                    'added_display' => $added_display
                ]), 
                200, 
                ['Content-Type' => 'application/json']
            );
        }

        return \Response::forge(
            json_encode(['status' => 'error']), 
            400, 
            ['Content-Type' => 'application/json']
        );
        
    }

    public function action_edit() 
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (\Input::method() == 'POST' && $data) {

            $edited_action = \Model_Actions::edit_action($data); //$dataはnameとfrequencyとid(action)とbefore_frequency
            $record_result = \Model_Records::edit_record($data);  //$edit_resultがnullなら今日の行動に変化はない
            if ($record_result) {
                $display_result = \Model_Display::edit_display($record_result);
            }
            else {
                $record_result = null;
                $display_result = null;
            }
            return \Response::forge(
                json_encode([
                    'status' => 'success',
                    'edited_action' => $edited_action,  //$edited_actionはnameとfrequency
                    'record_result' => $record_result,      //$record_resultはnullかインサートした今日のレコード
                    'display_result' => $display_result     //$display_resultはnullかインサートした今日のグラフのデータ
                ]),
                200,
                ['Content-Type' => 'application/json']
            );
        }
        return \Response::forge(
            json_encode(['status' => 'error']), 
            400, 
            ['Content-Type' => 'application/json']
        );
    }

    public function action_delete()
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (\Input::method() == 'POST' && $data) {

            \Model_Actions::delete_action($data);
            //\Model_Records::delete_record($data);
            
            return \Response::forge(
                json_encode([
                    'status' => 'success'
                ]), 
                200, 
                ['Content-Type' => 'application/json']
            );
        }
        return \Response::forge(
            json_encode(['status' => 'error']), 
            400, 
            ['Content-Type' => 'application/json']
        );

    }
}
       
    

?>