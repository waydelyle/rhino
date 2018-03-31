<?php

namespace App\Services;

use App\Client;
use App\Repositories\ClientRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Class ClientService
 * @package App\Services
 */
class ClientService
{
    /**
     * @var CsvService
     */
    protected $csvService;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * ClientService constructor.
     * @param CsvService $csvService
     * @param ClientRepository $clientRepository
     */
    public function __construct(CsvService $csvService, ClientRepository $clientRepository)
    {
        $this->csvService = $csvService;
        $this->clientRepository = $clientRepository;
    }

    /**
     * @param Collection $data
     * @return array
     */
    public function mapToArray(Collection $data): array
    {
        return $data->map(function ($client) {
            return [
                'name' => $client[0],
                'publication' => $client[1],
                'phone_numbers' => $this->generatePhoneNumbers($client[2]),
                'emails' => $this->generateEmailAddresses($client[3]),
                'join_date' => $this->generateJoinDate($client[6]),
            ];
        })
        ->forget([0, 1])
        ->filter(function ($client) {
            return !in_array(
                strtolower($client['name']),
                [
                    'cpt',
                    'gauteng',
                    'kzn',
                ]
            );
        })
        ->toArray();
    }

    /**
     * @param string $path
     * @return array
     */
    public function collectFromCsv(string $path): array
    {
        return $this->mapToArray(
            $this->csvService->toCollection($path)
        );
    }

    /**
     * @param string $path
     * @return bool
     */
    public function saveFromCsv(string $path): bool
    {
        $clients = $this->collectFromCsv($path);

        return $this->insert($clients);
    }

    /**
     * @param array $clients
     * @return bool
     */
    public function insert(array $clients): bool
    {
        return Client::insert(
            $clients
        );
    }

    /**
     * @param string $date
     * @return string
     */
    protected function generateJoinDate(string $date): string
    {
        if ($date === '') {
            return '';
        }

        try {
            $parsedDate = Carbon::parse($date);
        } catch (\Exception $exception) {
            return '';
        }

        return $parsedDate->toDateString();
    }

    /**
     * @param string $string
     * @return string
     */
    protected function generatePhoneNumbers(string $string): string
    {
        if ($string === '') {
            return json_encode([]);
        }

        $numbers = $this->stripFromString(
            [
                '-',
                ',',
                ' ',
                '+',
            ],
            $string
        );

        if (!is_numeric((int) $numbers)) {
            return json_encode([]);
        }

        $numbers = explode('/', $numbers);

        return Collection::make($numbers)->toJson();
    }

    /**
     * @param string $string
     * @return string
     */
    protected function generateEmailAddresses(string $string): string
    {
        if ($string === '') {
            return json_encode([]);
        }

        $emails = $this->stripFromString(
            [
                'n/a',
                ' ',
            ],
            $string
        );

        if ($string === '' || strpos($string, '@') === false) {
            return json_encode([]);
        }

        $emails = explode(';', $emails);

        return Collection::make($emails)
            ->map(function ($email) {
                $email = filter_var($email, FILTER_SANITIZE_EMAIL);

                return [
                    'email' => $email,
                    'valid' => filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false,
                ];
            })->toJson();
    }

    /**
     * @param array $characters
     * @param string $string
     * @return string
     */
    protected function stripFromString(array $characters, string $string): string
    {
        foreach ($characters as $character) {
            $string = str_replace($character, '', $string);
        }

        return $string;
    }

    /**
     * @return mixed
     */
    public function paginate()
    {
        return $this->clientRepository->paginate();
    }
}
