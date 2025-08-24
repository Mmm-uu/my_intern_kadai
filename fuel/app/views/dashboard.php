<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="/assets/js/knockout-3.5.1.js"></script>
    <title>習慣トラッカー</title>
</head>
<body>
    <div class="container">
        <section class="actions-section" id="actions-section">
            <h2>行動一覧</h2>
            <?php echo $actions_view; ?>
        </section>

        <section class="today-section" id="today-section">
             <h2>今日の行動一覧</h2>
            <?php echo $today_view; ?>
        </section>

        <section class="records-section" id="display-section">
             <h2>28日間の記録</h2>
             <?php echo $display_view; ?> 
        </section>

        <section class="setting-section" id="setting-section">
            <h2>設定</h2>
            <?php echo $setting_view; ?>
        </section>

    </div>
    
    <style>
        .container {
            display: flex;
            padding: 10px 20px; 
        }

        .records-section {
            min-width: 800px;
        }

        .setting-section {
            margin-left: auto;
        }

        .actions-section,
        .today-section,
        .records-section,
        .setting-section {
            padding: 10px 20px;
        }

        
    </style>
    
    
    <script>
        const actions_data = <?php echo json_encode($actions); ?>;
        const today_data = <?php echo json_encode($today_actions); ?>;
        const display_data = <?php echo json_encode($display); ?>;

        const actionsVM = new ActionsViewModel(actions_data);
        const todayVM = new TodayActionsViewModel(today_data);
        const displayVM = new DisplayViewModel(display_data);
        const settingVM = new SettingViewModel(actionsVM, todayVM);

        ko.applyBindings(actionsVM, document.getElementById('actions-section'));
        ko.applyBindings(settingVM, document.getElementById('setting-section'));
        ko.applyBindings(displayVM, document.getElementById('display-section'));
        ko.applyBindings(todayVM, document.getElementById('today-section'));
        
    </script>

</body>
</html>