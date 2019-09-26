<?php

namespace cyr\face;

use yii\base\Object;
use yii\httpclient\Client;
use yii;
use yii\httpclient\CurlTransport;

class PaybaseService extends Object
{
    public $request_url;

    public function request($data, $doconvert = true, $doverify = true)
    {
        $request_data =[
            'condition' => RsaHelper::encryprFace($this->sign()),
            'userCode' => 1,
            'signature' => 1,
            'vector' => 1,
        ];
        $client = new Client();
        $client->setTransport(CurlTransport::className());
        $request = $client->createRequest();
        $request->setHeaders(['content-type' => 'application/x-www-form-urlencoded'])
            ->addHeaders(['charset' => 'utf-8']);
        $request->setUrl($this->request_url);
        $request->setMethod('post');
        $request->setData($request_data);
        $response = $request->send();
        
        if ($response->isOk) {
            $result = $this->_getResult($response->getContent());

            return $result;
        } else {
            throw new \Exception($response->getContent());
        }
    }

    private function _getResult($response)
    {
        $decoded = json_decode($response, true);

        if (!$decoded) {
            return null;
        }
        $result = new Result();
        $result->respCode = $decoded['respCode'];
        $result->respData = $decoded['respData'];
        $result->respMsg = $decoded['respMsg'];
        $result->signature = $decoded['signature'];

        return $result;
    }
    
    private function sign(){
        $data ['header'][] = [
            "qryBatchNo" => "20160525151642123",
            "userCode" => "User0001",
            "syscode" =>"",
            "qtyreason" => "",
            "qtyDate" => "",
            "qtyTime" => "",
        ];
        $data ['condition'][] = [
            "realName" => "20160525151642123",
            "idCard" => "User0001",
            "photo" =>"",
            "alivedet" => "",
        ];
        return json_encode($data);
    }

}
