<?php

namespace App\Services;

interface IranService
{
    // creat new city
    public function create ($data);
  // get cities
    public function read ($data);
 // change name city
    public function update($city_id , $name);
 // delete a city
    public function delete ($city_id);
}