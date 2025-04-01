<?php

namespace App\Repositories;

use App\Models\Manufacturer;

class ManufacturerRepository implements ManufacturerRepositoryInterface
{
    protected $manufacturer;

    public function __construct(Manufacturer $manufacturer)
    {
        $this->manufacturer = $manufacturer;
    }

    public function getAll()
    {
        return $this->manufacturer->all();
    }

    public function find($id)
    {
        return $this->manufacturer->find($id);
    }

    public function create(array $data)
    {
        return $this->manufacturer->create($data);
    }

    public function update($id, array $data)
    {
        $manufacturer = $this->find($id);
        if($manufacturer) {
            $manufacturer->update($data);
        }
        return $manufacturer;
    }

    public function delete($id)
    {
        $manufacturer = $this->find($id);
        return $manufacturer ? $manufacturer->delete() : false;
    }
}
