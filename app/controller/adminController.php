<?php


require_once plugin_dir_path(__DIR__) . "model/adminModel.php";
// require_once plugin_dir_path(__DIR__) . "controller/getproductController.php";

class GpAaAdminController
{
    private $db;
    public function __construct()
    {
        $this->db = new adminModel;
    }

    public function Active()
    {
        $a = new adminModel;
        $a->Active();
    }
    public function Desactive()
    {
        flush_rewrite_rules();
    }

    public function CreateMenu($urlFile)
    {
        add_menu_page(
            'With Api Amazon add product',
            'Api Amazon add product',
            'manage_options',
            $urlFile,
            null,
            plugin_dir_url(__FILE__) . '../../public/assets/img/icon.png',
            '100'
        );
       }
}
