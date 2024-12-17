<?php

function playGame($attempts) {
    echo "Я загадав число від 1 до 100. Спробуй вгадати.\n";

    $numberToGuess = rand(1, 100);
    $compare = fn($userGuess) => $userGuess <=> $numberToGuess;

    for ($attempt = 1; $attempt <= $attempts; $attempt++) {
        echo "Спроба $attempt: ";
        $input = trim(fgets(STDIN));

        if (!validate($input)) {
            echo "Будь ласка, введіть ціле число від 1 до 100.\n";
            $attempt--;
            continue;
        }

        $guess = (int)$input;
        $result = $compare($guess);

        if ($result === 0) {
            echo "Вітаю! Ви вгадали число $numberToGuess за $attempt спроб(и).\n";
            return;
        } elseif ($result === 1) {
            echo "Спробуй менше.\n";
        } else {
            echo "Спробуй більше.\n";
        }
    }

    echo "Ви програли. Загадане число було $numberToGuess.\n";
}

function playRockPaperScissors() {
    $options = ["камінь", "ножиці", "папір"];
    $userScore = 0;
    $computerScore = 0;

    echo "Гра \"Камінь, Ножиці, Папір\". Ми зіграємо 3 раунди.\n";

    for ($round = 1; $round <= 3; $round++) {
        echo "Раунд $round. Обери:\n[0] камінь\n[1] ножиці\n[2] папір\n-> ";
        $input = trim(fgets(STDIN));

        if (!validateRPS($input)) {
            echo "Будь ласка, введіть число від 0 до 2.\n";
            $round--;
            continue;
        }

        $userChoice = (int)$input;
        $computerChoice = random_int(0, 2);

        echo "Я вибрав: {$options[$computerChoice]}\n";

        $result = playRound($userChoice, $computerChoice);

        if ($result === 1) {
            echo "Ти виграв цей раунд!\n";
            $userScore++;
        } elseif ($result === -1) {
            echo "Я виграв цей раунд!\n";
            $computerScore++;
        } else {
            echo "Цей раунд завершився нічиєю.\n";
        }
    }

    echo "\nРезультат: Ти - $userScore, Я - $computerScore.\n";

    if ($userScore > $computerScore) {
        echo "Вітаю! Ти виграв гру!\n";
    } elseif ($userScore < $computerScore) {
        echo "Я виграв гру!\n";
    } else {
        echo "Гра завершилась нічиєю!\n";
    }
}

function playRound($userChoice, $computerChoice) {
    if ($userChoice === $computerChoice) {
        return 0;
    }

    if (($userChoice === 0 && $computerChoice === 1) ||
        ($userChoice === 1 && $computerChoice === 2) ||
        ($userChoice === 2 && $computerChoice === 0)) {
        return 1;
    }

    return -1;
}

function playCalculator() {
    echo "Консольний Калькулятор. Введіть вираз (напр. 5 + 3): ";
    $input = trim(fgets(STDIN));

    if (!validateCalculator($input)) {
        echo "Некоректний формат виразу. Введіть у форматі число оператор число (напр. 5 + 3).\n";
        return;
    }

    [$num1, $operator, $num2] = parseExpression($input);

    $result = calculate($num1, $operator, $num2);
    if ($result === null) {
        echo "Некоректний оператор. Підтримувані оператори: +, -, *, /, **, %.\n";
    } else {
        echo "Результат: $result\n";
    }
}

function calculate($num1, $operator, $num2) {
    return match ($operator) {
        "+" => $num1 + $num2,
        "-" => $num1 - $num2,
        "*" => $num1 * $num2,
        "/" => $num2 != 0 ? $num1 / $num2 : "Ділення на нуль неможливе",
        "**" => $num1 ** $num2,
        "%" => $num2 != 0 ? $num1 % $num2 : "Ділення на нуль неможливе",
        default => null,
    };
}

function validateCalculator($input) {
    return preg_match('/^\s*\d+\s*[\+\-\*\/\%\*\*]+\s*\d+\s*$/', $input);
}

function parseExpression($input) {
    preg_match('/(\d+)\s*([\+\-\*\/\%\*\*]+)\s*(\d+)/', $input, $matches);
    return [(int)$matches[1], $matches[2], (int)$matches[3]];
}

function validate($inputParam) {
    return is_numeric($inputParam) && ctype_digit($inputParam) && $inputParam >= 1 && $inputParam <= 100;
}

function validateRPS($inputParam) {
    return is_numeric($inputParam) && ctype_digit($inputParam) && $inputParam >= 0 && $inputParam <= 2;
}

function rollDice() {
    return random_int(1, 6);
}

function playDiceGame() {
    $targetScore = 20;
    $currentScore = 0;

    echo "Гра \"Кидок кубика\". Мета - набрати 20 очок, не перевищивши їх.\n";
    echo "Початковий рахунок: $currentScore\n";

    while ($currentScore < $targetScore) {
        echo "Натисніть Enter, щоб зробити кидок...\n";
        fgets(STDIN);

        $diceRoll = rollDice();
        echo "Кидок: $diceRoll. ";

        if ($diceRoll === 6) {
            echo "Суперкидок! Отримуєш ще одну спробу.\n";
        } else {
            $currentScore += $diceRoll;
            echo "Загальний рахунок: $currentScore\n";
        }

        if ($currentScore === $targetScore) {
            echo "Вітаю! Ви досягли $targetScore очок і виграли гру!\n";
            return;
        } elseif ($currentScore > $targetScore) {
            echo "На жаль, ви перевищили $targetScore очок і програли гру.\n";
            return;
        }
    }
}

function mainMenu() {
    echo "Обери гру:\n[1] Вгадай число\n[2] Камінь, Ножиці, Папір\n[3] Калькулятор\n[4] Кидок кубика\n-> ";
    $choice = trim(fgets(STDIN));

    if ($choice === "1") {
        playGame(7);
    } elseif ($choice === "2") {
        playRockPaperScissors();
    } elseif ($choice === "3") {
        playCalculator();
    } elseif ($choice === "4") {
        playDiceGame();
    } else {
        echo "Некоректний вибір. Спробуй знову.\n";
        mainMenu();
    }
}

mainMenu();
