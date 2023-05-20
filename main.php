<?php

namespace blackJack;

use Exception;

require_once('BlackJack.php');

echo 'あなたの名前を入力してください' . PHP_EOL;

try {
    $input = trim(fgets(STDIN));

    if ($input !== '') {
        $blackJack = new BlackJack($input);
        $blackJack->start();
    } else {
        throw new Exception("あなたの名前が入力されなかったのでゲームを開始出来ませんでした");
    }
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage() . PHP_EOL;
}
