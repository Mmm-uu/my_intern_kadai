<ul data-bind="foreach: all_actions" class="actions-list">
    <li>
        <span data-bind="text: name"></span>
        <!-- <span class="frequency">(<span data-bind="text: frequency"></span>)</span> -->
        <button data-bind="click: $parent.onMove">></button>
        <button data-bind="click: $parent.delete">×</button>
    </li>
</ul>

<style>
    .actions-list li {
        margin-left: -25px;
        /* display: flex; */
        align-items: center;
        gap: 8px; /* ボタンと文字の間隔 */
    }

    .actions-list button {
        padding: 2px 6px;
        font-size: 10px;
        cursor: pointer;
    }
    /*
    .actions-list li {
        margin-left: -30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .frequency { 
        font-size: 0.8em;
        color: #555;
    }
    */

</style>
  
<script>
    function ActionsViewModel(actions_data){
        this.all_actions = actions_data;

        this.delete = function(item) {

            if (!confirm(`本当に 行動:${item.name} を削除しますか？\n削除すると今までの記録が表示されなくなります(；ω；)`)) {
            return; // キャンセルなら何もしない
            }

            console.log(item.name);
            actions_data.remove(item);
            today_data.remove(function(data){
                return data.action_id === item.id;
            });
            display_data.remove(function(data){
                return  data.action_id === item.id;
            });

            fetch('/dashboard/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(item),
            })
            .then(response => response.json())
            .then(res => {
                    console.log("サーバー応答:", res['status']);
            })
            .catch(error => {
                    console.log("送信エラー:", error);
            });

        }.bind(this);






        

        
    }

</script>
