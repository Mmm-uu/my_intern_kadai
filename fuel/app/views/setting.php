<form class="setting" data-bind="submit: addItem">
    行動名：<input type="text" data-bind="value: nameToAdd" />
    頻度（1=毎日）：<input type="number" data-bind="value: frequencyToAdd" />
    <button type="submit" data-bind="enable: nameToAdd().length > 0">保存</button>
</form>

<style>
    .setting {
        display: flex;
        flex-direction: column;
        padding: 10px;
    }
</style>

    
<!-- 新しい行動を追加したら actionsテーブルと、recordsテーブルに追加
                        画面にも反映　　　　　　　　　　　　　　　　　-->
<script>
    function SettingViewModel() {
        var self = this;

        self.nameToAdd = ko.observable("");
        self.frequencyToAdd = ko.observable(1);
        
        self.addItem = function() {
            if ((self.nameToAdd() !== "") && self.frequencyToAdd() > 0) {

                const data = {
                    name: self.nameToAdd(),
                    frequency: self.frequencyToAdd()
                };
                
                fetch('/dashboard/setting', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data),
                })
                .then(response => response.json())
                .then(res => {
                    console.log("サーバー応答:", res['status']);
                    console.log(res['added_display']);
                    actions_data.push({
                        id:         res['added_action'].id,
                        name:       ko.observable(res['added_action'].name),
                        frequency:  ko.observable(res['added_action'].frequency),
                        color:      ko.observable(res['added_action'].color),
                        create_at:  res['added_action'].create_at,
                        deleted:    ko.observable(res['added_action'].deleted_at)
                    });
                    today_data.push({
                        id:         res['added_record'].id,
                        name:       ko.observable(res['added_record'].name),
                        frequency:  ko.observable(res['added_record'].frequency),
                        color:      ko.observable(res['added_record'].color),
                        create_at:  res['added_record'].create_at,
                        deleted:    ko.observable(res['added_record'].deleted_at),
                        action_id:  res['added_record'].action_id,
                        data:       res['added_record'].data,
                        status:     ko.observable(res['added_record'].status),
                        next_at:    res['added_record'].next_at
                    });
                    display_data.push({
                        id:                 res['added_display'].id,
                        action_id:          res['added_display'].action_id,
                        name:               ko.observable(res['added_display'].name),
                        current_streak:     ko.observable(res['added_display'].current_streak),
                        start_at:           res['added_display'].start_at,
                        last_completed_at:  ko.observable(res['added_display'].last_completed_at)
                    });
                    self.nameToAdd("");
                    self.frequencyToAdd(1);
                })
                .catch(error => {
                    console.log("送信エラー:", error);
                });

            }
            return false;

        }
        
    }
    
    
</script>

