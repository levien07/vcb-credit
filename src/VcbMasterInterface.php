<?php


namespace Pay\Vcb\Master;


/**
 * Interface VcbMasterInterface
 * @package Pay\Vcb\Master
 */
interface VcbMasterInterface
{

    public function payment($params);

    public function refund($id,$params);

    public function searchTransactions($params);

    public function getTransactions($id);

}
