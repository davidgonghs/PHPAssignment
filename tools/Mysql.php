<?php
/**
 * Created by DavidGong.
 * Date: 2021/2/5
 * Time: 20:22
 */




class Mysql {
    private $is_log = true;//开启日志
    private $hlog ;//日志路径
    private $host = "localhost";
    private $port = "3306";
    private $user = "root";
    private $pass = "";
    private $db = "awd_assignment";
    private $charset = "utf8";
    private $link;

   public function __construct(){
        if ($this->is_log){
            $handle = fopen("log.txt","a+");
            $this->hlog = $handle;
        }
       $this->db_connect();//连接数据库
       $this->db_usedb();//选择数据库
       $this->db_charset();//设置字符集
   }

    //连接数据库
    private function db_connect(){
        $this->link=mysqli_connect($this->host,$this->user,$this->pass);
        if(!$this->link){
            $msg = "连接数据库失败：".mysqli_error($this->link);
            $this->write_log($msg);
            die($msg);
        }
    }
    //设置字符集
    private function db_charset(){
        mysqli_query($this->link,"set names {$this->charset}");
    }

    //选择数据库
    private function db_usedb(){
        mysqli_query($this->link,"use {$this->db}");
    }

    //私有的克隆
    private function __clone(){
        die('clone is not allowed');
    }



    //执行语句
    public function query($sql){
        $result = mysqli_query($this->link,$sql);
        if (!$result) {
            $this->write_log('mysql_query error:'."   ".$sql."  ".mysqli_error($this->link));
        } else {
            $this->write_log('执行语句：'.$sql.' 且执行成功');
        }
        return $result;
    }



    //返回受影响行数
    public function affected_num(){
        $num = mysqli_affected_rows($this->link);
        return $num;
    }

    //写入日志
    public function write_log($msg=''){
        if ($this->is_log){
            date_default_timezone_set('Asia/Shanghai');
            $text = date("Y-m-d H:i:s")." ".$msg."\r\n";
            fwrite($this->hlog,$text);
        }
    }

    //关闭数据库连接
    public function close(){
        mysqli_close($this->link);
    }

    //析构函数
    public function __destruct(){
        if($this->is_log){
            fclose($this->hlog);
        }
    }

    public function checkCondition($condition = ''){

        if(is_object($condition) ||  is_array($condition)){
            $where = "";
            foreach ($condition as $k => $v) {
                if($v != '' && !empty($v)){
                    $where .= "and ".$k."="."'$v'";
                }
            }
            $condition = substr($where, 3);
            $condition = "where ".$condition;
        }else if(is_string($condition) && $condition != ""){
            $condition = "where " . $condition;
        }else {
            $condition = '';
        }
        return $condition;
    }


    //插入一条数据
    public function insert($tab,$arr,$debug=false){
        $value = '';
        $column = '';
        foreach ($arr as $k => $v) {
            $column .= ",{$k}";
            $value .= ",'{$v}'";
        }
        $column = substr($column, 1);
        $value = substr($value, 1);

        $sql = "insert into $tab($column) values($value)";
        if ($debug) {
            echo '将执行语句：'.$sql;
        } else {
            $this->query($sql);
            $num = $this->affected_num();
            $this->write_log("受影响行数：".$num);
            return $num;    //返回受影响行数
        }
    }

    //获取总数
    public function count($tab,$condition="",$debug=false){
        $condition = $this->checkCondition($condition);
        $sql = "select count(*) from $tab $condition ";
        if ($debug) {
            echo '将执行语句：'.$sql.'<br />';
        } else {
            $result = $this->query($sql);
            $rows = mysqli_fetch_row($result);
            return $rows[0];
        }
    }


    //查询一条数据
    public function select_one($tab,$column = "*",$condition = '',$debug=false){
        $condition = $this->checkCondition($condition);
        if(empty($column) || $column == ''){
            $column = "*";
        }
        $sql = "select $column from $tab $condition ";
        if ($debug) {
            echo '将执行语句：'.$sql.'<br />';
        } else {
            $result = $this->query($sql);
            return $result;
        }
    }

    //查询多条数据
    public function select_more($tab,$column = "*",$condition = '',$page = "",$debug=False){
        $condition = $this->checkCondition($condition);

        if(empty($column) || $column == ''){
            $column = "*";
        }
        $sql = "select $column from $tab $condition $page";
        if ($debug) {
            echo '将执行语句：'.$sql.'<br />';
        } else {
            $this->write_log('将执行语句：'.$sql);
            $result = $this->query($sql);
            $i = 0;
            $rows = array();
            while ($row = mysqli_fetch_assoc($result)){
                $rows[$i] = $row;
                $i++;
            }
            return $rows;
        }
    }


    //更新数据
    public function update($tab,$arr,$condition = '',$debug=False){
        $condition = $this->checkCondition($condition);

        $value = '';
        foreach ($arr as $k => $v){
            if($v != '' && !empty($v)){
                $value .= "{$k}='{$v}',";
            }
        }
        $value = substr($value,0,-1);

        $sql = "update $tab set $value $condition";
        if ($debug) {
            echo '将执行语句：'.$sql;
        } else {
            $this->query($sql);
            $num = $this->affected_num();
            $this->write_log("受影响行数：".$num);
            return $num;
        }
    }


    //删除数据
    public function delete($tab,$condition='',$debug=False){
        $condition = $this->checkCondition($condition);
        $sql = "delete from $tab $condition";
        if ($debug) {
            echo '将执行语句：'.$sql;
        } else {
            $this->query($sql);
            $num = $this->affected_num();
            $this->write_log("受影响行数：".$num);
            return $num;    //返回受影响行数
        }
    }


}

?>