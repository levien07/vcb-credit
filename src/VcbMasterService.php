<?php

namespace Pay\Vcb\Master;

use CyberSource\Configuration;
use GuzzleHttp\Client;

/**
 * Class VcbMasterService
 * @package Pay\Vcb\Master
 */
class VcbMasterService implements VcbMasterInterface
{
    private $authType;
    private $enableLog;
    private $logSize;
    private $logFile;
    private $logFilename;
    private $merchantID;
    private $apiKeyID;
    private $secretKey;
    private $keyAlias;
    private $keyPass;
    private $keyFilename;
    private $keyDirectory;
    private $runEnv;

    /**
     * Service constructor.
     */
    public function __construct()
    {
        $this->authType = config('vcb.master.api_type');
        $this->enableLog = true;
        $this->logSize = "1048576";
        $this->logFile = "Log";
        $this->logFilename = "Cybs.log";
        $this->merchantID = config('vcb.master.merchant_id');
        $this->apiKeyID = config('vcb.master.api_key');
        $this->secretKey = config('vcb.master.secret_key');
        $this->keyAlias = "9pay";
        $this->keyPass = "9pay";
        $this->keyFilename = "9pay";
        $this->keyDirectory = "Resources/";
        $this->runEnv = config('vcb.master.api_url');
        $this->merchantConfigObject();
    }

    private function merchantConfigObject()
    {
        $config = new \CyberSource\Authentication\Core\MerchantConfiguration();
        if (is_bool($this->enableLog))
            $config->setDebug($this->enableLog);
        $config->setLogSize(trim($this->logSize));
        $config->setDebugFile(trim(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . $this->logFile));
        $config->setLogFileName(trim($this->logFilename));
        $config->setauthenticationType(strtoupper(trim($this->authType)));
        $config->setMerchantID(trim($this->merchantID));
        $config->setApiKeyID($this->apiKeyID);
        $config->setSecretKey($this->secretKey);
        $config->setKeyFileName(trim($this->keyFilename));
        $config->setKeyAlias($this->keyAlias);
        $config->setKeyPassword($this->keyPass);
        $config->setKeysDirectory(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . $this->keyDirectory);
        $confiData = $config->setRunEnvironment($this->runEnv);
        $config->validateMerchantData($confiData);
        return $config;
    }

    public function payment($params)
    {
        $clientReferenceInformation = new \CyberSource\Model\Ptsv2paymentsClientReferenceInformation(["code" => $params['clientReferenceInformation']['code']]);
        $processingInformation = new \CyberSource\Model\Ptsv2paymentsProcessingInformation(["capture" => false]);
        $paymentInformationCard = new \CyberSource\Model\Ptsv2paymentsPaymentInformationCard($params['paymentInformation']);
        $paymentInformationArr = ["card" => $paymentInformationCard];
        $paymentInformation = new \CyberSource\Model\Ptsv2paymentsPaymentInformation($paymentInformationArr);
        $orderInformationAmountDetailsArr = $params['amountDetails'];
        $orderInformationAmountDetails = new \CyberSource\Model\Ptsv2paymentsOrderInformationAmountDetails($orderInformationAmountDetailsArr);
        $orderInformationBillTo = new \CyberSource\Model\Ptsv2paymentsOrderInformationBillTo($params['billTo']);
        $orderInformationArr = [
            "amountDetails" => $orderInformationAmountDetails,
            "billTo" => $orderInformationBillTo
        ];
        $orderInformation = new \CyberSource\Model\Ptsv2paymentsOrderInformation($orderInformationArr);
        $requestObjArr = [
            "clientReferenceInformation" => $clientReferenceInformation,
            "processingInformation" => $processingInformation,
            "paymentInformation" => $paymentInformation,
            "orderInformation" => $orderInformation
        ];
        $requestObj = new \CyberSource\Model\CreatePaymentRequest($requestObjArr);
        $config = $this->ConnectionHost();
        $merchantConfig = $this->merchantConfigObject();
        $api_client = new \CyberSource\ApiClient($config, $merchantConfig);
        $api_instance = new \CyberSource\Api\PaymentsApi($api_client);
        try {
            return $api_instance->createPayment($requestObj);
        } catch (\Cybersource\ApiException $e) {
            print_r($e->getResponseBody());
            print_r($e->getMessage());
        }
    }

    public function searchTransactions($params)
    {
        $requestObj = new \CyberSource\Model\CreateSearchRequest($params);
        $config = $this->ConnectionHost();
        $merchantConfig = $this->merchantConfigObject();
        $api_client = new \CyberSource\ApiClient($config, $merchantConfig);
        $api_instance = new \CyberSource\Api\SearchTransactionsApi($api_client);
        try {
            return $api_instance->createSearch($requestObj);
        } catch (\Cybersource\ApiException $e) {
            print_r($e->getResponseBody());
            print_r($e->getMessage());
        }
    }

    public function refund($id, $params)
    {
        $clientReferenceInformation = new \CyberSource\Model\Ptsv2paymentsClientReferenceInformation($params['clientReferenceInformation']['code']);
        $orderInformationAmountDetails = new \CyberSource\Model\Ptsv2paymentsidcapturesOrderInformationAmountDetails($params['orderInformation']['amountDetails']);
        $orderInformationArr = [
            "amountDetails" => $orderInformationAmountDetails
        ];
        $orderInformation = new \CyberSource\Model\Ptsv2paymentsidrefundsOrderInformation($orderInformationArr);
        $requestObjArr = [
            "clientReferenceInformation" => $clientReferenceInformation,
            "orderInformation" => $orderInformation
        ];
        $requestObj = new \CyberSource\Model\RefundPaymentRequest($requestObjArr);
        $config = $this->ConnectionHost();
        $merchantConfig = $this->merchantConfigObject();

        $api_client = new \CyberSource\ApiClient($config, $merchantConfig);
        $api_instance = new \CyberSource\Api\RefundApi($api_client);

        try {
            return $api_instance->refundPayment($requestObj, $id);
        } catch (\Cybersource\ApiException $e) {
            print_r($e->getResponseBody());
            print_r($e->getMessage());
        }
    }

    public function getTransactions($id)
    {
        $config = $this->ConnectionHost();
        $merchantConfig = $this->merchantConfigObject();
        $api_client = new \CyberSource\ApiClient($config, $merchantConfig);
        $api_instance = new \CyberSource\Api\TransactionDetailsApi($api_client);
        try {
            return $api_instance->getTransaction($id);
        } catch (\Cybersource\ApiException $e) {
            print_r($e->getResponseBody());
            print_r($e->getMessage());
        }
    }

    private function ConnectionHost()
    {
        $merchantConfig = $this->merchantConfigObject();
        $config = new Configuration();
        $config = $config->setHost($merchantConfig->getHost());
        $config = $config->setDebug($merchantConfig->getDebug());
        $config = $config->setDebugFile($merchantConfig->getDebugFile() . DIRECTORY_SEPARATOR . $merchantConfig->getLogFileName());
        return $config;
    }


}
