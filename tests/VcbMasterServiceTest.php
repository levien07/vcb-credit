<?php

namespace Pay\Vcb\Master;


use PHPUnit\Framework\TestCase;

/**
 * Class VcbMasterServiceTest
 * @package Pay\Vcb\Master
 */
class VcbMasterServiceTest extends TestCase
{

    /**
     * @var VcbMasterService
     */
    private $service;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new VcbMasterService();
    }

    /**
     *
     */
    public function tearDown(): void
    {
        $this->service = null;
        parent::tearDown();
    }

    public function testPayment()
    {
        $param = [
            'clientReferenceInformation' => [
                "code" => "9Pay-001",
            ],
            'paymentInformation' => [
                "number" => "4111111111111111",
                "expirationMonth" => "12",
                "expirationYear" => "2031"
            ],
            'amountDetails' => [
                "totalAmount" => "102.21",
                "currency" => "USD"
            ],
            'billTo' => [
                "firstName" => "9pay",
                "lastName" => "9pay",
                "address1" => "Toa nha tay ha",
                "locality" => "Ha noi",
                "administrativeArea" => "CA",
                "postalCode" => "7000000",
                "country" => "VN",
                "email" => "vienld@9pay.vn",
                "phoneNumber" => "0904391603"
            ],
        ];
        $data = $this->service->payment($param);

        print_r($data['status']);
        die;
        return $this->assertTrue(true);
    }

   /* public function testSearchTransactions()
    {
        $param = [
            "save" => false,
            "name" => "MRN",
            "timezone" => "America/Chicago",
            "query" => "clientReferenceInformation.code:TC50171_3 AND submitTimeUtc:[NOW/DAY-7DAYS TO NOW/DAY+1DAY}",
            "offset" => 0,
            "limit" => 100,
            "sort" => "id:asc,submitTimeUtc:asc"
        ];
        $this->service->searchTransactions($param);
        return $this->assertTrue(true);
    }

    public function testGetTransactions()
    {
        $this->service->getTransactions('5967746433456159403004');
        return $this->assertTrue(true);
    }*/
}
