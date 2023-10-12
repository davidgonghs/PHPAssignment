<?php


class Place{
    public static $db_name = "place";
    public static $Heap = "1";
    public $place_id;
    public $place_name;
    public $create_user_id;



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
    /**
     * @return mixed
     */
    public function getPlaceId()
    {
        return $this->place_id;
    }

    /**
     * @param mixed $place_id
     */
    public function setPlaceId($place_id): void
    {
        $this->place_id = $place_id;
    }

    /**
     * @return mixed
     */
    public function getPlaceName()
    {
        return $this->place_name;
    }

    /**
     * @param mixed $place_name
     */
    public function setPlaceName($place_name): void
    {
        $this->place_name = $place_name;
    }




}