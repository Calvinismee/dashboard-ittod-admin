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
        abort_unless(in_array(auth()->user()->role, ['superadmin', 'panitia'], true), 403);
        
        $query = Team::with(['event', 'members.user']);
        
        if (auth()->user()->role === 'panitia') {
            $query->whereIn('competition_id', auth()->user()->events->pluck('id'));
        }
        
        $teams = $query->get();
        return view('operation.teams.index', compact('teams'));
    }

    // Melihat detail berkas identitas (REQ-08)
    public function show(string $id) {
        abort_unless(in_array(auth()->user()->role, ['superadmin', 'panitia'], true), 403);
        $team = Team::with(['event', 'members.user', 'members.kartu', 'paymentProof'])->findOrFail($id);
        
        if (auth()->user()->role === 'panitia') {
            abort_unless(auth()->user()->events->contains('id', $team->competition_id), 403);
        }
        
        return view('operation.teams.show', compact('team'));
    }

    // Mengubah status verifikasi berkas tim (REQ-08)
    public function updateStatus(Request $request, string $id) {
        abort_unless(in_array(auth()->user()->role, ['superadmin', 'panitia'], true), 403);
        $team = Team::with('members')->findOrFail($id);
        
        if (auth()->user()->role === 'panitia') {
            abort_unless(auth()->user()->events->contains('id', $team->competition_id), 403);
        }

        if ($team->is_document_verified) {
            return back()->with('error', 'Berkas tim yang sudah disetujui tidak dapat diubah statusnya.');
        }
        
        $request->validate([
            'is_document_verified' => 'required|in:0,1',
            'verification_error' => 'required_if:is_document_verified,0|nullable|string',
        ]);

        if ((int) $request->is_document_verified === 1) {
            $membersWithErrors = $team->members
                ->filter(fn (TeamMember $member) => filled($member->verification_error));

            if ($membersWithErrors->isNotEmpty()) {
                return back()
                    ->withErrors([
                        'is_document_verified' => 'Berkas tim belum bisa disetujui karena masih ada catatan kesalahan anggota. Kosongkan catatan anggota yang sudah diperbaiki, lalu setujui kembali.',
                    ])
                    ->withInput();
            }
        }

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
        abort_unless(in_array(auth()->user()->role, ['superadmin', 'panitia'], true), 403);
        $member = TeamMember::where('team_id', $teamId)
            ->where('user_id', $userId)
            ->firstOrFail();

        $team = Team::findOrFail($teamId);

        if (auth()->user()->role === 'panitia') {
            abort_unless(auth()->user()->events->contains('id', $team->competition_id), 403);
        }

        if ($team->is_document_verified) {
            return back()->with('error', 'Berkas tim yang sudah disetujui tidak dapat diubah status anggotanya.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'verification_error' => 'required_if:action,reject|nullable|string|max:1000',
        ]);

        $verificationError = $request->action === 'reject' && filled($request->verification_error)
            ? trim($request->verification_error)
            : null;

        $member->update([
            'verification_error' => $verificationError
        ]);

        if (filled($verificationError) && $team->is_document_verified) {
            $team->update([
                'is_document_verified' => 0,
            ]);
        }

        return back()->with('success', 'Status verifikasi dokumen anggota berhasil diperbarui!');
    }
}
