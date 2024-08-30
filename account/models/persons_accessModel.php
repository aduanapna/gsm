<?php

class persons_accessModel extends Model
{
    public $profile_person_id;
    public $profile_administrator      = 0;
    public $profile_store_id;

    public $profile_column;

    public function add()
    {
        $sql    = "INSERT INTO `persons_access` SET ";
        $params =
            [
                'profile_person_id'          => $this->profile_person_id,
                'profile_store_id'           => $this->profile_store_id,
            ];
        $sql    = generate_bind($sql, $params);
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function update()
    {
        $sql    = "UPDATE `persons_access` SET ";
        $params =
            [
                'profile_person_id'         => $this->profile_person_id,
                'profile_administrator'     => $this->profile_administrator,
                'profile_store_id'          => $this->profile_store_id,
            ];
        $sql    = generate_bind($sql, $params);
        $sql = $sql . " WHERE profile_person_id=:profile_person_id AND profile_store_id=:profile_store_id";

        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function delete()
    {
        $sql    = "DELETE FROM `persons_access` WHERE profile_person_id=:profile_person_id AND profile_store_id=:profile_store_id LIMIT 1";
        $params = ['profile_person_id' => $this->profile_person_id, 'profile_store_id'  => $this->profile_store_id];
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Listado de perfiles que no sea administrador */
    public function not_all()
    {
        $sql    = "SELECT * FROM `persons_profile` WHERE person_profile_index!=1 AND person_profile_condition=1";
        try {
            return (parent::query($sql));
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Metodo global para consultar si la persona consultada tiene acceso al profile */
    public function check_person()
    {
        $sql    = "SELECT $this->profile_column, store_name, person_profile_id,person_profile_text FROM `persons_access`
        INNER JOIN `stores` ON store_id = profile_store_id 
        INNER JOIN `persons_profile` ON person_profile_id =:profile_column 
        WHERE profile_person_id=:profile_person_id AND profile_store_id=:profile_store_id LIMIT 1";
        $params =
            [
                'profile_person_id' => $this->profile_person_id,
                'profile_store_id'  => $this->profile_store_id,
                'profile_column'    => $this->profile_column,
            ];
        try {
            return (parent::query($sql, $params, true));
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Metodo para comprobar si el usuario tiene permisos */
    public function check_rol()
    {
        $sql    = "SELECT person_profile_index FROM `persons_profile` WHERE person_profile_id=:person_profile_id LIMIT 1";
        $params = ['person_profile_id' => $this->profile_column];
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Listado de personas segun perfil  */
    public function all_profiles()
    {
        $sql = "SELECT person_id,person_name,person_lastname,person_picture,person_document
        FROM `persons_access` 
        INNER JOIN `persons` ON person_id=profile_person_id
        WHERE $this->profile_column=1 AND profile_store_id=:profile_store_id";
        $params = ['profile_store_id' => $this->profile_store_id];
        try {
            return (parent::query($sql, $params));
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function status_change($status_id, $profile_column)
    {
        $sql = "UPDATE `persons_access` SET $profile_column=:status_id WHERE profile_person_id=:profile_person_id AND profile_store_id=:profile_store_id";
        $params = [
            'status_id'             => $status_id,
            'profile_person_id'     => $this->profile_person_id,
            'profile_store_id'      => $this->profile_store_id
        ];
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function change_store($status_id, $profile_column)
    {
        $sql = "UPDATE `persons_access` SET $profile_column=:status_id WHERE profile_person_id=:profile_person_id";
        $params = [
            'status_id'             => $status_id,
            'profile_person_id'     => $this->profile_person_id,
        ];
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function pages()
    {
        try {
            $rows = profiles[$this->profile_column];

            return $rows;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
