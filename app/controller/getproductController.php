<?php


require_once plugin_dir_path(__DIR__) . "model/adminModel.php";
require_once plugin_dir_path(__DIR__) . "controller/credentialsAmazonController.php";

if (file_exists(plugin_dir_path(__DIR__) . "../vendor/autoload.php")) {
    include_once plugin_dir_path(__DIR__) . '../vendor/autoload.php';
}




class GpAaGetProductController
{
    private $db;
    private $table_getproduct = "gpgetproduct";

    public function __construct()
    {
        $this->db = new adminModel;
    }

    public function contString($data)
    {
        $string =  explode(' ', $data);
        $result = '';
        if (count($string) === 1) {
            $result = $string[0];
        } else {
            foreach ($string as $k => $v) {
                $result .= $v;
                if ($k < (count($string) - 1)) {
                    $result .= '+';
                }
            }
        }
        return $result;
    }


    public function getUrlProduct($asin, $amazonId)
    {
        $asin = $this->contString($asin);
        $str = 'https://www.amazon.es/dp/' . $asin . '?_encoding=UTF8&tag=' . $amazonId . '&language=es_ES';
        return $str;
    }


    
    public function GpAaGetStoreAfiliate()
    {
        global $wpdb;

        $query = "SELECT * FROM {$wpdb->prefix}storeid ORDER BY id DESC limit 1";
        return $wpdb->get_results($query,  ARRAY_A)[0]["storeid"];
    }

    public function SaveCreateProduct($product = null)
    {
        global $wpdb;
        if (isset($product)) {
            $title = $product["product"]["title"];
            $re = serialize($product);
        } else {
            parse_str($_POST["data"], $tr);
            $_POST["data"] = $tr;
            $title = $tr["product"]["title"];
            $re = serialize($_POST["data"]);
        }

        $ins = ["title" => $title, "product" => $re];
   
        if (!empty($_POST["data"]["id"]) && isset($_POST)) {
            $wpdb->update("{$wpdb->prefix}{$this->table_getproduct}", $ins, ['id' => $_POST["data"]["id"]]);
            return;
        }
        $wpdb->insert("{$wpdb->prefix}{$this->table_getproduct}", $ins);
        return ;
    }


    public function GpAagetProducts($since = 0, $total_rows = 5)
    {
        $since = ($_POST["data"]["nropagination"] * $_POST["data"]["total_rows"]);
        $total_rows = ($_POST["data"]["total_rows"]);
        global $wpdb;
        $total_product = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}{$this->table_getproduct} ",  ARRAY_A);
        $query = "SELECT * FROM {$wpdb->prefix}{$this->table_getproduct} ORDER BY id DESC LIMIT {$since}, {$total_rows}";
        $result = $wpdb->get_results($query,  ARRAY_A);
        foreach ($result as $k => $v) {
            $result[$k]["product"] = unserialize($result[$k]["product"]);
        }



        return json_encode($result);
    }

    public function GpAagetProduct()
    {
        global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}{$this->table_getproduct}";
        $result = $wpdb->get_results($query,  ARRAY_A);

        foreach ($result as $k => $v) {
            $result[$k]["product"] = unserialize($result[$k]["product"]);
        }
        return json_encode($result);
    }

    public function DeleteDataProduct()
    {
        global $wpdb;
        $id = intval($_POST["data"]);
        $result = $wpdb->delete("{$wpdb->prefix}{$this->table_getproduct}", ["id" => $id]);
        return  json_encode($result);
    }



    public function GpAaGetShortCode($atts)
    {
        global $wpdb;

        $id = $atts['id'];
        $query = "SELECT * FROM {$wpdb->prefix}{$this->table_getproduct} WHERE id = $id";
        $result = $wpdb->get_results($query,  ARRAY_A);

        if (empty($result)) return;

        $view = unserialize($result[0]['product']);

        if (empty($view['product']['linkproduct'])) $view['product']['linkproduct'] = $this->getUrlProduct($view['product']['asin'], $this->GpGetStoreAfiliate());
        // if (empty($view['product']['subtitle'])) $view['product']['subtitle'] = "";
        if (count($view['product']['image']) == 1) {

            $active_carrousel = "";
        } else {
            $active_carrousel = "carousel";
        }

        $q = '<div class="card h-100 shadow my-4" >';
        $q .=   '<div id="carouselExampleSlidesOnly" class="carousel slide " data-bs-ride="' . $active_carrousel . '">
                   
                         <div class="carousel-inner">';


        foreach ($view['product']['image'] as $k => $v) {

            if ($k == 0) {
                $active = "active";
            } else {
                $active = "";
            };

            $q .= '          <div class="carousel-item ' . $active . '">
                                <img style="max-width:350px; max-height:350px" src="' . $view['product']['image'][$k] . '" class="d-block m-auto py-3" alt="...">
                            </div>';
        }





        $q .=           '</div>
                   
                </div>';
        $q .= '<div class="p-3 container text-center"> 
                    <div class="card-body">
                        <h5 class="card-title">' . $view['product']['title'] . '</h5> 
                        <p class="text-muted">' . $view['product']['subtitle'] . '</p>
                    </div>          
                    <div class="">
                        <div class="text-center">          
                            <p class="text-muted"><b>' . $view['product']['price'] . '</b></p>
                            <a target="_blank" href="' . $view['product']['linkproduct'] . '" class="btn btn-outline-success " >Comprar en <i class="bi bi-amazon"></i></a>
                        </div>          
                    </div>
                </div>';

        $q .= '</div>';

        return $q;
    }
}
