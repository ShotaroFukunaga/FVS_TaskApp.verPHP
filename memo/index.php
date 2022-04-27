<?php
  require '../common/auth.php';
  require '../common/database.php';

  if(!isLogin()){
    header('Location: ../login/');
    exit;
  }

  $user_name = getLoginUserName();
  $user_id = getLoginUserId();

  $memos = [];
  $database_handler = getDatabaseConnection();

  if(isset($_GET['search'])){//要修正！！動くだけ！！
    $search = "%".$_GET['search']."%";
    $sql = 'SELECT id, title, content, updated_at FROM memos WHERE  user_id = :user_id AND title LIKE :search ORDER BY updated_at DESC';
  }else{
    $sql = "SELECT id,title,content,updated_at FROM memos WHERE user_id = :user_id ORDER BY updated_at DESC";
  }
	if($statement = $database_handler->prepare($sql)){
		if(isset($_GET['search'])){$statement->bindParam(':search',$search);}
		$statement->bindParam(':user_id',$user_id);
		$statement->execute();
  }
  while($result = $statement->fetch(PDO::FETCH_ASSOC)){
    array_push($memos,$result);
  }

  $edit_id = "";
  if(isset($_SESSION['select_memo'])){
    $edit_memo = $_SESSION['select_memo'];
    $edit_id = empty($edit_memo['id'])?"":$edit_memo['id'];
    $edit_title = empty($edit_memo['title'])?"":$edit_memo['title'];
    $edit_content = empty($edit_memo['content'])?"":$edit_memo['content'];
  }


?>
<!DOCTYPE html>
<html lang="ja">
    <?php
        include_once "../common/header.php";
        echo getHeader("メモ投稿");
    ?>
  <body class="bg-white">
    <div class="h-100">
      <div class="row h-100 m-0 p-0">
        <div class="col-3 h-10 m-0 p-0 border-left border-right border-gray">
          <div class="left-memo-menu d-flex justify-content-center pt-2">
            <div class="pl-3 pt-2">
              <?php echo $user_name; ?>さん、こんにちは。
            </div>
            <div class="pr-1">
              <a href="./action/add.php" class="btn btn-success"><i class="fas fa-plus"></i></a>
              <a href="./action/logout.php" class="btn btn-dark"><i class="fas fa-sign-out-alt"></i></a>
            </div>
          </div>
          <div class="center-memo-title h3 pl-3 pt-3">
            メモリスト
          </div>
          
          <!-- 検索用ホーム -->
          <form method="get" action="">
            <input type="text" name="search" placeholder="検索" >
            <input type="submit">
          </form>
          <!--            -->

          <div class="left-memo-list list-group-flush p-0">
            <?php if(empty($memos)): ?>
              <div class="pl-3 pt-3 h5 text-info text-center">
                <i class="far fa-surprise"></i>メモがありません
              </div>
            <?php endif; ?>

            <?php foreach($memos as $memo): ?>
              <a href="./action/select.php?id=<?php echo $memo['id']; ?>" class="list-group-item list-group-item-action ">
              <!-- <div class="d-flex w-100 justify-content-between"> -->
                <h5 class="mb-1"><?php echo $memo["title"] ?></h5>
                <p><?php echo date('Y/m/d ', strtotime($memo['updated_at'])); ?></p>
              <!-- </div> -->
              <p class="mb-1">
                <?php
                  if (mb_strlen($memo['content']) <= 100) {
                    echo $memo['content'];
                  } else {
                    echo mb_substr($memo['content'], 0, 100) . "...";
                  }
                ?>
              </p>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>

