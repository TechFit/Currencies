<?php

namespace app\commands;

use app\models\Currency;
use yii\db\Exception;
use yii\httpclient\Client;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Class CurrenciesController
 */
class CurrenciesController extends Controller
{
    /**
     * @return int
     *
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionUpdate(): int
    {
        echo "Update has been started. \n";

        $resultMessage = "Update finished";

        $url = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=" . date('d/m/yy');

        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->send();

        if ($response->isOk) {
            if (!empty($response->data)) {
                try {
                    $currencies = [];
                    foreach ($response->data["Valute"] as $valute) {
                        $value  = floatval(str_replace(
                                ',',
                                '.',
                                str_replace('.', '', $valute['Value']))
                        );
                        array_push($currencies, [
                            'name' => (string) $valute['Name'],
                            'rate' => $value,
                            'insert_dt' => time(),
                        ]);
                    }

                    $db = \Yii::$app->db;
                    $sql = $db->queryBuilder->batchInsert(Currency::tableName(), ['name', 'rate', 'insert_dt'], $currencies);
                    $db->createCommand($sql . ' ON DUPLICATE KEY UPDATE name = VALUES(name), rate = VALUES(rate)')->execute();
                } catch (Exception $exception) {
                    echo "Error " . $exception->getMessage();
                }
            } else {
                $resultMessage = "Empty data from API, update not finished";
            }
        }

        echo $resultMessage;

        return ExitCode::OK;
    }
}
