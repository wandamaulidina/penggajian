<?php
namespace App\Libraries;

use CodeIgniter\CLI\CLI;

class Flip extends \CodeIgniter\CLI\CLI
{
	private $environment = "sandbox";
	private $secret_key  = "JDJ5JDEzJEoyTWJ0aklydDhHdUFadUNWRlV2SC5nUWZ1MDk2YmlpUmZhM0dhNjVXV1c3Q1lNZ2xRcHNX";
	private $private_key;

	public function __construct()
	{
        // Mendapatkan access token saat pertama kali objek dibuat
		$this->generate_private_key();
	}

	public function generate_private_key()
	{
		# function for generate: private key
		$options = openssl_pkey_new([
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        # BUG: kenapa yang ditampilkan adalah PUBLIC-KEY. Harusnya PRIVATE-KEY

        // Get private key details
        $details = openssl_pkey_get_details($options);

        # set value of private_key
        $this->private_key = $details['key'];
	}

	public function generate_signature($message)
	{
		// Load the private key
        $private_key = openssl_pkey_get_private($this->private_key);

        // Create the signature
        openssl_sign($message, $signature, $private_key, "sha256WithRSAEncryption");

        // Free the key from memory
        openssl_free_key($private_key);

        // Return the signature as a base64-encoded string
        return base64_encode($signature);
	}

	public function send_request($method, $url, $data = array())
	{
		$url = 'https://bigflip.id/api/v3/' . $endpoint;

	    // Gunakan URL sandbox jika environment adalah sandbox
	    if ($this->environment === 'sandbox') {
	        $url = 'https://bigflip.id/big_sandbox_api/' . $endpoint;
	    }

	    # set secret key to encode
	    $encoded_secret_key = base64_encode($this->secret_key.":");

	    # set signature
	    $signature = $this->generate_signature(json_encode($data));

        // Konfigurasi pengiriman permintaan API
		$headers = array(
			'Content-Type: application/json',
			'Authorization: Basic ' . $encoded_secret_key,
			'X-Signature: '.$signature
		);

		$ch = curl_init($url);
		curl_setopt_array($ch, [
			CURLOPT_HTTPHEADER			=> $headers,
			CURLOPT_RETURNTRANSFER		=> true,
			CURLOPT_CUSTOMREQUEST		=> $method,
			CURLOPT_POSTFIELDS			=> json_encode($data)
		]);

        // Kirim permintaan API
		$response = curl_exec($ch);

        // Tutup koneksi cURL
		curl_close($ch);

        // Parse respons JSON dan kembalikan hasilnya
		$decoded_response = json_decode($response, true);
		if ($decoded_response == null) {
			throw new \Exception('Respons API tidak valid: ' . $response);
		} elseif (isset($decoded_response['error'])) {
			throw new \Exception('Gagal mengirim permintaan API: ' . $decoded_response['error']);
		} else {
			return $decoded_response;
		}
	}
	public function create_disbursement()
	{
		$ch = curl_init();
		$secret_key = "wwwwwwwxxxxxxxaaaaaaabbbbbbbbbcccccdddd";

		curl_setopt($ch, CURLOPT_URL, "https://bigflip.id/api/v3/disbursement");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_POST, TRUE);

		$payloads = [
		    "account_number" => "1122333300",
		    "bank_code" => "bni",
		    "amount" => "10000",
		    "remark" => "some remark",
		    "recipient_city" => "391",
		    "beneficiary_email" => "test@mail.com,user@mail.com"
		];

		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payloads));

		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  "Content-Type: application/x-www-form-urlencoded",
		  "idempotency-key: idem-key-1",
		  "X-TIMESTAMP: 2022-01-01T15:02:15+0700"
		));

		curl_setopt($ch, CURLOPT_USERPWD, $secret_key.":");

		$response = curl_exec($ch);
		curl_close($ch);

		var_dump($response);
	}
}
