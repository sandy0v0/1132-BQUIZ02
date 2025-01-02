<?php include_once "db.php";

// $acc=$_GET['acc'];

// echo $res=$User->count(['acc'=>$acc]);

// echo $User->count($_POST);

$chk=$User->count($_POST);
if($chk){
    echo $chk;
    $_SESSION['user']=$_POST['acc'];
}else{
    echo 0;
}
/*
會員登入: ajax資料傳入後台判斷
(O)如果有資料的話返回1,並導到首頁(主題內容頁面);
如果他是admin且帳號輸入正確,就導到後台管理頁面

(X)如果沒有資料返回0,帳號資料重新填寫;
依照返回的值(0),返回時-重製帳號及密碼資料,
決定要顯示什麼文字或畫面

if($res>0){
    echo 1;
}else{
    echo 0;
} */

?>