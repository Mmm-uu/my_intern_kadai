<h3 class="display-header">28日間の記録</h3>
<div id="chartContainer">
    <canvas id="myChart"></canvas>
</div>

<style>
    .display-header {
        padding-bottom: 60px;
    }
</style>

<script>
    function DisplayViewModel(){
        var self = this;
        //display_data = display_data;

        //self.chartLabels = ko.computed(function() {
        //    return display_data().map(item => item.name());
        //});

        self.chartData = ko.computed(function() {
            const grouped = {};

            display_data().map(item => {
                if (!grouped[item.action_id]) grouped[item.action_id] = [];
                grouped[item.action_id].push(item);
            });

            const result = [];
            for (const action_id in grouped) {
                const items = grouped[action_id];

                items.forEach(item => {
                    let start = new Date(item.start_at + "T12:00:00");        //表示のために時間を設定
                    start.setDate(start.getDate() - 1);
                    let end = item.last_completed_at()
                                    ? new Date(item.last_completed_at() + "T12:00:00") //表示のために時間を設定
                                    : start;                   //表示のために日付けを変更
                    result.push({
                        x: [start, end],
                        y: items[0].name(),
                        action_id: action_id
                    });
                });
            }
            return result;
        });

        self.chartLabels = ko.computed(function() {
            const labels = [];
            const seen = new Set();
            display_data().forEach(item => {
                if (!seen.has(item.action_id)) {
                    labels.push(item.name());
                    seen.add(item.action_id);
                }
            });
            return labels;
        });

        self.chartColor = ko.computed(function() {
        const colorMap = {};
        display_data().forEach(item => {
            if (!colorMap[item.action_id]) {
                colorMap[item.action_id] = item.color;
            }
        });

        const colors = self.chartData().map(dataPoint => {
            return colorMap[dataPoint.action_id];
        });

        return colors;
    });


        let chartInstance = null;

        self.drawChart = function() {
            const ctx = document.getElementById('myChart');

            const today = new Date();
            const past28 = new Date();
            past28.setDate(today.getDate() - 28);


            const data = {
                labels: self.chartLabels(),
                datasets: [{
                    label: '継続状況',
                    data: self.chartData(),
                    backgroundColor: self.chartColor(),
                    borderColor: self.chartColor(),
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    maintainAspectRatio: false,
                    borderSkipped: false,
                    indexAxis: 'y',
                    responsive: true,
                    scales: {
                        x: {
                            type: 'time',
                            time: { unit: 'day' },
                            min: past28, // 28日前
                            max: today   // 今日
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'buttom',
                        },
                        title: {
                            display: false,
                            text: 'Chart.js Floating Bar Chart'
                        }
                    }
                }
            };

            if (chartInstance) {
                // データだけ更新して再描画→このパターンにするとアニメーションがつかない
                //chartInstance.data.labels = self.chartLabels();
                //chartInstance.data.datasets[0].data = self.chartData();
                //chartInstance.update();
                chartInstance.destroy();
            } //else {
                // 初回のみチャート作成
                //chartInstance = new Chart(ctx, config);
            //}
            chartInstance = new Chart(ctx, config);
        };

        ko.computed(function() {
            display_data().forEach(item => {
                item.name();             
                item.current_streak();   
                item.last_completed_at();
            });
            self.drawChart();            
        });


        
        // display_data.subscribe(function() {
            // self.drawChart();
        // });
        
        const baseHeight = 42; // 項目ごとの高さ(px)

        function adjustChartHeight(items) {
            const uniqueActions = [...new Set(items.map(item => item.action_id))];
            const newHeight = 50 + uniqueActions.length * baseHeight;
            document.getElementById('chartContainer').style.height = newHeight + "px";
                //const newHeight = 50 + items.length * baseHeight;
                //document.getElementById('chartContainer').style.height = newHeight + "px";
        }

        adjustChartHeight(display_data()); // 初期ロード時に高さ調整

        display_data.subscribe(function(newItems) {
            adjustChartHeight(newItems); // 更新時にも高さ調整
            self.drawChart();
        });


    }
    
</script>


