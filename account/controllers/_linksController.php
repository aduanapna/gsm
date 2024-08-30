<?php
class _linksController extends Controller
{
    public static $user;
    function __construct()
    {
        check_csrf();
        self::$user = get_user(var_gestion, 'gestion/login', true);
        #check_methods(CONTROLLER, METHOD, self::$user->profile);
    }
    function list()
    {
        try {
            $links          = new linksModel();
            $links_data     = $links->all();
            if ($links_data != []) {
                foreach ($links_data as $ix_link => $vl_link) {
                    $type       = $vl_link['link_type'];
                    $href       = $vl_link['link_href'];
                    $picture    = $vl_link['link_picture'];
                    if (strpos($href, 'http') !== 0) {
                        switch ($type) {
                            case 'button':
                                $links_data[$ix_link]['link_href'] = URL . $href;
                                break;
                            case 'image':
                                $links_data[$ix_link]['link_picture'] = IMAGES . $picture;
                                break;
                            case 'logo':
                                $links_data[$ix_link]['link_picture'] = IMAGES . $picture;
                                break;
                        }
                    }
                    $links_data[$ix_link]['link_condition'] = boolean_return($vl_link['link_condition']);
                    $links_data[$ix_link]['keywords']       = generate_keywords([$vl_link['link_type'], $vl_link['link_name']]);
                    $links_data[$ix_link]['spinner']        = false;
                }
            }
            # Retorno
            $send['pages']  = $links_data;

            json_response(200, $links_data);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function new()
    {
        try {
            $send['link_id']            = '';
            $send['link_page']          = '';
            $send['link_type']          = 'button';
            $send['link_category']      = '';
            $send['link_picture']       = IMAGES . '_logocompletotrans.png';
            $send['link_name']          = '';
            $send['link_description']   = '';
            $send['link_class']         = '';
            $send['link_style']         = '';
            $send['link_icon']          = '';
            $send['link_href']          = '';
            $send['link_orderby']       = 0;

            json_response(200, $send);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function add()
    {
        $forms                          = check_form();
        try {
            $links                      = new linksModel();
            $links->link_page           = get_form($forms, 'link_page', ['notnull']);
            $links->link_type           = get_form($forms, 'link_type', ['notnull']);
            $links->link_category       = get_form($forms, 'link_category', []);
            $links->link_picture        = get_form($forms, 'link_picture', ['image']);
            $links->link_name           = get_form($forms, 'link_name', ['notnull']);
            $links->link_description    = get_form($forms, 'link_description', []);
            $links->link_class          = get_form($forms, 'link_class', []);
            $links->link_style          = get_form($forms, 'link_style', []);
            $links->link_icon           = get_form($forms, 'link_icon', []);
            $links->link_href           = get_form($forms, 'link_href', []);
            $links->link_orderby        = get_form($forms, 'link_orderby', []);
            $links->link_condition      = 1;

            if ($links->link_type == 'button') {
                $links->link_picture = null;
            }
            $links->add();

            $this->list();
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function update()
    {
        $forms                          = check_form();
        try {
            $links                      = new linksModel();
            $links->link_id             = get_form($forms, 'link_id', ['notnull']);
            $links->link_page           = get_form($forms, 'link_page', ['notnull']);
            $links->link_type           = get_form($forms, 'link_type', ['notnull']);
            $links->link_category       = get_form($forms, 'link_category', []);
            $links->link_picture        = get_form($forms, 'link_picture', ['image']);
            $links->link_name           = get_form($forms, 'link_name', ['notnull']);
            $links->link_description    = get_form($forms, 'link_description', []);
            $links->link_class          = get_form($forms, 'link_class', []);
            $links->link_style          = get_form($forms, 'link_style', []);
            $links->link_icon           = get_form($forms, 'link_icon', []);
            $links->link_href           = get_form($forms, 'link_href', []);
            $links->link_orderby        = get_form($forms, 'link_orderby', []);
            $links->link_condition      = get_form($forms, 'link_condition', []);
            if ($links->link_type == 'button') {
                $links->link_picture = null;
            }
            $links->update();

            $this->list();
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function delete()
    {
        $forms              = check_form();
        try {
            $link_id        = get_form($forms, 'link_id', ['notnull']);

            $links          = new linksModel();
            $links->link_id = $link_id;
            $links->delete();

            $this->list();
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
    function change()
    {
        $forms                  = check_form();
        try {
            $link_id            = get_form($forms, 'link_id', ['notnull']);
            $link_condition     = get_form($forms, 'link_condition', ['notnull', 'boolean']);
            switch ($link_condition) {
                case 0:
                    $link_condition = 1;
                    break;
                case 1:
                    $link_condition = 0;
                    break;
            }
            $links = new linksModel();
            $links->link_id         = $link_id;
            $links->link_condition  = $link_condition;
            $links->change();

            $forms['link_condition']    = boolean_return($link_condition);
            $forms['spinner']           = false;

            json_response(200, $forms);
        } catch (Exception $e) {
            json_response(404, null, $e->getMessage());
        }
    }
}
