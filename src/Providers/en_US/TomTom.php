<?php

namespace nickurt\postcodeapi\Providers\en_US;

use nickurt\PostcodeApi\Entity\Address;
use nickurt\PostcodeApi\Exception\NotSupportedException;

class TomTom extends \nickurt\PostcodeApi\Providers\AbstractProvider
{
    /** @var string */
    protected $apiKey;

    /** @var string */
    protected $requestUrl = 'https://api.tomtom.com/search/2/geocode/%s.json';

    /**
     * @param string $postCode
     * @return Address
     */
    public function findByPostcode($postCode)
    {
        return $this->find($postCode);
    }

    /**
     * @param string $postCode
     * @return Address
     */
    public function find($postCode)
    {
        $options = strlen($options = http_build_query($this->getOptions())) > 1 ? '&' . $options : '';

        $response = $this->get(sprintf($this->getRequestUrl(), $postCode) . '?key=' . $this->getApiKey() . $options);

        if ($response['summary']['totalResults'] < 1) {
            return new Address();
        }

        $address = new Address();
        $address
            ->setTown($response['results'][0]['address']['municipalitySubdivision'])
            ->setMunicipality($response['results'][0]['address']['municipality'])
            ->setProvince($response['results'][0]['address']['countrySubdivision'])
            ->setLatitude($response['results'][0]['position']['lat'])
            ->setLongitude($response['results'][0]['position']['lon']);

        return $address;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @param string $postCode
     * @param string $houseNumber
     */
    public function findByPostcodeAndHouseNumber($postCode, $houseNumber)
    {
        throw new NotSupportedException();
    }
}
