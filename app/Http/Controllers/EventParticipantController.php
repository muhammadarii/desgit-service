<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EventParticipantStoreRequest;
use App\Http\Requests\EventParticipantUpdateRequest;
use App\Http\Resources\EventParticipantResource;
use App\Http\Resources\PaginateResource;
use App\Interface\EventParticipantRepositoryInterface;
use Illuminate\Http\Request;

class EventParticipantController extends Controller
{
    private EventParticipantRepositoryInterface $eventParticipantRepository;

    public function __construct(EventParticipantRepositoryInterface $eventParticipantRepository)
    {
        $this->eventParticipantRepository = $eventParticipantRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $eventParticipants = $this->eventParticipantRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, 'Data Pendaftar Event Berhasil Diambil', EventParticipantResource::collection($eventParticipants), 200);
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer',
        ]);

        try{
            $eventParticipants = $this->eventParticipantRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(true, 'Data Pendaftar Event Berhasil Diambil', PaginateResource::make($eventParticipants, EventParticipantResource::class), 200);
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventParticipantStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $eventParticipant = $this->eventParticipantRepository->create($request);

            return ResponseHelper::jsonResponse(true, 'Pendaftaran Event Berhasil', new EventParticipantResource($eventParticipant), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $eventParticipant = $this->eventParticipantRepository->getById($id);

            if (!$eventParticipant) {
                return ResponseHelper::jsonResponse(false, 'Pendaftar Event Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Pendaftar Event Berhasil Diambil', new EventParticipantResource($eventParticipant), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventParticipantUpdateRequest $request, string $id)
    {
        $request = $request->validated();

         try {
            $eventParticipant = $this->eventParticipantRepository->getById($id);

            if (!$eventParticipant) {
                return ResponseHelper::jsonResponse(false, 'Pendaftar Event Tidak Ditemukan', null, 404);
            }

            $eventParticipant = $this->eventParticipantRepository->update($id, $request);

            return ResponseHelper::jsonResponse(true, 'Data Pendaftar Event Berhasil Diupdate', new EventParticipantResource($eventParticipant), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
             $eventParticipant = $this->eventParticipantRepository->getById($id);

            if (!$eventParticipant) {
                return ResponseHelper::jsonResponse(false, 'Data Event Tidak Ditemukan', null, 404);
            }

            $this->eventParticipantRepository->delete($id);

            return ResponseHelper::jsonResponse(true, 'Event Berhasil Dihapus', null, 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
