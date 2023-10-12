<?php

/**
 * 数据库表的属性名要相同
 * */
class BookType{
    public static $db_name = "book_type";
    public static $Nothing = "1";
    public $type_id;
    public $type_name;
    public $introduction;
    public $create_user_id;



    /**
     * @return mixed
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * @param mixed $type_id
     */
    public function setTypeId($type_id): void
    {
        $this->type_id = $type_id;
    }

    /**
     * @return mixed
     */
    public function getTypeName()
    {
        return $this->type_name;
    }

    /**
     * @param mixed $type_name
     */
    public function setTypeName($type_name): void
    {
        $this->type_name = $type_name;
    }

    /**
     * @return mixed
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * @param mixed $introduction
     */
    public function setIntroduction($introduction): void
    {
        $this->introduction = $introduction;
    }



    /**
     * @return mixed
     */
    public function getCreateUserId()
    {
        return $this->create_user_id;
    }

    /**
     * @param mixed $create_user_id
     */
    public function setCreateUserId($create_user_id): void
    {
        $this->create_user_id = $create_user_id;
    }

}
?>