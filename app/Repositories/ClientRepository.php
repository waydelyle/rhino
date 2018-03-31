<?php

namespace App\Repositories;

use App\Client;

/**
 * Class ClientRepository
 * @package App\Services
 */
class ClientRepository
{
    /**
     * @var Client
     */
    protected $clientModel;

    /**
     * ClientRepository constructor.
     * @param Client $clientModel
     */
    public function __construct(Client $clientModel)
    {
        $this->clientModel = $clientModel;
    }

    /**
     * @param int $perPage
     * @return mixed
     */
    public function paginate($perPage = 10)
    {
        return $this->clientModel->paginate($perPage);
    }
}
