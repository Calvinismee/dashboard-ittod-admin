<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTimeline;
use App\Models\Media;
use App\Models\Team;
use App\Models\UserIdentity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'events' => Event::count(),
                'teams' => Team::count(),
                'pendingTransactions' => Team::where('is_verified', false)
                    ->whereNull('verification_error')
                    ->count(),
                'rejectedTransactions' => Team::where('is_verified', false)
                    ->whereNotNull('verification_error')
                    ->count(),
            ],
        ]);
    }

    public function staff(): View
    {
        abort_unless(auth()->user()?->role === 'superadmin', 403);

        return view('admin.staff.index', [
            'staffAccounts' => UserIdentity::with(['user', 'events'])
                ->whereIn('role', ['superadmin', 'admin_keuangan', 'panitia'])
                ->orderBy('role')
                ->orderBy('email')
                ->get(),
            'events' => Event::orderBy('title')->get(),
        ]);
    }

    public function transactions(): View
    {
        $teams = Team::with(['event', 'paymentProof', 'members'])
            ->latest('created_at')
            ->get()
            ->map(function (Team $team) {
                $team->payment_proof_url = $this->mediaUrl($team->paymentProof?->url);

                return $team;
            });

        return view('admin.transactions.index', [
            'teams' => $teams,
        ]);
    }

    public function acceptTransaction(Team $team): RedirectResponse
    {
        $team->update([
            'is_verified' => true,
            'verification_error' => null,
        ]);

        return back()->with('status', "Transaksi {$team->team_name} diterima.");
    }

    public function rejectTransaction(Request $request, Team $team): RedirectResponse
    {
        $validated = $request->validate([
            'verification_error' => ['required', 'string', 'max:1000'],
        ]);

        $team->update([
            'is_verified' => false,
            'verification_error' => $validated['verification_error'],
        ]);

        return back()->with('status', "Transaksi {$team->team_name} ditolak.");
    }

    public function filesParticipants(): View
    {
        return view('admin.files-participants.index', [
            'events' => Event::withCount(['teams', 'participants'])
                ->orderBy('title')
                ->get(),
            'recentFiles' => Media::with('uploader')
                ->whereIn('grouping', ['competition_submission', 'dokum_tahun_lalu', 'twibbon'])
                ->latest('created_at')
                ->limit(12)
                ->get(),
        ]);
    }

    public function timelines(): View
    {
        return view('admin.timelines.index', [
            'events' => Event::with(['timelines' => fn ($query) => $query->orderBy('date')])
                ->where('type', 'competition')
                ->orderBy('title')
                ->get(),
            'timelineCount' => EventTimeline::count(),
        ]);
    }

    private function mediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '/'])) {
            return $path;
        }

        return Storage::url($path);
    }
}
