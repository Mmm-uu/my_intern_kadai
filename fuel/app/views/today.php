<ul class="today-list" data-bind="foreach: all_today_actions">
    <li data-bind="css: { completed: isCompleted }">
        <input type="checkbox" data-bind="checked: isCompleted" />
        <span data-bind="text: name"></span>
        <span data-bind="text: running_days"></span>
    </li>
</ul>

<style>
    .today-list {
        list-style: none;
        padding-left: 20px;
    }
    .completed {
        background-color: #d4edda;
        color: #155724;
        text-decoration: line-through;
    }
    
</style>
             

<script>
    
    function TodayActionsViewModel() {
        var self = this;
        
        function mapActions(action) {

            const running = display_data().find(x => x.action_id === action.action_id);
            const running_days = ko.observable(running ? running.current_streak() : 0);
            
            //statusと対応
            const isCompleted = ko.computed({
                read: () => action.status() === "1",
                write: value => action.status(value ? "1" : "0")
            });
            
            //ko.observable(action.status() === "1");
            
            //チェックボタン変化したとき
            action.status.subscribe(function(newValue) {
                console.log("送信データ", {
                            action_id: action.action_id,
                            action_name: action.name(),
                            status: action.status()
                });

                fetch('/dashboard/completed', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action_id: action.action_id,
                        status: action.status()
                    }),
                })
                .then(response => response.json())
                .then(res => {
                    console.log('更新成功:', res['status']);
                    
                    //display描画用 last_completed_atを書き換えてグラフに反映させる
                    let item = display_data().find(x => x.id === res['change_display'].id);
                    console.log('change_display:', res['change_display'].last_completed_at);
                    if (item) {
                        let day = res['change_display'].last_completed_at;
                        if (day!= null) {
                            item.last_completed_at(day.slice(0, 10));
                        }
                        else {
                            item.last_completed_at(day);
                        }
                        
                        displayVM.drawChart();
                    };
                    running_days(res['change_display'].current_streak);
                    item.current_streak = res['change_display'].current_streak;
                    console.log(item.last_completed_at());
                    //display_data.valueHasMutated();
                    })
                .catch(err => console.error('更新失敗:', err));
            });


            return {
            name: action.name,
            frequency: action.frequency,
            action_id: action.action_id,
            isCompleted: isCompleted,
            running_days: running_days
            };
        }

        self.all_today_actions = ko.observableArray(today_data().map(mapActions));

        today_data.subscribe(function(changes) {
            changes.forEach(change => {
                console.log(change.status);
                if (change.status === 'added') {
                    const mapped = mapActions(change.value);
                    self.all_today_actions.push(mapped);
                }
                else if (change.status === 'deleted') {
                    self.all_today_actions.remove(function(item) {
                        return item.action_id == change.value.action_id;
                    });
                }
            });
        }, null, "arrayChange");
    }
    
</script>
