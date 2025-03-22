<?php

namespace App\Repositories;

use App\Models\Team;

class TeamRepository implements TeamRepositoryInterface
{
    protected $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    public function getAll()
    {
        return $this->team->all();
    }

    public function find($id)
    {
        return $this->team->find($id);
    }

    public function create(array $data)
    {
        return $this->team->create($data);
    }

    public function update($id, array $data)
    {
        $team = $this->find($id);
        if($team) {
            $team->update($data);
        }
        return $team;
    }

    public function delete($id)
    {
        $team = $this->find($id);
        return $team ? $team->delete() : false;
    }
}
