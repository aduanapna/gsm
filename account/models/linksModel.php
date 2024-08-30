<?php

class linksModel extends Model
{
    public $link_id;
    public $link_page;
    public $link_type;
    public $link_category;
    public $link_picture;
    public $link_name;
    public $link_description;
    public $link_class;
    public $link_style;
    public $link_icon;
    public $link_href;
    public $link_orderby;
    public $link_condition;

    public function add()
    {
        $sql    = "INSERT INTO `links` SET ";
        $params =
            [
                'link_page'         => $this->link_page,
                'link_type'         => $this->link_type,
                'link_category'     => $this->link_category,
                'link_picture'      => $this->link_picture,
                'link_name'         => $this->link_name,
                'link_description'  => $this->link_description,
                'link_class'        => $this->link_class,
                'link_style'        => $this->link_style,
                'link_icon'         => $this->link_icon,
                'link_href'         => $this->link_href,
                'link_orderby'      => $this->link_orderby,
                'link_condition'    => $this->link_condition,
            ];
        $sql    = generate_bind($sql, $params);
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update()
    {
        $sql    = "UPDATE `links` SET ";
        $params =
            [
                'link_id'           => $this->link_id,
                'link_page'         => $this->link_page,
                'link_type'         => $this->link_type,
                'link_category'     => $this->link_category,
                'link_picture'      => $this->link_picture,
                'link_name'         => $this->link_name,
                'link_description'  => $this->link_description,
                'link_class'        => $this->link_class,
                'link_style'        => $this->link_style,
                'link_icon'         => $this->link_icon,
                'link_href'         => $this->link_href,
                'link_orderby'      => $this->link_orderby,
                'link_condition'    => $this->link_condition,
            ];
        $sql    = generate_bind($sql, $params);
        $sql    = $sql . " WHERE link_id=:link_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function one()
    {
        $sql = "SELECT * FROM `links` WHERE link_id=:link_id LIMIT 1";
        $params = ['link_id' => $this->link_id];

        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function all()
    {
        $sql = "SELECT * FROM `links` WHERE link_condition IS NOT null ORDER BY link_type,link_orderby ASC";
        $params = [];

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function all_method()
    {
        $sql = "SELECT * FROM `links` WHERE link_condition = 1 AND link_page=:link_page ORDER BY link_orderby ASC";
        $params = ['link_page' => $this->link_page];

        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function change()
    {
        $sql    = "UPDATE `links` SET link_condition=:link_condition WHERE link_id=:link_id";
        $params = ['link_condition' => $this->link_condition, 'link_id' => $this->link_id];
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function delete()
    {
        $sql    = "UPDATE `links` SET link_condition=null WHERE link_id=:link_id";
        $params = ['link_id' => $this->link_id];
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
