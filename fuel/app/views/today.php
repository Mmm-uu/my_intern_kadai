<ul class="today-list" data-bind="foreach: all_today_actions">
    <li data-bind="css: { completed: isCompleted }">
        <input type="checkbox" data-bind="checked: isCompleted" />
        <span data-bind="text: name"></span>
    </li>
</ul>

<style>
    .today-list {
        list-style: none;
        padding-left: 10px;
    }
    .completed {
        background-color: #d4edda;
        color: #155724;
        text-decoration: line-through;
    }
    
</style>
             

<script>
    
    function TodayActionsViewModel(today_data) {
        var self = this;
        
        self.all_today_actions = ko.observableArray(
            today_data.map(action => {
                const isCompleted = ko.observable(action.status === '1');
                isCompleted.subscribe(function(newValue) {
                    console.log("送信データ", {
                    action_id: action.action_id,
                    status: newValue ? 1 : 0
                });
                    fetch('/dashboard/completed', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            action_id: action.action_id,
                            status: newValue ? 1 : 0
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('更新成功:', data);
                    })
                    .catch(error => {
                        console.error('更新失敗:', error);
                    });
                });
                return {
                    name: action.name,
                    frequency: action.frequency,
                    isCompleted: isCompleted
                };
            })
        );
    }
    
</script>
