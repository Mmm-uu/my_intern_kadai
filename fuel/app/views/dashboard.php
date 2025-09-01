<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/sanitize.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
    <script src="/assets/js/knockout-3.5.1.js"></script>
    <title>習慣トラッカー</title>
</head>
<body>
    <div class="container">
        <!-- 行動一覧 -->
        <section class="actions-section" id="actions-section">
            <?php echo $actions_view; ?>
        </section>

        <!-- 今日の行動一覧 -->
        <section class="today-section" id="today-section">
            <?php echo $today_view; ?>
        </section>

        <!-- 28日間の記録 -->
        <section class="records-section" id="display-section">
             <?php echo $display_view; ?> 
        </section>
    
        <!-- 設定 -->
        <section class="setting-section" id="setting-section">
            <?php echo $setting_view; ?>
        </section>

    </div>
    
    <style>
        .container {
            display: flex;
            padding: 10px 10px;
        }

        .actions-section,
        .today-section,
        .records-section,
        .setting-section {
            padding: 20px 40px;
            min-width: 340px;
        }

        .records-section {
            min-width: 700px;
        }

        .setting-section {
            margin-left: auto;
        }
        
    </style>
    
    
    <script>
        const actions_data_raw = <?php echo json_encode($actions); ?>;
        const actions_data = ko.observableArray(
            actions_data_raw.map(a => ({
                id:         a.id,
                name:       ko.observable(a.name),
                frequency:  ko.observable(a.frequency),
                color:      a.color,
                created_at:  a.created_at,
                deleted:    ko.observable(a.deleted_at)
            }))
        );

        const today_data_raw = <?php echo json_encode($today_actions); ?>;
        const today_data = ko.observableArray(
            today_data_raw.map(t => ({
                id:         t.id,
                name:       ko.observable(t.name),
                frequency:  ko.observable(t.frequency),
                color:      t.color,
                created_at:  t.created_at,
                deleted:    ko.observable(t.deleted_at),
                action_id:  t.action_id,
                data:       t.data,
                status:     ko.observable(t.status),
                next_at:    t.next_at,
                current_streak: ko.observable(t.current_streak)
            }))
        );

        const display_data_raw = <?php echo json_encode($display); ?>;
        const display_data = ko.observableArray(
            display_data_raw.map(d => ({
                id:                 d.id,
                action_id:          d.action_id,
                name:               ko.observable(d.name),
                color:              d.color,
                current_streak:     ko.observable(d.current_streak),
                start_at:           d.start_at,
                last_completed_at:  ko.observable(d.last_completed_at)
            }))
        );

        
        const actionsVM = new ActionsViewModel();
        const displayVM = new DisplayViewModel();
        const todayVM = new TodayActionsViewModel();
        const settingVM = new SettingViewModel();

        ko.applyBindings(actionsVM, document.getElementById('actions-section'));
        ko.applyBindings(todayVM, document.getElementById('today-section'));
        ko.applyBindings(displayVM, document.getElementById('display-section'));
        ko.applyBindings(settingVM, document.getElementById('setting-section'));
        
        
    </script>

</body>
</html>