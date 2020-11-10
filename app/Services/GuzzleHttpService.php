<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;

class GuzzleHttpService
{
    const URL = "https://api.carbonintensity.org.uk/regional";
    const TIME_FORMAT = "Y-m-d\TH:i\Z";
    const REGIONS = [1,2,3,4,5,6,7,8,9,10,11,12,13,14];

    /** @var Client */
    var $client;

    /** @var array */
    var $data = [];

    var $from;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchCurrentData()
    {
        $data = $this->client->request('GET', self::URL)->getBody();
        return $this->aggregateData(json_decode((string) $data));
    }

    /**
     * @param string $start
     * @param string $end
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function fetchDataBetween(string $start, string $end) {
        $parameters = "/intensity/{$start}/{$end}";

        $data = $this->client->request('GET', self::URL . $parameters)->getBody();
        return $this->aggregateData(json_decode((string) $data));
    }

    /**
     * Data takes the form of:
     *
     *
     *
     * @param $data
     */
    public function aggregateData($data)
    {
        $returnData = [];
        $energyData = [];

        $returnData['from'] = reset($data->data)->from;
        $returnData['to'] = end($data->data)->to;

        foreach($data->data as $key => $timePeriod) {
            foreach ($timePeriod->regions as $key2 => $region) {
                if (!in_array($region->regionid, self::REGIONS)) {
                    continue;
                }
                if (! isset($energyData[0])) {
                    $energyData[0]['region'] = 'Total Grid';
                    $energyData[0]['forecast'] = $region->intensity->forecast;
                    foreach ($region->generationmix as $key3 => $generator) {
                        $energyData[0]['generation'][$generator->fuel] = ($region->intensity->forecast/100) * $generator->perc;
                    }
                } else {
                    $energyData[0]['forecast'] += $region->intensity->forecast;
                    foreach ($region->generationmix as $key3 => $generator) {
                        $energyData[0]['generation'][$generator->fuel] += ($region->intensity->forecast/100) * $generator->perc;
                    }
                }

                if (! isset($energyData[$region->regionid])) {
                    $energyData[$region->regionid]['region'] = $region->shortname;
                    $energyData[$region->regionid]['forecast'] = $region->intensity->forecast;
                    foreach ($region->generationmix as $key3 => $generator) {
                        $energyData[$region->regionid]['generation'][$generator->fuel] = ($region->intensity->forecast/100) * $generator->perc;
                    }
                } else {
                    $energyData[$region->regionid]['forecast'] += $region->intensity->forecast;
                    foreach ($region->generationmix as $key3 => $generator) {
                        $energyData[$region->regionid]['generation'][$generator->fuel] += ($region->intensity->forecast/100) * $generator->perc;
                    }
                }
            }
        }

        $returnData['energyData'] = $energyData;
        return $returnData;
    }
}
