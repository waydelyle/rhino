<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\FileRequest;
use App\Services\ClientService;
use Illuminate\Http\UploadedFile;

/**
 * Class ClientController
 * @package App\Http\Controllers
 */
class ClientController extends Controller
{
    /**
     * @var ClientService
     */
    public $clientService;

    /**
     * ClientController constructor.
     * @param ClientService $clientService
     */
    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $clients = $this->clientService->paginate();

        if (!$clients->count()) {
            return redirect()->back()->withErrors(['Please upload a client csv.']);
        }

        return view(
            'clients.index',
            [
                'clients' => $clients,
            ]
        );
    }

    /**
     * @param FileRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(FileRequest $request)
    {
        Client::truncate();

        /** @var UploadedFile $file */
        $file = $request->file('file');

        $this->clientService->saveFromCsv($file->getRealPath());

        return redirect(
            route('clients.index')
        );
    }
}
