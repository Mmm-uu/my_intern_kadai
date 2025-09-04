<div class="setting-container" data-bind="css: { shown: isVisible }">
	<div class="setting-header">
		<h3>設定</h3>
		<button data-bind="click: close">×</button>
	</div>
	<form class="setting" data-bind="submit: addItem">
		<span data-bind="text: type" style="white-space: pre-line;"></span>
		</br>
		行動名：(10文字まで)<input type="text" data-bind="value: nameToAdd" /><br/>
		頻度（1=毎日）：<input type="number" data-bind="value: frequencyToAdd" />
		<button type="submit">保存</button>
		</br>
		カラー：
		<span data-bind="visible: isVisibleColor, style: { backgroundColor: color }, css: 'color-box'"></span>
		<span data-bind="visible: !isVisibleColor()">保存後に決定します</span>
		</br>
		作成日：<span data-bind="text: createdAt"></span>
	</form>
</div>

<style>
	.setting {
		display: flex;
		flex-direction: column;
		width: 100%;
		padding: 10px;
	}

	.setting-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.setting-container {
		position: fixed;
		padding: 20px;
		top: 0;
		right: 0;
		width: 350px;
		height: 100vh;
		background-color: #fff;
		box-shadow: -3px 0 10px rgba(0,0,0,0.2);
		transform: translateX(100%);
		opacity: 0;
		pointer-events: none;
		transition: transform 0.5s ease, opacity 0.5s ease;
		z-index: 1000;
		display: flex;
		flex-direction: column;
	}

	.setting-container.shown {
		transform: translateX(0);     /* 画面内に移動 */
		opacity: 1;
		pointer-events: auto;
	}

	.color-box {
		display: inline-block;
		width: 20px;
		height: 20px;
		border-radius: 50%;
	}

	.color-text {
		writing-mode: horizontal-tb; 
		display:inline;
	}

</style>

    
<script>
	function SettingViewModel() {
		var self = this;

		self.isVisible = ko.observable(false);
		
		self.type = ko.observable("新規作成モード");
		self.nameToAdd = ko.observable("");
		self.frequencyToAdd = ko.observable(1);
		
		self.mode = ko.observable("add");
		self.editingId = ko.observable(null);
		
		self.isVisibleColor = ko.observable(false);
		self.color = ko.observable(null);
		self.createdAt = ko.observable("今日");

		self.addItem = function() {
			if (!self.nameToAdd() || self.nameToAdd().trim().length === 0){
				alert("行動名を入力してください");
				return;
			}
			const frequency_int = parseInt(self.frequencyToAdd(), 10);
			if (isNaN(frequency_int) || frequency_int <= 0) {
				alert("頻度は1以上の整数を入力してください");
				return false;
			}
			self.frequencyToAdd(frequency_int);

			if ((self.nameToAdd() !== "") && self.frequencyToAdd() > 0) {

				if (self.nameToAdd().length > 10) {
					alert("行動名を10文字以下にしてください！");
					return false;
				}

				const data = {
					name: self.nameToAdd(),
					frequency: frequency_int
				};
				
				if (self.mode() === "add"){
					fetch('/dashboard/setting', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'
						},
						body: JSON.stringify(data),
					})
					.then(response => response.json())
					.then(res => {
						console.log("サーバー応答:");//, res['status']);
						//console.log(res['added_display']);
						actions_data.push({
							id:         res['added_action'].id,
							name:       ko.observable(res['added_action'].name),
							frequency:  ko.observable(res['added_action'].frequency),
							color:      res['added_action'].color,
							created_at:  res['added_action'].created_at,
							deleted:    ko.observable(res['added_action'].deleted_at)
						});
						today_data.push({
							id:         res['added_record'].id,
							name:       ko.observable(res['added_record'].name),
							frequency:  ko.observable(res['added_record'].frequency),
							color:      res['added_record'].color,
							created_at:  res['added_record'].created_at,
							deleted:    ko.observable(res['added_record'].deleted_at),
							action_id:  res['added_record'].action_id,
							data:       res['added_record'].data,
							status:     ko.observable(res['added_record'].status),
							next_at:    res['added_record'].next_at,
							current_streak: ko.observable(res['added_record'].current_streak)
						});
						display_data.push({
							id:                 res['added_display'].id,
							action_id:          res['added_display'].action_id,
							name:               ko.observable(res['added_display'].name),
							color:              res['added_display'].color,
							current_streak:     ko.observable(res['added_display'].current_streak),
							start_at:           res['added_display'].start_at,
							last_completed_at:  ko.observable(res['added_display'].last_completed_at)
						});
						self.nameToAdd("");
						self.frequencyToAdd(1);
					})
					.catch(error => {
						console.log("送信エラー:");//, error);
					});
				}
				else if (self.mode() === "edit"){
					data.id = self.editingId();
					data.before_frequency = self.before_frequency;

					if (self.before_name === self.nameToAdd() && self.before_frequency === self.frequencyToAdd()) {
						alert("変更がありません！");
						return false;
					}
					else {
						fetch('/dashboard/edit', {
							method: 'POST',
							headers: {
								'Content-Type': 'application/json'
							},
							body: JSON.stringify(data),
						})
						.then(response => response.json())
						.then(res => {
							console.log("サーバー応答:");//, res['status']);
							let item_actions = actions_data().find(a => a.id == self.editingId());
							if (item_actions) {
								item_actions.name(res['edited_action'].name);
								item_actions.frequency(res['edited_action'].frequency);
							};
							let item_today = today_data().find(t => t.action_id == self.editingId());
							if (item_today) {
								item_today.name(res['edited_action'].name);
								item_today.frequency(res['edited_action'].frequency);
							};
							let item_display = display_data().find(d => d.action_id == self.editingId());
							if (item_display) {
								item_display.name(res['edited_action'].name);
							};
							if (res['record_result'] != null && res['display_result'] != null){  //今日の予定に追加する行動
								display_data.push({
									id:                 res['display_result'].id,
									action_id:          res['display_result'].action_id,
									name:               ko.observable(res['display_result'].name),
									color:              res['display_result'].color,
									current_streak:     ko.observable(res['display_result'].current_streak),
									start_at:           res['display_result'].start_at,
									last_completed_at:  ko.observable(res['display_result'].last_completed_at)
								});

								today_data.push({
									id:             res['record_result'].id,
									name:           ko.observable(res['record_result'].name),
									frequency:      ko.observable(res['record_result'].frequency),
									color:          res['record_result'].color,
									created_at:      res['record_result'].created_at,
									deleted:        ko.observable(res['record_result'].deleted_at),
									action_id:      res['record_result'].action_id,
									data:           res['record_result'].data,
									status:         ko.observable(res['record_result'].status),
									next_at:        res['record_result'].next_at,
									current_streak: ko.observable(res['current_streak'].current_streak)
								});
									
									//console.log(res['edit_result']);
									//console.log(res['display_result']);
							}
							self.nameToAdd("");
							self.frequencyToAdd(1);
							self.mode("add");
							self.editingId(null);
							self.type("新規作成モード");
							self.isVisibleColor(false);
							self.color = ko.observable(null);
							self.createdAt = ko.observable("今日");
						})
						.catch(error => {
							console.log("送信エラー:");//, error);
						});
					}
				}
			}
			return false;

		}

		this.close = function() {
			this.isVisible(false);
			self.nameToAdd("");
			self.frequencyToAdd(1);
		}
			
	}
    
    
</script>

