<?php


class Book{
    public static $db_name = "books";
    public $book_id;
    public $book_title;
    public $author;
    public $book_type_id;
    public $book_img;
    public $isbn_number;
    public $price;
    public $detail;
    public $place_id;
    public $shelf_id;
    public $create_user_id;



    /**
     * @return mixed
     */
    public function getBookTypeId()
    {
        return $this->book_type_id;
    }

    /**
     * @param mixed $book_type_id
     */
    public function setBookTypeId($book_type_id): void
    {
        $this->book_type_id = $book_type_id;
    }


    /**
     * @return mixed
     */
    public function getBookId()
    {
        return $this->book_id;
    }

    /**
     * @param mixed $book_id
     */
    public function setBookId($book_id): void
    {
        $this->book_id = $book_id;
    }

    /**
     * @return mixed
     */
    public function getBookTitle()
    {
        return $this->book_title;
    }

    /**
     * @param mixed $book_title
     */
    public function setBookTitle($book_title): void
    {
        $this->book_title = $book_title;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author): void
    {
        $this->author = $author;
    }



    /**
     * @return mixed
     */
    public function getBookImg()
    {
        return $this->book_img;
    }

    /**
     * @param mixed $book_img
     */
    public function setBookImg($book_img): void
    {
        $this->book_img = $book_img;
    }

    /**
     * @return mixed
     */
    public function getIsbnNumber()
    {
        return $this->isbn_number;
    }

    /**
     * @param mixed $isbn_number
     */
    public function setIsbnNumber($isbn_number): void
    {
        $this->isbn_number = $isbn_number;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @param mixed $detail
     */
    public function setDetail($detail): void
    {
        $this->detail = $detail;
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