<?php

class _staffController extends Controller
{
    public static $user;
    function __construct()
    {
        check_csrf();
        self::$user = get_user(var_gestion, 'gestion/login', true);
        #check_methods(CONTROLLER, METHOD, self::$user->profile);
    }
    function new()
    {
        try {
            $data_person['person_id']           = '';
            $data_person['person_picture']      = IMAGES . person_picture(1);
            $data_person['person_name']         = '';
            $data_person['person_lastname']     = '';
            $data_person['person_document']     = 'd' . rand(1, 999999);
            $data_person['person_birthday']     = '1980-01-01';
            $data_person['person_gender']       = 3;
            $data_person['person_cellphone']    = '';
            $data_person['person_address']      = '';
            $data_person['person_city']         = '';
            $data_person['person_postalcode']   = '';
            $data_person['person_email']        = '';
            $data_person['person_created']      = date_js();
            $data_person['person_employee']     = 1;
            json_response(200, $data_person, 'Datos por defecto');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function add()
    {
        $forms = check_form();
        try {
            $person                             = new personsModel();
            # Cargamos todos los datos enviados por el usuario y preparamos todo para agregarlo a la base de datos
            $person->person_document            = get_form($forms, 'person_document', ['notnull']);
            $person->person_picture             = get_form($forms, 'person_picture', ['image']);
            $person->person_name                = get_form($forms, 'person_name', ['strtolower', 'notnull']);
            $person->person_lastname            = get_form($forms, 'person_lastname', ['strtolower', 'notascii']);
            $person->person_birthday            = get_form($forms, 'person_birthday', ['notnull'], date_js());
            $person->person_gender              = get_form($forms, 'person_gender', ['notnull'], '3');
            $person->person_cellphone           = get_form($forms, 'person_cellphone', ['cellphone']);
            $person->person_address             = get_form($forms, 'person_address', ['strtolower', 'notascii']);
            $person->person_zone                = 2;
            $person->person_city                = get_form($forms, 'person_city', ['strtolower', 'notascii']);
            $person->person_postalcode          = get_form($forms, 'person_postalcode', []);
            $person->person_email               = get_form($forms, 'person_email', []);
            $person->person_observation         = get_form($forms, 'person_observation', []);
            $person->person_pass                = hash_pass($person->person_document);
            $person->person_condition           = 1;
            $person->person_employee            = get_form($forms, 'person_employee', ['notnull'], 1);
            $person->person_vip                 = 0;
            $person->person_address_prefer      = 0;
            $person->person_store_prefer        = self::$user->store_id;
            $person->person_created             = now();
            # Con los datos pre cargados nos fijamos si el numero esta asociado.-
            $data_person                        = $person->check_telephone();
            if ($data_person != []) {
                json_response(400, null, 'Existe individuo con mismo celular. Contacte a soporte');
            }
            $data_person                    = $person->check_document();
            if ($data_person != []) {
                json_response(400, null, 'Existe individuo con mismo dni. Contacte a soporte');
            }
            $person_id                      = $person->add();

            $profiles                       = new persons_accessModel();
            $profiles->profile_person_id    = $person_id;
            $profiles->profile_store_id     = self::$user->store_id;
            $profiles->add();

            $this->list('Personal creado');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function update()
    {
        $forms = check_form();
        try {
            $person                         = new personsModel();
            $person->person_id              = get_form($forms, 'person_id', ['notnull']);
            # Cargamos todos los datos enviados por el usuario y preparamos todo para agregarlo a la base de datos
            $person->person_document        = get_form($forms, 'person_document', ['notnull']);
            $person->person_picture         = get_form($forms, 'person_picture', ['image']);
            $person->person_name            = get_form($forms, 'person_name', ['strtolower', 'notnull', 'notascii']);
            $person->person_lastname        = get_form($forms, 'person_lastname', ['strtolower', 'notascii']);
            $person->person_birthday        = get_form($forms, 'person_birthday', ['notnull'], date_js());
            $person->person_gender          = get_form($forms, 'person_gender', ['notnull'], '3');
            $person->person_address         = get_form($forms, 'person_address', ['strtolower', 'notascii']);
            $person->person_city            = get_form($forms, 'person_city', ['strtolower', 'notascii']);
            $person->person_postalcode      = get_form($forms, 'person_postalcode', []);
            $person->person_email           = get_form($forms, 'person_email', []);
            $person->person_employee        = get_form($forms, 'person_employee', ['notnull'], 1);
            $person->person_observation     = get_form($forms, 'person_observation', []);
            $person->person_lastedit        = now();

            $person->update();
            $this->list('Personal actualizado.');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function delete()
    {
        $forms = check_form();
        try {
            $person_id  = get_form($forms, 'person_id', ['notnull']);

            person_condition($person_id, 0);
            $this->list('Personal eliminado. Para restaurar debera contactar a soporte');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage() . ' pacienteController');
        }
    }
    function list($msg = null)
    {
        try {
            $person            = new personsModel();
            $person->store_id  = self::$user->store_id;
            $data_staff         = $person->all_staff();

            if ($data_staff != []) {
                foreach ($data_staff as $index => $row) {
                    $data_staff[$index]['person_name']      = $row['person_name'];
                    $data_staff[$index]['person_lastname']  = $row['person_lastname'];
                    $data_staff[$index]['person_picture']   = IMAGES . $row['person_picture'];
                    $data_staff[$index]['keywords']         = generate_keywords([$row['person_name'], $row['person_lastname'], $row['person_document'], $row['person_cellphone']]);
                    $data_staff[$index]['spinner']          = false;
                }
            }

            json_response(200, $data_staff, $msg);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function view()
    {
        $forms = check_form();
        try {
            $person                                = new personsModel();
            $person->person_id                     = get_form($forms, 'person_id', ['notnull']);
            $person_data                            = $person->one();

            if ($person_data != []) {
                $person_data['person_name']         = $person_data['person_name'];
                $person_data['person_lastname']     = $person_data['person_lastname'];
                $person_data['person_picture']      = IMAGES . $person_data['person_picture'];
                $person_data['keywords']            = generate_keywords([$person_data['person_name'], $person_data['person_lastname'], $person_data['person_document'], $person_data['person_cellphone']]);
                $person_data['spinner']             = false;
            }

            json_response(200, $person_data);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function profile()
    {
        $forms                                  = check_form();
        try {
            $profiles                           = new persons_accessModel();
            $profiles->profile_person_id        = get_form($forms, 'person_id', ['notnull']);
            $profiles->profile_store_id         = self::$user->store_id;
            # Cargamos todos los datos enviados por el usuario y preparamos todo para agregarlo a la base de datos
            $profiles->profile_administrator    = get_form($forms, 'profile_administrator', ['notnull']);
            $profiles->update();

            json_response(200, null, 'Perfiles de personal modificados');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function change_condition()
    {
        $forms = check_form();
        try {
            $person_condition   = get_form($forms, 'person_condition', ['notnull']);
            $person_id          = get_form($forms, 'person_id', ['notnull']);
            switch ($person_condition) {
                case '1':
                    $person_condition = '2';
                    break;
                case '2':
                    $person_condition = '1';
                    break;
            }

            $person                    = new personsModel();
            $person->person_id         = $person_id;
            $person->person_condition  = $person_condition;
            $person->condition_change();

            $this->view();
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function pass()
    {
        $forms                          = check_form();
        try {
            $person_id                  = get_form($forms, 'person_id', ['notnull']);
            $person_document            = get_form($forms, 'person_document', ['notnull']);

            $person                     = new personsModel();
            $person->person_id          = $person_id;
            $person->person_pass        = hash_pass($person_document);
            $person->person_lastedit    = now();

            $person->pass_update();
            json_response(200, null, 'Contraseña reseteada.');
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    # Funciones para administrar los profiles
    function profile_add($person_id)
    {
        # Funcion para agregar los profiles a la persona en todas las sucursales. Solo para personal
        $store                              = new storesModel();
        $data_store                         = $store->alls();

        $profiles                           = new persons_accessModel();
        foreach ($data_store as $store) {
            $profiles->profile_person_id     = $person_id;
            $profiles->profile_store_id      = $store['store_id'];
            $profiles->add();
        }
    }
    ### Privates
    private function lastlogin($person_id)
    {
        $customer                   = new personsModel();
        $customer->person_id        = $person_id;
        $customer->lastlogin();
    }
}
