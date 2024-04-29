<?php


require_once plugin_dir_path(__DIR__) . "model/adminModel.php";
require_once plugin_dir_path(__DIR__) . "controller/credentialsAmazonController.php";
require_once plugin_dir_path(__DIR__) . "controller/getproductController.php";

if (file_exists(plugin_dir_path(__DIR__) . "../vendor/autoload.php")) {
    include_once plugin_dir_path(__DIR__) . '../vendor/autoload.php';
}




class GpAaGetProductForApiController
{
    private $table_getproduct = "gpgetproduct";

    public function SaveCreateProductAsin()
    {

        $api = new GpAaCredentialsAmazonController();
        $credentials = $api->GetCredentialsAmazonId();
        try {
            parse_str($_POST["data"], $tr);
            $res = $tr['asin'];

            // if ($_POST["type"] == "getItems") {

            $consult = " \"ItemIds\": ["        . "  \"{$res}\""        . " ],";

            $search = ["getitems", "GetItems"];
            $partner_tag = $credentials["partner_tag"];
            // } else if ($_POST["type"] == "searchItems") {

            //     $consult = " \"Keywords\": \"" . $res . "\",";
            //     $search = ["searchitems", "SearchItems"];
            // }



            // Put your Secret Key in place of **********
            $serviceName = "ProductAdvertisingAPI";
            $region = "eu-west-1";
            $accessKey = $credentials["access_key"];
            $secretKey = $credentials["secret_key"];
            $payload = "{"
                . $consult
                . " \"Resources\": ["
                . "  \"BrowseNodeInfo.BrowseNodes\","
                . "  \"BrowseNodeInfo.BrowseNodes.Ancestor\","
                . "  \"BrowseNodeInfo.BrowseNodes.SalesRank\","
                . "  \"BrowseNodeInfo.WebsiteSalesRank\","
                . "  \"CustomerReviews.Count\","
                . "  \"CustomerReviews.StarRating\","
                . "  \"Images.Primary.Small\","
                . "  \"Images.Primary.Medium\","
                . "  \"Images.Primary.Large\","
                . "  \"Images.Primary.HighRes\","
                . "  \"Images.Variants.Small\","
                . "  \"Images.Variants.Medium\","
                . "  \"Images.Variants.Large\","
                . "  \"Images.Variants.HighRes\","
                . "  \"ItemInfo.ByLineInfo\","
                . "  \"ItemInfo.ContentInfo\","
                . "  \"ItemInfo.ContentRating\","
                . "  \"ItemInfo.Classifications\","
                . "  \"ItemInfo.ExternalIds\","
                . "  \"ItemInfo.Features\","
                . "  \"ItemInfo.ManufactureInfo\","
                . "  \"ItemInfo.ProductInfo\","
                . "  \"ItemInfo.TechnicalInfo\","
                . "  \"ItemInfo.Title\","
                . "  \"ItemInfo.TradeInInfo\","
                . "  \"Offers.Listings.Availability.MaxOrderQuantity\","
                . "  \"Offers.Listings.Availability.Message\","
                . "  \"Offers.Listings.Availability.MinOrderQuantity\","
                . "  \"Offers.Listings.Availability.Type\","
                . "  \"Offers.Listings.Condition\","
                . "  \"Offers.Listings.Condition.ConditionNote\","
                . "  \"Offers.Listings.Condition.SubCondition\","
                . "  \"Offers.Listings.DeliveryInfo.IsAmazonFulfilled\","
                . "  \"Offers.Listings.DeliveryInfo.IsFreeShippingEligible\","
                . "  \"Offers.Listings.DeliveryInfo.IsPrimeEligible\","
                . "  \"Offers.Listings.DeliveryInfo.ShippingCharges\","
                . "  \"Offers.Listings.IsBuyBoxWinner\","
                . "  \"Offers.Listings.LoyaltyPoints.Points\","
                . "  \"Offers.Listings.MerchantInfo\","
                . "  \"Offers.Listings.Price\","
                . "  \"Offers.Listings.ProgramEligibility.IsPrimeExclusive\","
                . "  \"Offers.Listings.ProgramEligibility.IsPrimePantry\","
                . "  \"Offers.Listings.Promotions\","
                . "  \"Offers.Listings.SavingBasis\","
                . "  \"Offers.Summaries.HighestPrice\","
                . "  \"Offers.Summaries.LowestPrice\","
                . "  \"Offers.Summaries.OfferCount\","
                . "  \"ParentASIN\","
                . "  \"RentalOffers.Listings.Availability.MaxOrderQuantity\","
                . "  \"RentalOffers.Listings.Availability.Message\","
                . "  \"RentalOffers.Listings.Availability.MinOrderQuantity\","
                . "  \"RentalOffers.Listings.Availability.Type\","
                . "  \"RentalOffers.Listings.BasePrice\","
                . "  \"RentalOffers.Listings.Condition\","
                . "  \"RentalOffers.Listings.Condition.ConditionNote\","
                . "  \"RentalOffers.Listings.Condition.SubCondition\","
                . "  \"RentalOffers.Listings.DeliveryInfo.IsAmazonFulfilled\","
                . "  \"RentalOffers.Listings.DeliveryInfo.IsFreeShippingEligible\","
                . "  \"RentalOffers.Listings.DeliveryInfo.IsPrimeEligible\","
                . "  \"RentalOffers.Listings.DeliveryInfo.ShippingCharges\","
                . "  \"RentalOffers.Listings.MerchantInfo\""
                . " ],"
                . " \"PartnerTag\": \"{$partner_tag}\","
                . " \"PartnerType\": \"Associates\","
                . " \"Marketplace\": \"www.amazon.es\""
                . "}";
            $host = "webservices.amazon.es";
            $uriPath = "/paapi5/" . $search[0];

            require_once plugin_dir_path(__DIR__) . "controller/awsV4.php";
            $awsv4 = new AwsV4($accessKey, $secretKey);
            $awsv4->setRegionName($region);
            $awsv4->setServiceName($serviceName);
            $awsv4->setPath($uriPath);
            $awsv4->setPayload($payload);
            $awsv4->setRequestMethod("POST");
            $awsv4->addHeader('content-encoding', 'amz-1.0');
            $awsv4->addHeader('content-type', 'application/json; charset=utf-8');
            $awsv4->addHeader('host', $host);
            $awsv4->addHeader('x-amz-target', 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.' . $search[1]);
            $headers = $awsv4->getHeaders();
            $headerString = "";
            foreach ($headers as $key => $value) {
                $headerString .= $key . ': ' . $value . "\r\n";
            }
            $params = array(
                'http' => array(
                    'header' => $headerString,
                    'method' => 'POST',
                    'content' => $payload
                )
            );
            $stream = stream_context_create($params);

            $fp = @fopen('https://' . $host . $uriPath, 'rb', false, $stream);

            if (!$fp) {
                throw new Exception("Exception Occured");
            }
            $response = @stream_get_contents($fp);
            if ($response === false) {
                throw new Exception("Exception Occured");
            }

            $g = new GpAaGetProductController;

            $as =  $this->PrepareToSaveDb($response);


            return $g->SaveCreateProduct($as);
        } catch (Exception $e) {
            return json_encode($res['error'] = $e->getMessage());
        }
    }

    function PrepareToSaveDb($product)
    {
        $product = json_decode($product);
        $product0 = $product->ItemsResult->Items[0];
      
        $result["product"]["title"] = $product0->ItemInfo->Title->DisplayValue;
        $result["product"]["asin"] = $product0->ASIN;
        $result["product"]["subtitle"] = $product0->ItemInfo->Title->DisplayValue;
        $result["product"]["price"] = $product0->Offers->Summaries[0]->HighestPrice->DisplayAmount ?? 'Price not available';
        $result["product"]["linkproduct"] = $product0->DetailPageURL;
        $result["product"]["image"][] = $product0->Images->Primary->Large->URL;
        $variantsImg = $product0->Images->Variants;
        foreach ($variantsImg as $k => $v) {
            $result["product"]["image"][] = $variantsImg[$k]->Large->URL;
        }
        return  $result;
    }
}
