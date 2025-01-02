<?php

// 去資料庫建一個db99的資料庫，要複製school的classes，點classes再去操作頁面，copy table to (database.table)，直接執行即可

session_start();

class DB{
    protected $dsn="mysql:host=localhost;charset=utf8;dbname=db14-2";
    protected $pdo;
    protected $table;

    // 建構式construct，    指定運算子=　　，this=DB
    function __construct($table){
        $this->table=$table;
        $this->pdo=new PDO($this->dsn,'root','');
    }

    // 我們把,$dept=$DEPT->q("SELECT * FROM classes")做成function
    // 在做前construct，已經先被執行，$pdo 跟 $table 已被指定，所以我們可以新建一個All從中取得資料
    /**
     * 撈出全部資料(=多筆資料 where)
     * 1. 整張資料表
     * 2. 有條件
     * 3. 其他SQL功能
    */
    function all(...$arg){
        $sql="SELECT * FROM $this->table ";
        if(!empty($arg[0])){
            if(is_array($arg[0])){

                $where=$this->a2s($arg[0]);
                $sql=$sql . " WHERE ". join(" && ",$where);
            }else{
                //$sql=$sql.$arg[0];
                $sql .= $arg[0];
            }
        }

        if(!empty($arg[1])){
            $sql=$sql . $arg[1];
        }

        return $this->fetchALL($sql);
    }


    function find($id){
        $sql="SELECT * FROM $this->table ";
      
            if(is_array($id)){
                $where=$this->a2s($id);
                $sql=$sql . " WHERE ". join(" && ",$where);
            }else{
                $sql .= " WHERE `id` ='$id' ";
            }   

            return $this->fetchOne($sql);
        }

    
    function save($array){

        if(isset($array['id'])){
            //update
            //update table set `欄位1`='值1',`欄位2`='值2' where `id`='值' 
            $id=$array['id'];
            unset($array['id']);
            $set=$this->a2s($array);
            $sql ="UPDATE $this->table SET ".join(',',$set)." where `id`='$id'";

            }else{
                //insert
                $cols=array_keys($array);
                $sql="INSERT INTO $this->table (`".join("`,`",$cols)."`) VALUES('".join("','",$array)."')";
                // return $this->pdo->exec($sql);
            }
            //  echo $sql;
            return $this->pdo->exec($sql);
        }

    function del($id){
        $sql="DELETE FROM $this->table ";
      
            if(is_array($id)){
                $where=$this->a2s($id);
                $sql=$sql . " WHERE ". join(" && ",$where);
            }else{
                $sql .= " WHERE `id` ='$id' ";
            }   

            return $this->pdo->exec($sql);
        }


    // update table set[ a=>1,b=>2,]
    /**
    * 把陣列轉成條件字串陣列
    */
    function a2s($array){
        $tmp=[];
        foreach($array as $key => $value){
            $tmp[]="`$key`='$value'";
        }
        return $tmp;
    }


    function max($col,$where=[]){
        return $this->math('max',$col,$where);
    }
    function sum($col,$where=[]){
        return $this->math('sum',$col,$where);
    }
    function min($col,$where=[]){
        return $this->math('min',$col,$where);
    }
    function avg($col,$where=[]){
        return $this->avg('avg',$col,$where);
    }
    function count($where=[]){
        return $this->math('count','*',$where);
    }

/**
* 取得單筆資料
*/
protected function fetchOne($sql){
    // echo sql;  function 也可以簡寫取成FO或FA，但是不能用數字
    return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
}

/**
* 取得多筆資料
*/
protected function fetchALL($sql){
    // echo sql;
    return $this->pdo->query($sql)->fetchALL(PDO::FETCH_ASSOC);
}

// 從資料庫取出來的是 陣列['sum(`id`)'=>55]，如果要取用陣列的值，用$a['sum(`id`)'] 或 $a[0]
    // ['sum(`id`)'=>55]

    /**
    * 方便使用各個聚合函式
    */

    protected function math($math,$col='id',$where=[]){
        $sql="SELECT $math($col) FROM $this->table";

        if(!empty($where)){
            $tmp=$this->a2s($where);
            $sql=$sql . " WHERE " . join(" && ", $tmp);
        }

        return $this->pdo->query($sql)->fetchColumn();
    }
}


function q($sql){
    $pdo=new PDO ("mysql:host=localhost;charset=utf8;dbname=db14-2",'root','');
        return $pdo->query($sql)->fetchAll();
    }

function dd($array){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function to($url){
    header("location:".$url);
}


$Total=new DB('total');
$User=new DB('users');
$News=new DB('news');






if(!isset($_SESSION['view'])){
    if($Total->count(['date'=>date("Y-m-d")])>0){
        $total=$Total->find(['date'=>date("Y-m-d")]);
        $total['total']++;
        $Total->save($total);
    }else{
        $Total->save(['date'=>date("Y-m-d"),'total'=>1]);
    }
    $_SESSION['view']=1;
}


// ---------------------------------------------

// 用SESSION記錄訪客瀏覽，如果他用同個瀏覽器瀏覽，沒有關掉的話，都算一次，才不會每更換一個頁面就重複計算人數

// if(!isset($_SESSION['view'])){
//     echo "Hi~ 歡迎第一次來訪~ (○︎´∀︎`○︎)ﾉ";
//     $_SESSION['view']=1;
// }else{
//     echo "Hi~ 歡迎再次來訪~ (⑅˃◡˂⑅)ﾉ";
// }

// ---------------------------------------------

// 你要抓的資料庫為(classes)，所以要注意抓的地方是哪裡
// new DB 在做實體化，把藍圖的功能實體化
// $DEPT=new DB('classes');
// 老師用dept把classes改為dept

// $DEPT=new DB('dept');


// // $dept=$DEPT->q("SELECT * FROM classes");
// // 原本的程式碼如上，我們新增一個function all()，類別內的方法，來完成資料庫存取
// // 取代$dept=$DEPT->q("SELECT * FROM classes")這段程式，使其更精簡


// // $dept=$DEPT->all(" order by `id` DESC");
// $dept=$DEPT->find(['code'=>'404']);
// // $DEPT->del(['code'=>'504']);
// // $DEPT->save(['code'=>'504','id'=>'7','name'=>'資訊發展部']);
// // dd($dept);

// // echo $DEPT->math('max','id',['code'=>'503']);
// echo "<br>";
// echo $DEPT->max('id',['code'=>'503']);
// echo "<br>";
// echo $DEPT->count(['code'=>'503']);
// echo "<br>";
// echo $DEPT->count();