<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function Showlogin()
    {
        if (Auth::check()) {
            return redirect()->route($this->dashboardRouteFor(Auth::user()->role));
        }

        return view('login');
    }

    private function dashboardRouteFor(?string $role): string
    {
        $role = trim(strtolower($role ?? ''));

        if ($role === 'admin' || $role === 'manager') {
            return 'dashboard';
        }

        if ($role === 'conseiller') {
            return 'conseiller.dashboard';
        }

        return 'login.page';
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
                    'signature' => $ev->signature,
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

    public function conseillerDashboard()
    {
        $user = Auth::user();
        
        // Get conseiller's own evaluations
        $totalEvaluations = $user->evaluations()->count();
        $evaluationsThisMonth = $user->evaluations()->whereYear('date', now()->year)->whereMonth('date', now()->month)->count();
        $evaluationsThisWeek = $user->evaluations()->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $averageScore = $user->evaluations()->whereNotNull('score')->avg('score');
        $averageScore = $averageScore ? round($averageScore, 1) : 0;

        $incomingCount = $user->evaluations()->where('type', 'entrant')->count();
        $outgoingCount = $user->evaluations()->where('type', 'sortant')->count();
        $incomingPercent = $totalEvaluations ? round($incomingCount / $totalEvaluations * 100) : 0;
        $outgoingPercent = $totalEvaluations ? round($outgoingCount / $totalEvaluations * 100) : 0;

        $koCount = $user->evaluations()->where('has_ko', true)->count();
        $koPercent = $totalEvaluations ? round($koCount / $totalEvaluations * 100, 1) : 0;

        $signedCount = $user->evaluations()->where('status', 'signed')->count();
        $pendingSigCount = $user->evaluations()->where('status', 'completed')->count();

        $recentEvaluations = $user->evaluations()
            ->with('manager')
            ->orderByDesc('created_at')
            ->take(8)
            ->get()
            ->map(function ($ev) {
                $score = $ev->score ?? 0;
                $avatar = $score >= 85
                    ? 'linear-gradient(135deg,#F5A623,#F7BC54)'
                    : ($score >= 75
                        ? 'linear-gradient(135deg,#8B0000,#C0152A)'
                        : 'linear-gradient(135deg,#8B0000,#6B3040)');

                $managerName = $ev->manager->name ?? 'Unknown';
                $mgrInit = collect(explode(' ', $managerName))
                    ->filter()
                    ->map(fn ($part) => mb_substr($part, 0, 1))
                    ->join('');

                return [
                    'id' => 'EV-'.$ev->id,
                    'type' => $ev->type === 'entrant' ? 'incoming' : 'outgoing',
                    'date' => optional($ev->date)->format('j M Y, H:i') ?? $ev->created_at->format('j M Y, H:i'),
                    'score' => $score,
                    'ko' => $ev->has_ko,
                    'status' => $ev->status,
                    'audio' => $ev->audio,
                    'signature' => $ev->signature,
                    'avatar' => $avatar,
                    'manager' => $managerName,
                    'mgr_init' => $mgrInit,
                ];
            });

        return view('conseiller-dashboard', compact(
            'totalEvaluations',
            'evaluationsThisMonth',
            'evaluationsThisWeek',
            'averageScore',
            'incomingPercent',
            'outgoingPercent',
            'koCount',
            'koPercent',
            'signedCount',
            'pendingSigCount',
            'recentEvaluations'
        ));
    }

    public function settings()
    {
        $user = Auth::user();

        return view('settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user->update([
            'name' => $validated['name'],
        ]);

        return redirect()->route('settings')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return redirect()->route('settings')
                ->withErrors(['current_password' => 'Current password is incorrect.'])
                ->withInput();
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return redirect()->route('settings')->with('success_password', 'Password updated successfully.');
    }

    public function users()
    {
        $users = User::all();
        return view('users', compact('users'));
    }

    public function export(Request $request)
    {
        if (! $request->has('format')) {
            return view('export');
        }

        $format = strtolower($request->query('format'));
        $evaluations = Evaluation::with('conseiller')->orderByDesc('created_at')->get();

        if ($format === 'excel' || $format === 'xls') {
            return $this->downloadExcel($evaluations);
        }

        if ($format === 'pdf') {
            return $this->downloadPdf($evaluations);
        }

        return $this->downloadCsv($evaluations);
    }

    protected function downloadCsv($evaluations)
    {
        $filename = 'evaluations-export-'.now()->format('YmdHis').'.csv';
        $rows = $this->prepareExportRows($evaluations);

        return response()->streamDownload(function () use ($rows) {
            $output = fopen('php://output', 'w');
            fputs($output, "\xEF\xBB\xBF");
            if (! empty($rows)) {
                fputs($output, "sep=,\r\n");
                fputcsv($output, array_keys($rows[0]));
                foreach ($rows as $row) {
                    fputcsv($output, array_values($row));
                }
            }
            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    protected function downloadExcel($evaluations)
    {
        $filename = 'evaluations-export-'.now()->format('YmdHis').'.xls';
        $rows = $this->prepareExportRows($evaluations);

        return response()->streamDownload(function () use ($rows) {
            $output = fopen('php://output', 'w');
            fputs($output, "\xEF\xBB\xBF");
            if (! empty($rows)) {
                fputs($output, "sep=,\r\n");
                fputcsv($output, array_keys($rows[0]));
                foreach ($rows as $row) {
                    fputcsv($output, array_values($row));
                }
            }
            fclose($output);
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    protected function downloadPdf($evaluations)
    {
        $filename = 'evaluations-export-'.now()->format('YmdHis').'.pdf';
        $rows = $this->prepareExportRows($evaluations);
        $pdf = $this->buildPdf($rows);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    protected function prepareExportRows($evaluations)
    {
        return $evaluations->map(function ($ev) {
            return [
                'Evaluation ID' => 'EV-'.$ev->id,
                'Conseiller' => $ev->conseiller->name ?? 'Unknown',
                'Call Type' => $ev->type === 'entrant' ? 'Incoming' : 'Outgoing',
                'Date' => $this->formatExportDate($ev->date ?? $ev->created_at),
                'Reference' => $ev->reference ?? '-',
                'Score' => $ev->score ?? 0,
                'KO' => $ev->has_ko ? 'Yes' : 'No',
                'Status' => ucfirst($ev->status),
                'Audio' => $ev->audio ? asset('storage/'.$ev->audio) : '-',
                'Signature' => $ev->signature ? 'Yes' : 'No',
            ];
        })->toArray();
    }

    protected function formatExportDate($value)
    {
        if (! $value) {
            return '-';
        }

        try {
            $date = $value instanceof Carbon ? $value : Carbon::parse($value);
            return "'".$date->format('Y-m-d H:i');
        } catch (\Throwable $e) {
            return "'".trim((string) $value);
        }
    }

    protected function buildPdf(array $rows)
    {
        $lines = [];
        $lines[] = $this->escapePdfText('KiteaCall Evaluations Export');
        $lines[] = $this->escapePdfText('Generated: '.now()->format('Y-m-d H:i'));
        $lines[] = $this->escapePdfText(str_repeat('-', 90));

        $header = array_keys($rows[0] ?? []);
        $lines[] = $this->escapePdfText(implode(' | ', $header));
        $lines[] = $this->escapePdfText(str_repeat('-', 90));

        $maxRows = 30;
        $count = 0;
        foreach ($rows as $row) {
            if ($count >= $maxRows) {
                $lines[] = $this->escapePdfText('...output truncated for preview, use CSV/Excel to download the full dataset.');
                break;
            }
            $values = array_values($row);
            $lines[] = $this->escapePdfText(implode(' | ', $values));
            $count++;
        }

        $content = "BT /F1 9 Tf 40 770 Td (".$lines[0].") Tj ";
        $content .= "0 -14 Td (".$lines[1].") Tj ";
        $content .= "0 -18 Td (".$lines[2].") Tj ";
        foreach (array_slice($lines, 3) as $line) {
            $content .= "0 -12 Td (".$line.") Tj ";
        }
        $content .= ' ET';

        $pdfObjects = [];
        $pdfObjects[] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $pdfObjects[] = "2 0 obj\n<< /Type /Pages /Count 1 /Kids [3 0 R] >>\nendobj\n";
        $pdfObjects[] = "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>\nendobj\n";
        $pdfObjects[] = "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Courier >>\nendobj\n";
        $streamLength = strlen($content);
        $pdfObjects[] = "5 0 obj\n<< /Length $streamLength >>\nstream\n$content\nendstream\nendobj\n";

        $pdf = "%PDF-1.4\r\n";
        $offsets = [];
        foreach ($pdfObjects as $obj) {
            $offsets[] = strlen($pdf);
            $pdf .= $obj;
        }

        $xrefStart = strlen($pdf);
        $xref = "xref\r\n0 ".(count($pdfObjects) + 1)."\r\n";
        $xref .= "0000000000 65535 f \r\n";
        foreach ($offsets as $offset) {
            $xref .= sprintf('%010d 00000 n \r\n', $offset);
        }

        $pdf .= $xref;
        $pdf .= "trailer\r\n<< /Size ".(count($pdfObjects) + 1)." /Root 1 0 R >>\r\n";
        $pdf .= "startxref\r\n".$xrefStart."\r\n%%EOF";

        return $pdf;
    }

    protected function escapePdfText(string $text): string
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $text);
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
            'date' => 'required|date_format:Y-m-d\TH:i',
            'reference' => 'nullable|string|max:255',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg,m4a,aac,wma,flac|max:10240',
            'signature' => 'nullable|string|max:200000',
            'score' => 'nullable|numeric|min:0|max:100',
            'has_ko' => 'nullable|boolean',
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
            'signature' => $validated['signature'] ?? null,
            'score' => $validated['score'] ? (int)round($validated['score']) : null,
            'has_ko' => (bool) $validated['has_ko'],
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
        
        // Determine redirect target based on role
        $route = $this->dashboardRouteFor($user->role);
        $redirectUrl = $route === 'login.page'
            ? route('login.page')
            : route($route);

        if ($request->wantsJson()) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'redirect_url' => $redirectUrl,
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ]);
        }

        if ($route === 'login.page') {
            Auth::logout();
            return redirect()->back()->withInput($request->only('email'))->with('error', 'Your account role is not recognized.');
        }

        return redirect()->route($route);
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
     
     return redirect()->back()->withInput($request->only('email'))->with('error', 'Invalid email or password');
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