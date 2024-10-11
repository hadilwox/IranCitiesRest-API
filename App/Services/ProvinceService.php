<?php

namespace App\Services;

class ProvinceService implements IranService
{

    public function create ($data)
    {
        return addProvince($data);

    }

    public function read ($data)
    {
        return getProvinces($data);

    }

    public function update ($id, $name)
    {
        return changeProvinceName($id,$name);
    }

    public function delete ($id)
    {
        return deleteProvince($id);
    }
}