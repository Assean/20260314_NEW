<?php 
session_start();
$games=[];
$handle=opendir('games');
if($handle){
    while(false !== ($entry=readdir($handle))){
        if($entry != "." && $entry != ".."){
            $json_path="games/".$entry."/game.json";
            if(file_exists($json_path)){
                $data=json_decode(file_get_contents($json_path),true);
                $data['path']="games/".$entry."/index.html";
                $games[]=$data;
            }
        }
    }
    closedir($handle);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FunTech 社群網站</title>
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/bootstrap.css">
    <script src="./js/bootstrap.js"></script>
    <script src="./js/jqueryv3.7.1.js"></script>
</head>
<body>
    <?php include_once("./include/header.php"); ?>
    <main id="main-content index-main" style="margin-bottom: 9%;">
        <div>
            <div>
                <span>遊戲狀態: <span id="GameSta"></span></span>
                <br>
                <span>遊戲玩家: <span id="Gameuser"></span></span>
                <br>
                <span>遊戲分數: <span id="Gamescore"></span></span>
            </div>
        </div>
        <table>
            <tr>
                <td>id</td>
                <td>標題</td>
                <td>描述</td>
                <td>功能區</td>
            </tr>
            <?php $i=0 ?>
            <?php foreach($games as $game){
                 ?>
                <tr>
                <td><?= ++$i ?></td>
                <td><?=$game['title'];?></td>
                <td><?= mb_strimwidth($game['description'],0,50,"...")?></td>
                <td><button onclick="openGame('<?=$game['path'];?>')">開始遊戲</button></td>
            </tr>
            <?php } ?>
        </table>
    </main>
    <?php include_once("./include/footer.php"); ?>
    <script>
        let actGame;
        let urlGame;
        let a;
        function openGame(title,path){
            actGame = path;
            urlGame = title;
            a=window.open(title,"openGame","width=700,height=700");
        }
        function receiveGameResult({score, status}){ 
            a.close();
            setTimeout(() => {
                const user = prompt(`恭喜完成！請輸入姓名以便儲存成績:`);
                if (confirm("是否再玩一次?")) {
                    openGame(urlGame,actGame)
                }
                $("#GameSta").text(status);
                $("#Gameuser").text(user);
                $("#Gamescore").text(score);

                $.post('./api/save_result.php', {
                        user: user,
                        score: score
                });
            }, 100);
        }
    </script>
</body>
</html>