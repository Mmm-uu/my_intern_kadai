<canvas id="myChart"></canvas>

<script>
    function DisplayViewModel(display_data){
        var self = this;
        self.all_display = display_data;

        self.chartLabels = ko.computed(function() {
            return self.all_display().map(item => item.name);
        });

        self.chartData = ko.computed(function() {
            return self.all_display().map(item => {
                let start = new Date(item.start_at);
                let end = new Date(item.last_completed_at);
                end.setDate(end.getDate() + 1);
                return {
                    x: [start, end],
                    y: item.name
                };
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
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Chart.js Floating Bar Chart'
                        }
                    }
                }
            };

            if (chartInstance) {
                // データだけ更新して再描画
                chartInstance.data.labels = self.chartLabels();
                chartInstance.data.datasets[0].data = self.chartData();
                chartInstance.update();
            } else {
                // 初回のみチャート作成
                chartInstance = new Chart(ctx, config);
            }
        };

        self.drawChart();

        self.all_display.subscribe(function() {
            self.drawChart();
        });


    }
    
</script>


