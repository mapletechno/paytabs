<?php
namespace App\Controllers;
use App\Models\Payment;
use App\Utils\EnvLoader;

class PaymentController {
    private $paytabsBaseUrl;
    private $profileId;
    private $serverKey;
    private $paymentModel;

    public function __construct() {
  // Load environment variables
  EnvLoader::load(__DIR__ . '/../../.env');

        $this->paytabsBaseUrl = $_ENV['PAYTABS_BASE_URL'];
        $this->profileId = $_ENV['PAYTABS_PROFILE_ID'];
        $this->serverKey = $_ENV['PAYTABS_SERVER_KEY'];

        $this->paymentModel = new Payment();
    }

        /**
     * Check if the redirect is valid.
     * @param array $post_values
     * @return bool
     */
    function is_valid_redirect($post_values)
    {
        if (empty($post_values) || !array_key_exists('signature', $post_values)) {
            return false;
        }
    
        $serverKey = $this->serverKey;
    
        // Request body include a signature post Form URL encoded field
        // 'signature' (hexadecimal encoding for hmac of sorted post form fields)
        $requestSignature = $post_values["signature"];
        unset($post_values["signature"]);
        $fields = array_filter($post_values);
    
        // Sort form fields
        ksort($fields);
    
        // Generate URL-encoded query string of Post fields except signature field.
        $query = http_build_query($fields);
    
        return $this->is_genuine($query, $requestSignature, $serverKey);
    }
    
    /**
     * Check if the request is genuine.
     * @param string $data
     * @param string $requestSignature
     * @param string $serverKey
     * @return bool
     */
    private function is_genuine($data, $requestSignature, $serverKey)
    {
        $signature = hash_hmac('sha256', $data, $serverKey);
    
        if (hash_equals($signature, $requestSignature) === TRUE) {
            // VALID Redirect
            return true;
        } else {
            // INVALID Redirect
            return false;
        }
    }


    public function createPayment($order_id, $total, $customerDetails) {
        $url = $this->paytabsBaseUrl . '/payment/request';
        $data = [
            'profile_id' => $this->profileId,
            'tran_type' => 'sale',
            'tran_class' => 'ecom',
            'cart_id' => "$order_id",
            'cart_description' => 'Order Checkout',
            'cart_currency' => 'EGP',
            'cart_amount' => $total,
            'framed' => true,
            'framed_return_top' => true,
            'framed_return_parent' => true,
            'return' => 'https://phpstack-1383605-5136042.cloudwaysapps.com/pytb/public/handle-payment-response.php',
            'customer_details' =>$customerDetails
            /*
             [
                'name' => $customerDetails['name'],
                'email' => $customerDetails['email'],
                'phone' => $customerDetails['phone'],
                'country' => $customerDetails['country'],
                'address' => $customerDetails['address'],
                'city' => $customerDetails['city'],
            ]  
            */
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: ' . $this->serverKey,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function savePayment($userId, $order_id, $paymentStatus) {
        // Save the payment details to the database
        $this->paymentModel->createPayment($userId, $paymentStatus, $order_id);
    }
}
?>
