<?php
// 40 じゃんけんを作成しよう！
// 下記の要件を満たす「じゃんけんプログラム」を開発してください。
// 要件定義
// ・使用可能な手はグー、チョキ、パー
// ・勝ち負けは、通常のじゃんけん
// ・PHPファイルの実行はコマンドラインから。
// ご自身が自由に設計して、プログラムを書いてみましょう！
print "===40問目". PHP_EOL; // ← 自分が見やすくするため

//勝敗判定のための自分の手の値を定数に代入
const STONE = 0; 
const SCISSORS = 1; 
const PAPER = 2;

//自分の手をカタカナで表示するための配列
const HAND_TYPE = array(
  STONE => 'グー',
  SCISSORS => 'チョキ',
  PAPER => 'パー'
);

//ジャンケンの勝敗判定の計算結果0、1、2を定数に
const DRAW = 0;
const LOSE = 1;
const WIN = 2;

//ジャンケン結果判定の配列。計算結果の0は引き分け、1は負け、2は勝ち。
const PRINT_JUDGE = array(
  DRAW =>  '引き分け',
  LOSE => 'あなたの負け',
  WIN => 'あなたの勝ち'
);

//ジャンケンの継続回答の定数
const ANSWER_YES = 'yes';
const ANSWER_NO = 'no';
const ANSWER_TYPE = array(
  ANSWER_YES,
  ANSWER_NO
);

//ジャンケンを実行する
janken();
exit;

//標準入力、変数と配列の宣言と定義、バリデーションや他の処理をまとめた関数
function janken(){
  
  echo '0、1、2のいずれかを入力してください'.PHP_EOL.'0はグー、1はチョキ、2はパーです';
  
  // ”コンピューターの手のキー” と ”勝負判定に使用する値” を変数に代入
  $comHand = getComHand();

  //自分の手の入力とバリデーション
  $inputHand = inputHand();

  //自分とpcの手を表示
  handCall($inputHand,$comHand);

  //勝負の判定結果を変数に代入
  $result = judge($inputHand,$comHand);
  
  //勝負の結果を表示
  show($result);

  //引き分けの判定をするとき定数との比較にする
  if($result === DRAW){
    echo 'あいこなのでもう一度'.PHP_EOL;
    return janken();
  }

  //ジャンケンを継続するか確認フラグ
  $continueFlag = jankenContinue($result);

  //ジャンケンを継続する確認を受け取った後の処理
  if($continueFlag === true){
    return janken();
  } else {
    echo 'ジャンケンを終了します';
    return;
  }
}

//バリデーション。空と指示した入力値を満たさないものをfalse
function inputValidation($input){
  if($input === ''){
    return false; 
  }

  if(!array_key_exists($input,HAND_TYPE)){
    return false;
  }
  return true;
}

// 自分の手を入力する
function inputHand(){

  $input = trim(fgets(STDIN));
  
  $validation = inputValidation($input);
  if($validation === false){
    echo '入力ミスです。再度入力してください。';
    return inputHand();
  } 
  return $input;
}

//自分の手とｐｃの手を表示するための関数
function handCall($inputHand,$comHand){
 
  $yourHand = HAND_TYPE[$inputHand];
  echo PHP_EOL."あなたは {$yourHand} です".PHP_EOL;
  
  //pcの手の表示の配列はHAND_TYPEに統一
  $comHand = HAND_TYPE[$comHand];
  echo "pcは {$comHand} です".PHP_EOL;
}

//勝敗の判定と結果表示
function judge($inputHand,$comHand){
  $judgeCalc = (($inputHand - $comHand) + 3) % 3; 
  return $judgeCalc;
}

//コンピューターの手を決める
function getComHand(){
  return mt_rand(0,2);  
}

//結果を表示させる
function show($result){
  echo PRINT_JUDGE[$result].PHP_EOL;
}

//ジャンケンを継続するか確認する
function jankenContinue($result){
  
  //$result !== 0 をしておかないと、引き分けの時に入力処理が動いてしまう
  //引き分けの判定をするとき定数との比較にする
  if($result !== DRAW ){
    //3回目の修正 inputAnswer()の処理をこちらに移動した
    echo 'ジャンケンを続けますか？続けるときは yes 、続けないときは no を入力してください。';
    $answer = trim(fgets(STDIN));
    $answerCheck = answerValidation($answer);
    if($answerCheck === false){
      echo '入力ミスです。再度入力してください。'.PHP_EOL;
      return jankenContinue($result);
    }

    //janken()の再帰ではなくboolを返す
    switch($answer){
      case ANSWER_YES:
        return true;
      break;
  
      case ANSWER_NO:
        return false;
      break;

      //念のための処理
      default:
        return jankenContinue($result);
      break;
    }
  }
}

//$answer のバリデーション。空の場合と入力されるべき定数でなかった場合をfalseとした。
function answerValidation($answer){

  if($answer === ''){
    return false; 
  }

  if(!in_array($answer,ANSWER_TYPE)){
    return false;
  }
  return true;
}
