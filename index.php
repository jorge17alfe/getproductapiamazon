<?php
/*
Plugin Name: Api Amazon add product
Description: This is a Get Product of Api Amazon
Version: 1.00
*/
/* GPAA  = get product amazon afiliate*/
require_once "app/controller/adminController.php";
$GpAaadminController =   new GpAaAdminController;

if(!defined('GPAAURLPLUGIN')) define('GPAAURLPLUGIN', basename(dirname(__FILE__)) . "/public/view/index.php");


register_activation_hook(__FILE__, array($GpAaadminController, 'Active'));



register_deactivation_hook(__FILE__, array($GpAaadminController, 'Desactive'));


function GpAaCreateMenu()
{
    global $GpAaadminController;
    $GpAaadminController->CreateMenu(plugin_dir_path(__FILE__) . 'public/view/index.php');
    
}
add_action('admin_menu', 'GpAaCreateMenu');



function GpAaRegisterBootstrapJS($hook)
{

    if ($hook != GPAAURLPLUGIN) {
        return;
    }
    wp_enqueue_script('bootstrapJs', plugins_url('public/assets/bootstrap-5.2.3-dist/js/bootstrap.bundle.js', __FILE__), array('jquery'));
}
add_action('admin_enqueue_scripts', 'GpAaRegisterBootstrapJS');


function GpAaRegisterBootstrapCSS($hook)
{
    if ($hook != GPAAURLPLUGIN) {
        return;
    }
    wp_enqueue_style('bootstrapCss', plugins_url('public/assets/bootstrap-5.2.3-dist/css/bootstrap.min.css', __FILE__));
    wp_enqueue_style('bootstrapIconsCss', plugins_url('public/assets/bootstrap-5.2.3-dist/css/font/bootstrap-icons.min.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'GpAaRegisterBootstrapCSS');



// // //Register js own

function GpAaRegisterJsGeneratePage($hook)
{
    if ($hook != GPAAURLPLUGIN) {
        return;
    }
    wp_enqueue_script('JsExternal', plugins_url('public/assets/js/index.js', __FILE__), array('jquery'));
    wp_localize_script('JsExternal', 'PetitionAjax', [
        'url' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('seg')
    ]);
}
add_action('admin_enqueue_scripts', 'GpAaRegisterJsGeneratePage');



//  data Credentials  AMAZON Api IDS
require_once "app/controller/credentialsAmazonController.php";
$credentialsAmazonController =   new GpAaCredentialsAmazonController;


function GpAagetCredentialsAmazonIds()
{
    global $credentialsAmazonController;
    echo $credentialsAmazonController->GetCredentialsAmazonIds();
}

add_action('wp_ajax_nopriv_get_data_credentials_amazon_ids', 'GpAaGetCredentialsAmazonIds');
add_action('wp_ajax_get_data_credentials_amazon_ids', 'GpAaGetCredentialsAmazonIds');

function GpAaDeleteCredentialsAmazonId()
{
    global $credentialsAmazonController;
    echo $credentialsAmazonController->DeleteDataCredentialsAmazonId();
}

add_action('wp_ajax_nopriv_delete_data_credentials_amazon_id', 'GpAaDeleteCredentialsAmazonId');
add_action('wp_ajax_delete_data_credentials_amazon_id', 'GpAaDeleteCredentialsAmazonId');



function GpAaSaveCredentialsAmazonId()
{
    global $credentialsAmazonController;
    echo $credentialsAmazonController->SaveDataCredentialsAmazonId();
   
}

add_action('wp_ajax_nopriv_save_data_credentials_amazon_id', 'GpAaSaveCredentialsAmazonId');
add_action('wp_ajax_save_data_credentials_amazon_id', 'GpAaSaveCredentialsAmazonId');



// data PRODUCTS
require_once "app/controller/getproductController.php";
$getProductController =   new GpAaGetProductController;


function GpAagetProducts()
{
    global $getProductController;
    echo $getProductController->GpAagetProducts();
}

add_action('wp_ajax_nopriv_get_data_products', 'GpAaGetProducts');
add_action('wp_ajax_get_data_products', 'GpAaGetProducts');

function GpAaSaveCreateProduct()
{
    global $getProductController ;
    echo  $getProductController->SaveCreateProduct();
   
}

add_action('wp_ajax_nopriv_save_create_product', 'GpAaSaveCreateProduct');
add_action('wp_ajax_save_create_product', 'GpAaSaveCreateProduct');




function GpAaDeleteProduct(){
    global $getProductController;
    echo $getProductController->DeleteDataProduct();
}

add_action('wp_ajax_nopriv_delete_data_product_id', 'GpAaDeleteProduct');
add_action('wp_ajax_delete_data_product_id', 'GpAaDeleteProduct');


// data PRODUCTS for ASIN
require_once "app/controller/getProductForApiController.php";
$getProductForApiController =   new GpAaGetProductForApiController;

function GpAaSaveCreateProductAsin()
{
    global $getProductForApiController ;
    echo  $getProductForApiController->SaveCreateProductAsin();
}

add_action('wp_ajax_nopriv_save_data_create_product_asin', 'GpAaSaveCreateProductAsin');
add_action('wp_ajax_save_data_create_product_asin', 'GpAaSaveCreateProductAsin');




// data SHORTCODE
function GpAaGetShortCode($atts)
{
    global $getProductController ;
    return  $getProductController->GpAaGetShortCode($atts);
}

add_shortcode("GETPRODUCT", "GpAaGetShortCode");
