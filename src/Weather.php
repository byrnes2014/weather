<?php
namespace Byrnes2014\Weather;

use GuzzleHttp\Client;
use Byrnes2014\Weather\Exceptions\HttpException;
use Byrnes2014\Weather\Exceptions\InvalidArgumentException;

class Weather
{

	protected $key;
	protected $guzzleOption = [];

	public function __construct($key)
	{
		$this->key = $key;
	}


	public function getHttpClient()
	{
		return new Client($this->guzzleOption);
	}

	public function setGuzzleOptions(array $option)
	{
		$this->guzzleOption = $option;
	}

	public function getWeather($city, string $type = 'base', string $format = 'json')
	{

		$url = 'https://restapi.amap.com/v3/weather/weatherInfo';

        if (!\in_array(\strtolower($format), ['xml', 'json'])) {
            throw new InvalidArgumentException('Invalid response format: '.$format);
        }

        if (!\in_array(\strtolower($type), ['base', 'all'])) {
            throw new InvalidArgumentException('Invalid type value(base/all): '.$type);
        }

        $query = array_filter([
            'key' => $this->key,
            'city' => $city,
            'output' => \strtolower($format),
            'extensions' =>  \strtolower($type),
        ]);

        try {
            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();

            return 'json' === $format ? \json_decode($response, true) : $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }

	}

    // 获取实时天气
    public function getLiveWeather($city, $format = 'json') 
    {
        return $this->getWeather($city, 'base', $format);
    }


    // 获取天气预报
    public function getForecastsWeather($city, $format = 'json')
    {
        return $this->getWeather($city, 'all', $format);
    }

}