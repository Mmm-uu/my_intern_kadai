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

    

<script>
    function SettingViewModel(actionsVM, todayVM) {
        var self = this;
        self.nameToAdd = ko.observable("");
        self.frequencyToAdd = ko.observable(1);
        
        self.addItem = function() {
            if ((self.nameToAdd() !== "") && self.frequencyToAdd() > 0) {

                const data = {
                    name: self.nameToAdd(),
                    frequency: self.frequencyToAdd()
                };
                actionsVM.all_actions.push(data);
                

                data.isCompleted = ko.observable(0);
                todayVM.all_today_actions.push(data);

                fetch('/dashboard/setting', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data),
                })
                .then(response => response.json())
                .then(res => {
                    console.log("サーバー応答:", res);
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

