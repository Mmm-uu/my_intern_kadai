<div id="chartContainer">
    <canvas id="myChart"></canvas>
</div>



<script>
    function DisplayViewModel(display_data){
        var self = this;
        self.all_display = display_data;

        self.chartLabels = ko.computed(function() {
            return self.all_display().map(item => item.name);
        });

        self.chartData = ko.computed(function() {
            return self.all_display().map(item => {
                if (item.last_completed_at) {  //継続記録がある場合
                    let start = new Date(item.start_at + "T12:00:00");        //表示のために時間を設定
                    let end = new Date(item.last_completed_at + "T12:00:00"); //表示のために時間を設定
                    start.setDate(start.getDate() - 1);                       //表示のために日付けを変更
                    return {
                        x: [start, end],
                        y: item.name
                    };
                }
                else { //継続記録がない場合
                    let start = new Date(item.start_at);
                    return {
                        x: [start, start],
                        y: [item.name]
                    }
                }
            });
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
                    backgroundColor: 
                        'rgba(255, 99, 132, 0.2)',
                    borderColor: 
                        'rgba(255,99,132,1)',
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

        self.drawChart();

        
        // self.all_display.subscribe(function() {
            // self.drawChart();
        // });
        
        const baseHeight = 30; // 項目ごとの高さ(px)

        function adjustChartHeight(items) {
            const newHeight = 50 + items.length * baseHeight;
            document.getElementById('chartContainer').style.height = newHeight + "px";
        }

        adjustChartHeight(self.all_display()); // 初期ロード時に高さ調整

        self.all_display.subscribe(function(newItems) {
            adjustChartHeight(newItems); // 更新時にも高さ調整
            self.drawChart();
        });


    }
    
</script>


