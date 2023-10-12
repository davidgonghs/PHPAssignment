<?php

class Common{


    public function refreshPage($arr=""){
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
        if($arr!=""){
            $url.=$arr;
        }
        echo "<script>window.location.href='$url';</script>";
    }


}

?>
