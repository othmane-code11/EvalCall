<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function Showlogin()
    {
        return view('login');
    }
    public function dashboard()
    {
        $totalEvaluations = Evaluation::count();
        $evaluationsThisMonth = Evaluation::whereYear('date', now()->year)->whereMonth('date', now()->month)->count();
        $evaluationsThisWeek = Evaluation::whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $averageScore = Evaluation::whereNotNull('score')->avg('score');
        $averageScore = $averageScore ? round($averageScore, 1) : 0;

        $totalConseillers = User::where('role', 'conseiller')->count();
        $totalTeam = $totalConseillers;

        $incomingCount = Evaluation::where('type', 'entrant')->count();
        $outgoingCount = Evaluation::where('type', 'sortant')->count();
        $incomingPercent = $totalEvaluations ? round($incomingCount / $totalEvaluations * 100) : 0;
        $outgoingPercent = $totalEvaluations ? round($outgoingCount / $totalEvaluations * 100) : 0;

        $koCount = Evaluation::where('has_ko', true)->count();
        $koPercent = $totalEvaluations ? round($koCount / $totalEvaluations * 100, 1) : 0;

        $conseillers = User::where('role', 'conseiller')
            ->withCount('evaluations')
            ->withAvg('evaluations', 'score')
            ->get()
            ->map(function ($cons) {
                $score = $cons->evaluations_avg_score ? (int) round($cons->evaluations_avg_score) : 0;
                $initials = collect(explode(' ', $cons->name))
                    ->filter()
                    ->map(fn ($part) => mb_substr($part, 0, 1))
                    ->join('');
                $color = $score >= 85
                    ? 'linear-gradient(135deg,#F5A623,#F7BC54)'
                    : ($score >= 75
                        ? 'linear-gradient(135deg,#8B0000,#C0152A)'
                        : 'linear-gradient(135deg,#C0152A,#6B3040)');

                return [
                    'name' => $cons->name,
                    'initials' => $initials,
                    'score' => $score,
                    'evals' => $cons->evaluations_count,
                    'color' => $color,
                ];
            });

        $topPerformer = $conseillers->sortByDesc('score')->first() ?? ['name' => '-', 'score' => 0, 'evals' => 0];
        $lowPerformer = $conseillers->sortBy('score')->first() ?? ['name' => '-', 'score' => 0, 'evals' => 0];

        $evaluations = Evaluation::with('conseiller')
            ->orderByDesc('created_at')
            ->take(8)
            ->get()
            ->map(function ($ev) {
                $name = $ev->conseiller->name ?? 'Unknown';
                $initials = collect(explode(' ', $name))
                    ->filter()
                    ->map(fn ($part) => mb_substr($part, 0, 1))
                    ->join('');
                $score = $ev->score ?? 0;
                $avatar = $score >= 85
                    ? 'linear-gradient(135deg,#F5A623,#F7BC54)'
                    : ($score >= 75
                        ? 'linear-gradient(135deg,#8B0000,#C0152A)'
                        : 'linear-gradient(135deg,#8B0000,#6B3040)');

                return [
                    'id' => 'EV-'.$ev->id,
                    'name' => $name,
                    'initials' => $initials,
                    'avatar' => $avatar,
                    'type' => $ev->type === 'entrant' ? 'incoming' : 'outgoing',
                    'date' => optional($ev->date)->format('j M Y, H:i') ?? $ev->created_at->format('j M Y, H:i'),
                    'score' => $score,
                    'ko' => $ev->has_ko,
                    'status' => $ev->status,
                    'audio' => $ev->audio,
                ];
            });

        $latestEvaluation = Evaluation::with('conseiller')->latest('created_at')->first();
        $pendingSignatures = Evaluation::where('status', 'completed')
            ->where('date', '<=', now()->subDays(5))
            ->count();
        $staleDrafts = Evaluation::where('status', 'draft')
            ->where('date', '<=', now()->subDays(7))
            ->count();

        $alerts = [
            [
                'type' => 'urgent',
                'title' => 'KO detected',
                'msg' => $koCount
                    ? 'Latest KO evaluation is '.$latestEvaluation?->conseiller->name.' on '.optional($latestEvaluation?->date)->format('j M Y').'.'
                    : 'No KO records have been flagged yet.',
                'time' => $latestEvaluation?->created_at->diffForHumans() ?? 'just now',
            ],
            [
                'type' => 'warning',
                'title' => 'Awaiting signature',
                'msg' => $pendingSignatures.' evaluations are completed but pending signature for over 5 days.',
                'time' => 'Today',
            ],
            [
                'type' => 'urgent',
                'title' => 'Stale draft',
                'msg' => $staleDrafts.' drafts have been inactive for over 7 days.',
                'time' => 'Today',
            ],
        ];

        return view('dashboard', compact(
            'totalEvaluations',
            'evaluationsThisMonth',
            'evaluationsThisWeek',
            'averageScore',
            'totalConseillers',
            'totalTeam',
            'incomingPercent',
            'outgoingPercent',
            'koCount',
            'koPercent',
            'conseillers',
            'topPerformer',
            'lowPerformer',
            'incomingPercent',
            'outgoingPercent',
            'evaluations',
            'alerts'
        ));
    }
    public function users()
    {
        $users = User::all();

        return view('users', compact('users'));
    }
    public function evaluations()
    {
        $conseillers = $this->getConseillers();
        return view('evaluations', compact('conseillers'));
    }

    public function evaluationsCreate()
    {
        $conseillers = $this->getConseillers();
        return view('evaluations', compact('conseillers'));
    }

    protected function getConseillers()
    {
        return User::where('role', 'conseiller')->orderBy('name')->get();
    }

    public function storeEvaluation(Request $request)
    {
        $validated = $request->validate([
            'conseiller_id' => 'required|exists:users,id',
            'type' => 'required|in:entrant,sortant',
            'date' => 'required|date',
            'reference' => 'nullable|string|max:255',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac,wma,flac|max:10240',
            'status' => 'nullable|in:draft,completed,signed',
        ]);

        $audioPath = null;
        if ($request->hasFile('audio')) {
            $audioPath = $request->file('audio')->store('evaluations/audio', 'public');
        }

        Evaluation::create([
            'manager_id' => Auth::id(),
            'conseiller_id' => $validated['conseiller_id'],
            'type' => $validated['type'],
            'date' => $validated['date'],
            'reference' => $validated['reference'] ?? null,
            'audio' => $audioPath,
            'status' => $validated['status'] ?? 'draft',
        ]);

        return redirect()->route('evaluations.create')->with('success', 'Evaluation saved successfully.');
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,manager,conseiller',
        ]);

        $name = $request->first_name . ' ' . $request->last_name;

        User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users')->with('success', 'User created successfully.');
    }


    public function login(Request $request)
    {
      
    // Validate input
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:3',
    ]);

    $user = User::where('email', $request->email)->first();
    
    if ($user && Hash::check($request->password, $user->password)) {
        Auth::login($user);
        
        // Check if it's an AJAX request
        if ($request->wantsJson()) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ]);
        }
        
        return redirect()->route('dashboard');
     }
     
     // Check if it's an AJAX request
     if ($request->wantsJson()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password',
            'errors' => [
                'general' => ['Invalid email or password']
            ]
        ], 401);
     }
     
     return redirect()->route('dashboard')->with('error', 'Invalid email or password');
    }
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users')->with('success', 'User deleted successfully.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,manager,conseiller',
        ]);

        $name = $request->first_name . ' ' . $request->last_name;

        $updateData = [
            'name' => $name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return back()->with('error', 'Invalid email or password');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.page');
    }

    public function activeUser(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $request->user()
        ]);
    }
}