#!/bin/bash
# コンテナ内で FuelPHP の oil を使ってタスクを実行
cd /var/www/html/my_fuel_project
php oil r daily_update
