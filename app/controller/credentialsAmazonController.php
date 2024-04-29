<?php
// require_once plugin_dir_path(__DIR__) . "model/adminModel.php";
require_once plugin_dir_path(__DIR__) . "controller/getproductController.php";

class GpAaCredentialsAmazonController
{
    // private $db;
    private $table_store = "gpaacredentialsamazon";

    public function __construct()
    {
        // $this->db = new adminModel;

    }

    public function SaveDataCredentialsAmazonId()
    {

        global $wpdb;
        parse_str($_POST["data"], $r);
     
        if (!empty($r['id'])) {
            $id = $r['id'];
            $query = "SELECT id FROM {$wpdb->prefix}{$this->table_store}  WHERE id = $id";
            $result = $wpdb->get_results($query,  ARRAY_A)[0]["id"];
            $wpdb->update("{$wpdb->prefix}{$this->table_store}", $r["credentials"], ['id' => $id]);
        } else {
            $wpdb->insert("{$wpdb->prefix}{$this->table_store}", $r["credentials"]);
        }

        return json_encode("Update");
    }



    public function GetCredentialsAmazonIds()
    {
        global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}{$this->table_store}";
        $result = $wpdb->get_results($query,  ARRAY_A);
        return json_encode($result[0]);
    }

    public function GetCredentialsAmazonId($id = '')
    {
        global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}{$this->table_store}";
        $result = $wpdb->get_results($query,  ARRAY_A);
        // if (count($result) > 0) {
            return $result[0];
        // } else {
        //     $result['storeid'] = '<b class="text-danger">Add store</b>';
        //     return $result;
        // }
        // <i class="bi bi-amazon"></i>
    }

    public function DeleteDataCredentialsAmazonId()
    {
        global $wpdb;
        $id = intval($_POST["data"]);
        $result = $wpdb->delete("{$wpdb->prefix}{$this->table_store}", ["id" => $id]);
        return  json_encode($result);
    }
}
