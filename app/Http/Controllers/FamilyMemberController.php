<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\FamilyMemberStoreRequest;
use App\Http\Requests\FamilyMemberUpdateRequest;
use App\Http\Resources\FamilyMemberResource;
use App\Http\Resources\PaginateResource;
use App\Interface\FamilyMemberRepositoryInterface;
use Illuminate\Http\Request;

class FamilyMemberController extends Controller
{
    private FamilyMemberRepositoryInterface $familyMemberRepository;

    public function __construct(FamilyMemberRepositoryInterface $familyMemberRepository)
    {
        $this->familyMemberRepository = $familyMemberRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $familyMembers = $this->familyMemberRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(true, 'Data Anggota Keluarga Berhasil Diambil', FamilyMemberResource::collection($familyMembers), 200);
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
            $familyMembers = $this->familyMemberRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(true, 'Data Anggota Keluarga Berhasil Diambil', PaginateResource::make($familyMembers, FamilyMemberResource::class), 200);
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FamilyMemberStoreRequest $request)
    {
        $request = $request->validated();

        try{
            $familyMember = $this->familyMemberRepository->create($request);

            return ResponseHelper::jsonResponse(true, 'Data Anggota Keluarga Berhasil Ditambahkan', new FamilyMemberResource($familyMember), 201);
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $familyMember = $this->familyMemberRepository->getById($id);

            if(!$familyMember) {
                return ResponseHelper::jsonResponse(false, 'Anggota Keluarga Tidak Ditemukan', null, 404);
            }

            return ResponseHelper::jsonResponse(true, 'Data Anggota Keluarga Berhasil Ditemukan', new FamilyMemberResource($familyMember), 200);
            
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FamilyMemberUpdateRequest $request, string $id)
    {
        $request = $request->validated();

        try{
             $familyMember = $this->familyMemberRepository->getById($id);

            if(!$familyMember) {
                return ResponseHelper::jsonResponse(false, 'Anggota Keluarga Tidak Ditemukan', null, 404);
            }

            $familyMember = $this->familyMemberRepository->update($id, $request);

            return ResponseHelper::jsonResponse(true, 'Data Anggota Keluarga Berhasil Diupdate', new FamilyMemberResource($familyMember), 200);

        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $familyMember = $this->familyMemberRepository->getById($id);

            if(!$familyMember) {
                return ResponseHelper::jsonResponse(false, 'Anggota Keluarga Tidak Ditemukan', null, 404);
            }

            $this->familyMemberRepository->delete($id);

            return ResponseHelper::jsonResponse(true, 'Data Anggota Keluarga Berhasil Dihapus',null, 200);
            
        }catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
