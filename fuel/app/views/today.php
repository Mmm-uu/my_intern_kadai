<div class="today-header">
	<h3>今日の行動一覧</h3>
	<div class="chart">
		<canvas id="completionChart"></canvas>
	</div>  
</div>
<ul class="today-list" data-bind="foreach: allTodayActions">
	<label class="custom-checkbox">
		<input type="checkbox" data-bind="checked: isCompleted" />
		<span data-bind="style: { backgroundColor: isCompleted() ? completedBgColor() : 'transparent',
															borderColor: completedBgColor }"></span>
		<span class="name" data-bind="text: name, style: { textDecoration: completedLine}"></span>
		<img class="img" src="/assets/img/arrow.png" alt="継続イメージ">
		<span class="running-days" data-bind="text: runningDays, style: { color: runningDaysColor }"></span>
	</label>  
</ul>


<style>
	.today-header {
		display: flex;
		gap: 20px;
	}
	.chart {
		width: 100px;
		margin-top: 20px;
	}


	.today-list {
		list-style: none;
		padding-left: 0px;
	}

	.today-list ul {
		padding-left: -50px;
	}
	.today-list li {
		padding: 4px 5px;
		margin-bottom: 4px;
		transition: all 0.3s ease;
	}

	.custom-checkbox {
		display: flex;
		align-items: center;
		padding: 8px 0;
		gap: 8px;
	}

	.name {
		flex: 1;
		text-align: left;
	}

	.img {
		width: 20px;
	}

	.running-days {
		margin-left: auto;
		font-weight: 1000;
	}

	.custom-checkbox input[type="checkbox"] {
		display: none; /* 元のチェックボックスを非表示 */
	}

	.custom-checkbox span:first-of-type {
		display: inline-block;
		width: 22px;
		height: 22px;
		margin-right: 8px;
		border: 2px solid #ccc;
		border-radius: 50%;
		vertical-align: middle;
		transition: all 0.2s ease;
		cursor: pointer;
		position: relative;
	}

	.custom-checkbox input[type="checkbox"]:checked + span:first-of-type::after {  
		content: '✔';
		color: white !important; 
		position: absolute;
		top: -2px;
		left: 3px;
		font-size: 14px;
		z-index: 2;
	}

	/*  
	.completed {
		background-color: #d4edda;
		color: #155724;
		text-decoration: line-through;
	}
	*/
	
</style>
             

<script>
    
	function TodayActionsViewModel() {
		var self = this;
			
		function mapActions(action) {

			const runningDays = ko.observable(action.current_streak() ? action.current_streak() : 0);
			
			//statusと対応
			const isCompleted = ko.computed({
				read: () => action.status() === "1",
				write: value => action.status(value ? "1" : "0")
			});
			
			//console.log(action.color);
			const completedBgColor = ko.pureComputed(() => isCompleted() ? action.color : '');
			const completedLine = ko.pureComputed(() => isCompleted() ? `line-through double ${action.color}` : 'none');
			const runningDaysColor = ko.pureComputed(() => isCompleted() ? action.color : "black" );

			
			//チェックボタが変化したとき
			action.status.subscribe(function(newValue) {
				console.log("送信データ" //, {
										//action_id: action.action_id,
										//action_name: action.name(),
										//status: action.status()
				//}
				);

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
					console.log('更新成功:');//, res['status']);
					
					//display描画用 last_completed_atを書き換えてグラフに反映させる
					let item = display_data().find(x => x.id === res['change_display'].id);
					//console.log('change_display:', res['change_display'].last_completed_at);
					if (item) {
						let day = res['change_display'].last_completed_at;
						if (day!= null) {
							item.last_completed_at(day.slice(0, 10));
						}
						else {
							item.last_completed_at(day);
						}
					};
					runningDays(res['change_display'].current_streak);
					item.current_streak(res['change_display'].current_streak);
					//console.log(item.last_completed_at());
					})
				.catch(err => console.error('更新失敗:'));//, err));
			});

			return {
			name: action.name,
			frequency: action.frequency,
			action_id: action.action_id,
			isCompleted: isCompleted,
			runningDays: runningDays,
			runningDaysColor: runningDaysColor,
			completedBgColor,
			completedLine
			};
		}

		self.allTodayActions = ko.observableArray(today_data().map(mapActions));


		today_data.subscribe(function(changes) {
			changes.forEach(change => {
				//console.log(change.status);
				if (change.status === 'added') {
					const mapped = mapActions(change.value);
					self.allTodayActions.push(mapped);
				}
				else if (change.status === 'deleted') {
					self.allTodayActions.remove(function(item) {
						return item.action_id == change.value.action_id;
					});
				}
			});
		}, null, "arrayChange");


		//円グラフ部分
		let ctx = document.getElementById('completionChart').getContext('2d');
		let completionChart = new Chart(ctx, {
			type: 'doughnut',
			data: {
				labels: ['完了', '未完了'],
				datasets: [{
					data: [0, 0], // 初期値
					backgroundColor: ['#CCCCCC', '#ffffff'],
					borderColor: '#CCCCCC',
					borderWidth: 1
				}]
			},
			options: {
				responsive: true,
				plugins: {
					legend: {
						display: false
					},
					tooltip: {
						enabled: true
					}
				}
			},
			plugins: [{
				id: 'center-text',
				afterDraw(chart) {
					const {ctx, width, height} = chart;
					const dataset = chart.data.datasets[0].data;
					const total = dataset.reduce((a, b) => a + b, 0);
					const percent = total === 0 ? 0 : Math.round(dataset[0] / total * 100);

					ctx.save();
					ctx.font = 'bold 15px sans-serif';
					ctx.fillStyle = '#333';
					ctx.textAlign = 'center';
					ctx.textBaseline = 'middle';
					ctx.fillText(percent + '%', width / 2, height / 2);
					ctx.restore();
				}
			}]
		});

		ko.computed(function() {
			const total = self.allTodayActions().length;
			const completed = self.allTodayActions().filter(a => a.isCompleted()).length;
			const incomplete = total - completed;

			completionChart.data.datasets[0].data = [completed, incomplete];
			completionChart.update();
		});

	}
    
</script>
