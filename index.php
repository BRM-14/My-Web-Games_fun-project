<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hammam Clicker | Исправленная версия</title>
    <style>
        :root {
            --bg-color: #1e1e24;
            --card-color: #2a2a35;
            --accent-color: #ff9f1c;
            --text-color: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            user-select: none;
        }

        .game-container {
            background-color: var(--card-color);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            text-align: center;
            max-width: 450px;
            width: 100%;
            border: 2px solid #3d3d4f;
        }

        h1 {
            margin-top: 0;
            font-size: 28px;
            color: var(--accent-color);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            background: #15151c;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .stat-box span {
            display: block;
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
        }

        .stat-box strong {
            font-size: 20px;
            color: #fff;
        }

        .hammam-viewport {
            width: 100%;
            height: 250px;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 25px;
            position: relative;
            background-color: #111;
            box-shadow: inset 0 0 20px rgba(0,0,0,0.8);
            border: 1px solid #444;
        }

        .hammam-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: filter 0.5s ease, transform 0.1s ease;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 16px;
            margin-bottom: 12px;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-transform: uppercase;
        }

        .btn-click {
            background-color: var(--accent-color);
            color: #000;
            box-shadow: 0 4px 15px rgba(255, 159, 28, 0.4);
        }

        .btn-click:active {
            transform: scale(0.95);
        }

        .btn-upgrade {
            background-color: #4caf50;
            color: white;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
        }

        .btn-upgrade:disabled {
            background-color: #444;
            color: #777;
            cursor: not-allowed;
            box-shadow: none;
        }

        .cost-tag {
            font-size: 13px;
            display: block;
            margin-top: 4px;
            color: #e0e0e0;
        }
    </style>
</head>
<body>

<div class="game-container">
    <h1>Hammam Clicker</h1>

    <div class="stats">
        <div class="stat-box">
            <span>Баланс</span>
            <strong id="balance-display">0</strong> vMon
        </div>
        <div class="stat-box">
            <span>Уровень Хаммама</span>
            Lvl <strong id="level-display">1</strong>
        </div>
    </div>

    <div class="hammam-viewport">
        <img src="https://unsplash.com"
             id="hammam-pic"
             class="hammam-img"
             alt="Ваш Хаммам">
    </div>

    <button class="btn btn-click" id="click-btn">
        Кликнуть (+<span id="power-display">1</span> vMon)
    </button>

    <button class="btn btn-upgrade" id="upgrade-btn" disabled>
        Улучшить Хаммам
        <span class="cost-tag">Стоимость: <span id="cost-display">100</span> vMon</span>
    </button>
</div>

<script>
    // Константы математики игры
    const BASE_UPGRADE_COST = 100;
    const COST_MULTIPLIER = 1.3;
    const MAX_LEVEL = 9999;

    // Загружаем данные из памяти браузера или ставим дефолт
    let vMon = parseInt(localStorage.getItem('hammam_vMon')) || 0;
    let hammamLevel = parseInt(localStorage.getItem('hammam_level')) || 1;

    // Элементы интерфейса
    const balanceDisplay = document.getElementById('balance-display');
    const levelDisplay = document.getElementById('level-display');
    const powerDisplay = document.getElementById('power-display');
    const costDisplay = document.getElementById('cost-display');
    const hammamPic = document.getElementById('hammam-pic');
    const clickBtn = document.getElementById('click-btn');
    const upgradeBtn = document.getElementById('upgrade-btn');

    // Формула расчета стоимости апгрейда
    function getUpgradeCost(level) {
        return Math.round(BASE_UPGRADE_COST * Math.pow(COST_MULTIPLIER, level - 1));
    }

    // Формула расчета силы клика
    function getClickPower(level) {
        return 1 + Math.floor((level - 1) * 1.5);
    }

    // Красивое форматирование чисел (с пробелами)
    function formatNum(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\n))/g, " ");
    }

    // Сохранение прогресса
    function saveGame() {
        localStorage.setItem('hammam_vMon', vMon);
        localStorage.setItem('hammam_level', hammamLevel);
    }

    // Обновление всего экрана
    function updateUI() {
        let currentCost = getUpgradeCost(hammamLevel);
        let currentPower = getClickPower(hammamLevel);

        balanceDisplay.textContent = formatNum(vMon);
        levelDisplay.textContent = hammamLevel;
        powerDisplay.textContent = currentPower;
        costDisplay.textContent = formatNum(currentCost);

        // Проверка вашей логики: если денег хватает — включаем кнопку
        if (vMon >= currentCost && hammamLevel < MAX_LEVEL) {
            upgradeBtn.removeAttribute('disabled');
        } else {
            upgradeBtn.setAttribute('disabled', 'true');
        }

        // Эволюция графики хаммама
        if (hammamLevel === 1) {
            hammamPic.style.filter = "grayscale(95%) contrast(80%) brightness(45%) sepia(20%) blur(0.5px)";
        } else if (hammamLevel < 5) {
            hammamPic.style.filter = "grayscale(70%) contrast(90%) brightness(60%)";
        } else if (hammamLevel < 15) {
            hammamPic.style.filter = "grayscale(35%) contrast(100%) brightness(80%)";
        } else if (hammamLevel < 50) {
            hammamPic.style.filter = "grayscale(0%) contrast(100%) brightness(100%)";
        } else {
            // Элитный уровень с золотым свечением
            hammamPic.style.filter = "grayscale(0%) contrast(115%) brightness(120%) saturate(140%) drop-shadow(0 0 15px gold)";
        }
    }

    // Обработчик КЛИКА (Заработок)
    clickBtn.addEventListener('click', () => {
        vMon += getClickPower(hammamLevel);
        saveGame();
        updateUI();

        // Визуальный мини-эффект толчка картинки при клике
        hammamPic.style.transform = "scale(0.98)";
        setTimeout(() => hammamPic.style.transform = "scale(1)", 50);
    });

    // Обработчик УЛУЧШЕНИЯ (Апгрейд)
    upgradeBtn.addEventListener('click', () => {
        let currentCost = getUpgradeCost(hammamLevel);

        // Жёсткая проверка: баланс >= стоимости
        if (vMon >= currentCost && hammamLevel < MAX_LEVEL) {
            vMon -= currentCost;
            hammamLevel += 1;
            saveGame();
            updateUI();
        }
    });

    // Первая отрисовка при запуске страницы
    updateUI();
</script>

</body>
</html>
