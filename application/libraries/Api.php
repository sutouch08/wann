<?php
class Api
{
  private $url;
  protected $ci;
	//public $error;
  private $timeout = 0; //--- timeout in seconds;

  public function __construct()
  {
		$this->ci =& get_instance();

    $this->url = getConfig('SAP_API_HOST');

    if( ! empty($this->url))
    {
      if($this->url[-1] != '/')
      {
        $this->url = $this->url .'/';
      }
    }
  }


	public function exportTransfer($id)
	{
    $this->ci->load->model('inventory/transfer_model');
    $this->ci->load->model('logs_model');

		$testMode = getConfig('TEST_MODE') ? TRUE : FALSE;

		if($testMode)
		{
			$arr = array(
				'Status' => 1,
				'DocEntry' => 1,
				'DocNum' => "22000001"
			);

			$this->ci->transfer_model->update($id, $arr);
			return TRUE;
		}

		$logJson = getConfig('LOGS_JSON') == 1 ? TRUE : FALSE;



		$sc = TRUE;
		$doc = $this->ci->transfer_model->get($id);
    $details = $this->ci->transfer_model->get_details($id);

		if(! empty($doc) && ! empty($details))
		{
      $ds = array(
        'U_WEBCODE' => $doc->code,
        'DocType' => 'I',
        'CANCELED' => 'N',
        'DocDate' => sap_date($doc->DocDate, TRUE),
        'DocDueDate' => sap_date($doc->DocDueDate, TRUE),
        'TaxDate' => sap_date($doc->TaxDate, TRUE),
        'CardCode' => NULL,
        'CardName' => NULL,
        'VatPercent' => 0.000000,
        'VatSum' => 0.000000,
        'VatSumFc' => 0.000000,
        'DiscPrcnt' => 0.000000,
        'DiscSum' => 0.000000,
        'DiscSumFC' => 0.000000,
        'DocCur' => NULL,
        'DocRate' => 1,
        'DocTotal' => 0.000000,
        'DocTotalFC' => 0.000000,
        'Filler' => $doc->fromWhsCode,
        'ToWhsCode' => $doc->toWhsCode,
        'Comments' => $doc->remark,
        'U_ProductionOrder' => "{$doc->U_ProductionOrder}",
        'U_BEX_EXREMARK' => $doc->remark,
        'U_TransferType' => 'P',
        'DocLine' => array()
      );

      foreach($details as $rs)
      {
        $arr =  array(
          'U_WEBCODE' => $rs->transfer_code,
          'LineNum' => intval($rs->LineNum),
          'BaseEntry' => intval($rs->BaseEntry),
          'BaseLine' => intval($rs->BaseLine),
          'ItemCode' => $rs->ItemCode,
          'Dscription' => $rs->Dscription,
          'Quantity' => floatval($rs->Qty),
          'unitMsr' => $rs->unitMsr,
          'NumPerMsr' => $rs->numPerMsr,
          'UomEntry' => intval($rs->UomEntry),
          'UomCode' => $rs->UomCode,
          'PriceBefDi' => 0.000000,
          'LineTotal' => 0.000000,
          'ShipDate' => NULL,
          'Currency' => NULL,
          'Rate' => 1,
          'DiscPrcnt' => 0.000000,
          'Price' => 0.000000,
          'TotalFrgn' => 0.000000,
          'FromWhsCod' => $rs->fromWhsCode,
          'WhsCode' => $rs->toWhsCode,
          'TaxStatus' => 'Y',
          'VatPrcnt' => 0.000000,
          'VatGroup' => NULL,
          'PriceAfVAT' => 0.000000,
          'VatSum' => 0.000000,
          'BatchNo' => $rs->ReceiptNo
        );

        array_push($ds['DocLine'], $arr);
      }

			$url = $this->url."OWTR";

      $json = json_encode([$ds]);

      if($logJson)
      {
        $logs = array(
          'code' => $doc->code,
          'status' => 'send',
          'json' => $json
        );

        $this->ci->logs_model->log_transfer($logs);
      }

			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($curl, CURLOPT_TIMEOUT, 0);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

			$response = curl_exec($curl);

      if($logJson)
      {
        $logs = array(
          'code' => $doc->code,
          'status' => 'result',
          'json' => $response
        );

        $this->ci->logs_model->log_transfer($logs);
      }

      if($response === FALSE)
      {
        $response = curl_error($curl);
      }

			curl_close($curl);

			$rd = json_decode($response);

			if(! empty($rd) && ! empty($rd[0]->status))
			{
        $rs = $rd[0];

				if($rs->status == 'success')
				{
					$arr = array(
						'Status' => 1,
						'DocEntry' => $rs->DocEntry,
						'DocNum' => $rs->DocNum
					);

					$this->ci->transfer_model->update($id, $arr);

				}
        elseif($rs->status == 'exists')
        {
          $arr = array(
						'Status' => 1,
						'DocEntry' => $rs->DocEntry,
						'DocNum' => $rs->DocNum
					);

					$this->ci->transfer_model->update($id, $arr);
        }
				else
				{
					$arr = array(
						'Status' => 3,
						'Message' => empty($rs->errMsg) ? $response : $rs->errMsg
					);

					$this->ci->transfer_model->update($id, $arr);

					$sc = FALSE;
					$this->ci->error = empty($rs->errMsg) ? $response : $rs->errMsg;

					if($logJson)
					{
						$logs = array(
							'code' => $doc->code,
							'status' => 'error',
							'json' => empty($rs->errMsg) ? $response : $rs->errMsg
						);

						$this->ci->logs_model->log_transfer($logs);
					}
				}
			}
			else
			{
				$sc = FALSE;
				$this->ci->error = "Export failed : {$response}";

				$arr = array(
					'Status' => 3,
					'Message' => $response
				);

				$this->ci->transfer_model->update($id, $arr);
			}
		}
		else
		{
			$sc = FALSE;
			$this->ci->error = "No data found";
		}

		return $sc;
	}

} //--- end class
?>
