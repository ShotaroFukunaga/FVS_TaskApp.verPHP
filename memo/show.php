
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
        echo getHeader("ユーザー登録");
    ?>
    <body>
<div class="col-9 h-100">
  <?php if(isset($_SESSION['select_memo'])): ?>
    <form class="w-100 h-100" method="post">
      <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>" />
      <div id="memo-menu">
          <button type="submit" class="btn btn-danger" formaction="./action/delete.php"><i class="fas fa-trash-alt"></i></button>
          <button type="submit" class="btn btn-success" formaction="./action/update.php"><i class="fas fa-save"></i></button>
        </div>
      <input type="text" id="memo-title" name="edit_title" placeholder="タイトルを入力する..." value="<?php echo $edit_title; ?>" />
      <textarea id="memo-content" name="edit_content" placeholder="内容を入力する..."><?php echo $edit_content; ?></textarea>
    </form>
  <?php else: ?>
  <div class="mt-3 alert alert-info">
    <i class="fas fa-info-circle"></i>メモを新規作成するか選択してください。
  </div>
  <?php endif; ?>
</div>

</body>
</html>