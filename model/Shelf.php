<?php


class Shelf{
    public static $db_name = "Shelf";
    public static $Heap = "1";
    public $shelf_id;
    public $place_id;
    public $shelf_name;
    public $capacity;
    public $create_user_id;

    /**
     * @return mixed
     */
    public function getShelfId()
    {
        return $this->shelf_id;
    }

    /**
     * @param mixed $shelf_id
     */
    public function setShelfId($shelf_id): void
    {
        $this->shelf_id = $shelf_id;
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
    public function getShelfName()
    {
        return $this->shelf_name;
    }

    /**
     * @param mixed $shelf_name
     */
    public function setShelfName($shelf_name): void
    {
        $this->shelf_name = $shelf_name;
    }

    /**
     * @return mixed
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param mixed $capacity
     */
    public function setCapacity($capacity): void
    {
        $this->capacity = $capacity;
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