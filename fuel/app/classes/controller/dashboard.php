<?php

class Controller_Dashboard extends Controller
{
    // public function before(){
        // return \View::forge('dashboard');
    // }

    //モデルからデータとってきて、ビューのdashboard.phpに、データと部分ビューを渡す
    public function action_index()
    {
        $actions = \Model_Actions::get_all_actions();
        $actions_view = \View::forge('index');

        $today_actions = \Model_Actions::get_today_actions();
        $today_view = \View::forge('today');

        $display = \Model_Display::get_continuous_display($today_actions);
        $display_view = \View::forge('display');

        $setting_view = \View::forge('setting');

        $data = [
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
    public function action_record($today_actions = null)
    {
        if ($today_actions) {
            \Model_Records::add_new_record($today_actions['id']);
        
            \Response::redirect('dashboard');
        }
        return \View::forge('dashboard');
    }

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

            //入力を直接使ってるから危険?
            $added_action = \Model_Actions::create_new_action($data); 
            $added_record = \Model_Records::create_new_record($data);
            $added_display = \Model_Display::create_display($added_record);
            
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