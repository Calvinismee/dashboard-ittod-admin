<?php

namespace App\Http\Controllers\Operation;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    // Menampilkan daftar semua tim (UC-04)
    public function index() {
        abort_unless(in_array(auth()->user()->role, ['superadmin', 'panitia', 'admin_keuangan']), 403);
        
        $query = Team::with(['event', 'members.user']);
        
        if (auth()->user()->role === 'panitia') {
            $query->whereIn('competition_id', auth()->user()->events->pluck('id'));
        }
        
        $teams = $query->get();
        return view('operation.teams.index', compact('teams'));
    }

    // Melihat detail berkas identitas (REQ-08)
    public function show(string $id) {
        abort_unless(in_array(auth()->user()->role, ['superadmin', 'panitia', 'admin_keuangan']), 403);
        $team = Team::with(['event', 'members.user', 'members.kartu', 'paymentProof'])->findOrFail($id);
        
        if (auth()->user()->role === 'panitia') {
            abort_unless(auth()->user()->events->contains('id', $team->competition_id), 403);
        }
        
        return view('operation.teams.show', compact('team'));
    }

    // Mengubah status verifikasi berkas tim (REQ-08)
    public function updateStatus(Request $request, string $id) {
        abort_unless(in_array(auth()->user()->role, ['superadmin', 'panitia']), 403);
        $team = Team::findOrFail($id);
        
        if (auth()->user()->role === 'panitia') {
            abort_unless(auth()->user()->events->contains('id', $team->competition_id), 403);
        }
        
        $request->validate([
            'is_document_verified' => 'required|in:0,1',
            'verification_error' => 'required_if:is_document_verified,0|nullable|string',
        ]);

        $team->update([
            'is_document_verified' => (int) $request->is_document_verified,
            'verification_error' => $request->is_document_verified == 0 ? $request->verification_error : null
        ]);

        return redirect()
            ->route('operation.teams.index')
            ->with('success', 'Status verifikasi tim berhasil diperbarui!');
    }

    // Mengubah status verifikasi dokumen anggota secara individual
    public function updateMemberStatus(Request $request, string $teamId, string $userId) {
        abort_unless(in_array(auth()->user()->role, ['superadmin', 'panitia']), 403);
        $member = TeamMember::where('team_id', $teamId)
            ->where('user_id', $userId)
            ->firstOrFail();

        if (auth()->user()->role === 'panitia') {
            $team = Team::findOrFail($teamId);
            abort_unless(auth()->user()->events->contains('id', $team->competition_id), 403);
        }

        $request->validate([
            'verification_error' => 'nullable|string',
        ]);

        $member->update([
            'verification_error' => $request->verification_error
        ]);

        return back()->with('success', 'Status verifikasi dokumen anggota berhasil diperbarui!');
    }
}
