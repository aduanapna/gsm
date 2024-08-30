<?php

class personsModel extends Model
{
    public $person_id;
    public $person_name;
    public $person_lastname;
    public $person_pass;
    public $person_document;
    public $person_birthday;
    public $person_gender;
    public $person_picture;
    public $person_cellphone;
    public $person_address;
    public $person_zone;
    public $person_city;
    public $person_postalcode;
    public $person_email;
    public $person_observation;
    public $person_condition;
    public $person_wallet;
    public $person_created;
    public $person_lastedit;
    public $person_lastaccess;
    public $person_lastorder;
    public $person_vip;
    public $person_address_prefer;
    public $person_store_prefer;
    public $person_employee;


    # Variables especificas
    public $store_id;
    public $fecha_desde;
    public $fecha_hasta;
    public $queried_column;

    public function add()
    {
        $sql    = "INSERT INTO `persons` SET ";
        $params =
            [
                'person_name'               => $this->person_name,
                'person_lastname'           => $this->person_lastname,
                'person_pass'               => $this->person_pass,
                'person_document'           => $this->person_document,
                'person_birthday'           => $this->person_birthday,
                'person_gender'             => $this->person_gender,
                'person_picture'            => $this->person_picture,
                'person_cellphone'          => $this->person_cellphone,
                'person_address'            => $this->person_address,
                'person_zone'               => $this->person_zone,
                'person_city'               => $this->person_city,
                'person_postalcode'         => $this->person_postalcode,
                'person_employee'           => $this->person_employee,
                'person_email'              => $this->person_email,
                'person_created'            => $this->person_created,
                'person_condition'          => $this->person_condition,
                'person_vip'                => $this->person_vip,
                'person_observation'        => $this->person_observation,
                'person_address_prefer'     => $this->person_address_prefer,
                'person_store_prefer'       => $this->person_store_prefer,
            ];
        $sql    = generate_bind($sql, $params);
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function add_fast()
    {
        $sql    = "INSERT INTO `persons` SET ";
        $params =
            [
                'person_name'               => $this->person_name,
                'person_address'            => $this->person_address,
                'person_zone'               => $this->person_zone,
                'person_document'           => $this->person_document,
                'person_birthday'           => $this->person_birthday,
                'person_gender'             => $this->person_gender,
                'person_picture'            => $this->person_picture,
                'person_cellphone'          => $this->person_cellphone,
                'person_created'            => $this->person_created,
                'person_condition'          => $this->person_condition,
                'person_vip'                => $this->person_vip,
                'person_store_prefer'       => $this->person_store_prefer,
                'person_employee'           => $this->person_employee,
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
        $sql    = "UPDATE `persons` SET ";
        $params =
            [
                'person_id'                 => $this->person_id,
                'person_name'               => $this->person_name,
                'person_lastname'           => $this->person_lastname,
                'person_document'           => $this->person_document,
                'person_birthday'           => $this->person_birthday,
                'person_gender'             => $this->person_gender,
                'person_picture'            => $this->person_picture,
                'person_address'            => $this->person_address,
                'person_city'               => $this->person_city,
                'person_postalcode'         => $this->person_postalcode,
                'person_email'              => $this->person_email,
                'person_email'              => $this->person_email,
                'person_lastedit'           => $this->person_lastedit,
                'person_observation'        => $this->person_observation,
            ];
        $sql    = generate_bind($sql, $params);
        $sql    = $sql . " WHERE person_id=:person_id";

        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function update_fast()
    {
        $sql    = "UPDATE `persons` SET ";
        $params =
            [
                'person_id'                 => $this->person_id,
                'person_name'               => $this->person_name,
                'person_birthday'           => $this->person_birthday,
                'person_address'            => $this->person_address,
                'person_zone'               => $this->person_zone,
            ];
        $sql    = generate_bind($sql, $params);
        $sql    = $sql . " WHERE person_id=:person_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function update_vip()
    {
        $sql    = "UPDATE `persons` SET ";
        $params = ['person_id' => $this->person_id, 'person_vip' => $this->person_vip, 'person_zone' => $this->person_zone,];
        $sql    = generate_bind($sql, $params);
        $sql    = $sql . " WHERE person_id=:person_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function delete()
    {
        $sql    = "DELETE FROM `persons` WHERE person_id=:person_id LIMIT 1";
        $params = ['person_id' => $this->person_id];
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function one()
    {
        # En la misma consulta se puede buscar por id, documento o telefono
        $sql    = "SELECT * FROM `persons` 
        INNER JOIN `persons_condition` ON person_condition_id = person_condition
        INNER JOIN `stores` ON store_id = person_store_prefer
        WHERE person_id=:person_id 
        OR person_document=:person_id 
        OR person_cellphone=:person_id 
        LIMIT 1";

        $params = ['person_id' => $this->person_id];

        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function all()
    {
        $sql = "SELECT person_id,person_name,person_lastname,person_picture,person_document,person_cellphone,person_address,person_vip,person_condition,person_condition_color FROM `persons` 
        INNER JOIN `persons_condition` ON person_condition_id=person_condition
        WHERE person_condition >= 1 
        ORDER BY person_lastaccess desc,person_lastedit desc LIMIT 2000";

        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function all_customers()
    {
        $sql    = "SELECT person_id,person_name,person_lastname,person_picture,person_document,person_cellphone,person_address,person_vip,person_condition,person_condition_color, 
        DATE_FORMAT(person_lastaccess, '%d/%m/%Y %H:%i') AS person_lastaccess 
        FROM `persons` 
        INNER JOIN `persons_condition` ON person_condition_id=person_condition
        WHERE person_condition >= 1 AND person_store_prefer=:store_id AND person_employee=0
        ORDER BY person_lastaccess desc,person_lastedit desc ";
        $params = ['store_id' => $this->store_id];
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Setea condicion y agrega la ultimo edicion */
    public function condition_change()
    {
        $sql = "UPDATE `persons` SET 
        person_condition=:person_condition, 
        person_lastedit=:person_lastedit 
        WHERE person_id=:person_id";
        $params =
            [
                'person_id'         => $this->person_id,
                'person_condition'  => $this->person_condition,
                'person_lastedit'   => $this->person_lastedit
            ];
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Actualizar password de la persona */
    public function pass_update()
    {
        $sql    = "UPDATE `persons` SET ";
        $params = ['person_pass' => $this->person_pass, 'person_id' => $this->person_id, 'person_lastedit' => $this->person_lastedit];
        $sql    = generate_bind($sql, $params);
        $sql    = $sql . " WHERE person_id=:person_id";
        try {
            return (parent::query($sql, $params)) ? true : false;
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Busca la persona por documento */
    public function check_document()
    {
        $sql = "SELECT person_id FROM `persons` WHERE person_document=:person_document LIMIT 1";
        $params = ['person_document' => $this->person_document];
        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Busca la persona por telefono */
    public function check_telephone()
    {
        $sql    = "SELECT person_id FROM `persons` WHERE person_cellphone=:person_cellphone LIMIT 1";
        $params = ['person_cellphone' => $this->person_cellphone];
        try {
            return parent::query($sql, $params, true);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Funcion para guardar el ultimo login de la persona */
    public function lastlogin()
    {
        $sql    = "UPDATE `persons` SET person_lastaccess=:person_lastaccess WHERE person_id=:person_id";
        $params = ['person_lastaccess' => now(), 'person_id' => $this->person_id];
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    /** Funcion para guardar el ultimo acceso a ordenar de la persona */
    public function lastaccess()
    {
        $sql    = "UPDATE `persons` SET person_lastaccess=:person_lastaccess WHERE person_id=:person_id";
        $params = ['person_lastaccess' => now(), 'person_id' => $this->person_id];
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    # Funciones Personal
    public function all_staff()
    {
        $sql    = "SELECT person_id,person_name,person_lastname,person_picture,person_document,person_cellphone,person_employee,person_condition,person_condition_color,DATE_FORMAT(person_lastaccess, '%d/%m/%Y %H:%i') AS person_lastaccess,
        persons_access.*
        FROM `persons` 
        INNER JOIN `persons_condition` ON person_condition_id=person_condition
        INNER JOIN `persons_access` ON profile_person_id=person_id
        WHERE person_condition >= 1 AND profile_store_id=:store_id AND person_employee!=0
        ORDER BY person_lastaccess desc,person_lastedit desc";
        $params = ['store_id' => $this->store_id];
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function present_staff()
    {
        $sql    = "SELECT person_id,person_name,person_lastname,person_picture,person_document,person_condition,
        DATE_FORMAT(income_entry_date, '%d/%m/%Y %H:%i') AS income_entry_date,income_entry_ip,income_entry_agent
        FROM `persons` 
        INNER JOIN `incomes` ON income_person_id=person_id
        WHERE income_condition = 1 AND income_entry_store=:store_id 
        ORDER BY income_entry_date desc";
        $params = ['store_id' => $this->store_id];
        try {
            return parent::query($sql, $params);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function empleados()
    {
        $sql    = "SELECT er.id,em.dni,em.ncompleto,em.sucursal,er.estado,er.hora_ing,er.hora_egr FROM empleados_registros AS er 
        INNER JOIN empleados AS em ON em.id=er.idempleado
        ORDER BY er.hora_ing asc";
        $params = [];
        try {
            return parent::query($sql);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
