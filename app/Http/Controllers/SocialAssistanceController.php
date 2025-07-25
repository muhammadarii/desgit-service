<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SocialAssistanceStoreRequest;
use App\Http\Requests\SocialAssistanceUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\SocialAssistanceResource;
use App\Interface\SocialAssistanceRepositoryInterface;
use Illuminate\Http\Request;

class SocialAssistanceController extends Controller
{
    private SocialAssistanceRepositoryInterface $socialAssistanceRepository;

    public function __construct(SocialAssistanceRepositoryInterface $socialAssistanceRepository)
    {
        $this->socialAssistanceRepository = $socialAssistanceRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $socialAssistanceRepository = $this->socialAssistanceRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, 'Data Bantuan Sosial Berhasil Diambil', SocialAssistanceResource::collection($socialAssistanceRepository),200);
        }catch(\Exception $e){
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
            $socialAssistanceRepository = $this->socialAssistanceRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(true, 'Data Bantuan Sosial Berhasil Diambil', PaginateResource::make($socialAssistanceRepository, SocialAssistanceResource::class),200);
        }catch(\Exception $e){
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SocialAssistanceStoreRequest $request)
    {
        $request = $request->validated();

        try{
            $socialAssistance = $this->socialAssistanceRepository->create($request);

            return ResponseHelper::jsonResponse(true, 'Data Bantuan Sosial Berhasil Ditambahkan', new SocialAssistanceResource($socialAssistance), 201);   
        }catch(\Exception $e){
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $socialAssistance = $this->socialAssistanceRepository->getById($id);

            if(!$socialAssistance){
                return ResponseHelper::jsonResponse(false, 'Data Bantuan Sosial Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Bantuan Sosial Berhasil Diambil', new SocialAssistanceResource($socialAssistance), 200);   
        }catch(\Exception $e){
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SocialAssistanceUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try{
            $socialAssistance = $this->socialAssistanceRepository->getById($id);

              if(!$socialAssistance){
                return ResponseHelper::jsonResponse(false, 'Data Bantuan Sosial Tidak Ditemukan', null, 404);
            }

            $socialAssistance = $this->socialAssistanceRepository->update($id, $request);

            return ResponseHelper::jsonResponse(true, 'Data Bantuan Sosial Berhasil Diupdate', new SocialAssistanceResource($socialAssistance), 200);
        }catch(\Exception $e){
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $socialAssistance = $this->socialAssistanceRepository->getById($id);

            if(!$socialAssistance){
                return ResponseHelper::jsonResponse(false, 'Data Bantuan Sosial Tidak Ditemukan', null, 404);
            }

            $this->socialAssistanceRepository->destroy($id);

            return ResponseHelper::jsonResponse(true, 'Data Bantuan Sosial Berhasil Dihapus', null, 200);
        }catch(\Exception $e){
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
