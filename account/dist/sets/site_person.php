<?php

/** Consulta y formatea persona */
function public_person($person_id)
{
    $send_person            = [];
    $persons                = new personsModel();
    $persons->person_id     = $person_id;
    $person_data            = $persons->one();

    if ($person_data != []) {
        $send_person['person_id']           = $person_data['person_id'];
        $send_person['person_name']         = ucwords($person_data['person_name']);
        $send_person['person_lastname']     = ucwords($person_data['person_lastname']);
        $send_person['person_document']     = $person_data['person_document'];
        $send_person['person_picture']      = IMAGES . $person_data['person_picture'];
        $send_person['person_condition']    = $person_data['person_condition'];
    }

    return $send_person;
}
/** Consulta y formatea perfiles */
function public_profiles($store_id, $profile)
{
    $profiles                   = new persons_accessModel();
    $profiles->profile_store_id = $store_id;
    $profiles->profile_column   = $profile;
    $profiles                   = $profiles->all_profiles();

    return $profiles;
}
/** Consulta y formatea perfiles */
function present_staff($store_id)
{
    $detect             = new Mobile_Detect;
    $persons            = new personsModel();
    $persons->store_id  = $store_id;
    $persons_data       = $persons->present_staff();

    if ($persons_data != []) {
        foreach ($persons_data as $ix_p => $vl_p) {
            $persons_data[$ix_p]['person_name']         = ucwords($vl_p['person_name']);
            $persons_data[$ix_p]['person_lastname']     = ucwords($vl_p['person_lastname']);
            $persons_data[$ix_p]['person_picture']      = IMAGES . $vl_p['person_picture'];
            $persons_data[$ix_p]['income_entry_agent']  = $detect->isMobile($vl_p['income_entry_agent']) ? ($detect->isTablet($vl_p['income_entry_agent']) ? 'tablet' : 'phone') : 'computer';
        }
    }
    return $persons_data;
}
/** Devuelve imagen predeterminada segun genero */
function person_picture($person_gender)
{
    switch ($person_gender) {
        case '1':
            $person_picture = '_nodisponible.jpg';
            break;
        case '2':
            $person_picture = '_nodisponible.jpg';
            break;
        case '3':
            $person_picture = '_nodisponible.jpg';
            break;
    }
    return $person_picture;
}
/** Cambia estado de la persona */
function person_condition($person_id, $person_condition)
{
    $person                        = new personsModel();
    $person->person_id             = $person_id;
    $person->person_condition      = $person_condition;
    $person->person_lastedit       = now();

    $person->condition_change();
}
/** Trae todos los datos de una persona */
function person_one($person_id)
{
    $persons            = new personsModel();
    $persons->person_id = $person_id;
    $persons_data       = $persons->one();

    return $persons_data;
}
