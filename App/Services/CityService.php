<?php

namespace App\Services;

class CityService implements IranService
{
    // creat new city
    public function create ($data)
    {
        return addCity($data);
    }

    // get cities
    public function read ($data)
    {
        return getCities($data);
    }

    // change name city
    public function update ($city_id , $name)
    {
        return changeCityName($city_id , $name);
    }

    // delete a city
    public function delete ($city_id)
    {
        return deleteCity($city_id);
    }
}