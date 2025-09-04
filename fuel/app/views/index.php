<div class="action-header">
	<h3>行動一覧</h3>
	<button class="open-btn" data-bind="click: addNewAction">+</button>
</div>
<ul data-bind="foreach: allActions" class="actions-list">
	<li>
		<span data-bind="text: name"></span>
		<button class="show-btn" data-bind="click: $parent.show">></button>
		<button class="delete-btn" data-bind="click: $parent.delete">×</button>
	</li>
</ul>

<style>
	.action-header {
		display: flex;
		align-items: center;
		gap: 15px;
	}

	
	.actions-list li {
		margin-left: -40px;
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 8px;
		margin-bottom: 10px;
	}

	.actions-list li > span::before {
		content: "• ";          
		color: #333;            
		font-weight: bold;  
	}


	.actions-list button {
		padding: 2px 6px;
		cursor: pointer;
		background-color: white;
		color: #ccc;
		font-size: 20px;
		border: none;
		border-radius: 50%;
		padding: 4px 8px;
	}

	.actions-list button:hover {
		color: #999;
	}

	/* 複数ボタンを横に並べて右寄せ */
	.actions-list li > span {
		flex-grow: 1; /* 名前部分が左側を埋める */
	}

	.open-btn {
		padding: 2px 6px;
		font-size: 12px;
		cursor: pointer;
		border: 1px solid #ccc;
		border-radius: 50%;
		background-color: #f8f8f8;
		color: #333;
		transition: background-color 0.2s ease, transform 0.1s ease;
	}
	.open-btn:hover {
		background-color: #e0e0e0;
		transform: scale(1.05);
	}
	.open-btn:active {
		background-color: #ccc;
		transform: scale(0.95);
	}

</style>
  
<script>
	function ActionsViewModel(){
		this.allActions = actions_data;

		//+ボタンを押したとき（追加）
		this.addNewAction = function() {
				settingVM.isVisible(true);
				settingVM.nameToAdd("");
				settingVM.frequencyToAdd("1");
				settingVM.mode("add");
				settingVM.editingId(null);
				settingVM.type("新規作成");
				settingVM.isVisibleColor(false);
				settingVM.color(null);
				settingVM.createdAt("今日");
		}

		//×ボタンを押したとき（削除）
		this.delete = function(item) {

			//確認
			if (!confirm(`本当に 行動:${item.name()} を削除しますか？\n削除すると今までの記録が表示されなくなります(；ω；)`)) {
				return;
			}

			//配列から削除
			//console.log(item.name());
			actions_data.remove(item);
			today_data.remove(function(data){
				return data.action_id === item.id;
			});
			display_data.remove(function(data){
				return  data.action_id === item.id;
			});

			//サーバーに送信
			fetch('/dashboard/delete', {
				method: 'POST',
				headers: { 'Content-Type': 'application/json' },
				body: JSON.stringify(item),
			})
			.then(response => response.json())
			.then(res => {
						console.log("サーバー応答:");//, res['status']);
			})
			.catch(error => {
						console.log("送信エラー:");//, error);
			});

		}.bind(this);


		//>ボタンを押したとき（編集）
		this.show = function(item) {
			//console.log("編集:", item.name(), "id(action)", item.id);
			settingVM.isVisible(true);
			settingVM.before_name = item.name();
			settingVM.before_frequency = item.frequency();
			settingVM.mode("edit");
			settingVM.nameToAdd(item.name());
			settingVM.frequencyToAdd(item.frequency());
			settingVM.isVisibleColor(true);
			settingVM.color(item.color);
			settingVM.createdAt(item.created_at);
			settingVM.editingId(item.id);
			settingVM.type(`編集モード：${settingVM.nameToAdd()}を編集中\n※今日の行動に含まれるものの頻度は、次回から変更されます`);
		}
	}

</script>
