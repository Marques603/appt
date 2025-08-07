<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Note;
use App\Models\CostCenter;
use App\Models\Menu;
use App\Models\User;
use App\Policies\NotePolicy;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');
        $userPositions = $user->positions->pluck('name')->toArray();
        $userPositionIds = $user->positions->pluck('id');

        $query = Note::with(['costCenter', 'approvalPosition', 'createdBy']);

        if (in_array('Fiscal de notas', $userPositions)) {
            $query->where('status', 'Aprovada pelo Diretor');
        } elseif (in_array('ANALISTA FINANCEIRO', $userPositions)) {
            $query->where('status', 'Lançada no Financeiro');
        } else {
            $query->whereIn('approval_position_id', $userPositionIds);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('provider', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('note_number', 'like', "%{$search}%");
            });
        }

        $notes = $query->orderBy('created_by', 'desc')->paginate(10);

        return view('notes.index', compact('notes'));
    }

    public function create()
    {
        $costCenters = CostCenter::all(); // ou ->orderBy('name')->get()
        return view('notes.create', compact('costCenters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider'             => 'required|string|max:255',
            'note_number'          => 'required|string|max:50|unique:notes,note_number',
            'description'          => 'required|string|max:255',
            'valor'                => 'required|numeric|min:0',
            'payday'               => 'required|date',
            'approval_position_id' => 'required|exists:position,id',
            'pdf_file'             => 'required|file|mimes:pdf|max:10240', // 10MB
            'cost_center_id'       => 'required|exists:cost_center,id',
        ]);

        if (!$request->hasFile('pdf_file')) {
            return back()->withErrors(['pdf_file' => 'Arquivo PDF é obrigatório.'])->withInput();
        }

        $pdfPath = $request->file('pdf_file')->store('notes_pdfs', 'public');

        $note = Note::create([
            'provider'             => $validated['provider'],
            'note_number'          => $validated['note_number'],
            'description'          => $validated['description'],
            'valor'                => $validated['valor'],
            'payday'               => $validated['payday'],
            'approval_position_id' => $validated['approval_position_id'],
            'cost_center_id'       => $validated['cost_center_id'],
            'pdf_file'             => $pdfPath,
            'created_by'           => auth()->id(),
        ]);

        return redirect()->route('notes.index')->with('success', 'Nota cadastrada e enviada para aprovação.');
    }

    public function show(Note $note)
    {
        return view('notes.show', compact('note'));
    }

    public function edit(Note $note)
    {
        Gate::authorize('edit', Note::class);
        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, Note $note)
    {
        $validated = $request->validate([
            'provider'    => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'valor'       => 'required|numeric|min:0',
            'payday'      => 'required|date',
            'cost_center' => 'nullable|string|max:255',
        ]);

        $note->update($validated);

        return redirect()->route('notes.index')->with('success', 'Nota atualizada com sucesso.');
    }

    public function destroy(Note $note)
    {
        $note->delete();
        return redirect()->route('notes.index')->with('success', 'Nota excluída com sucesso.');
    }

    public function aprovar(Note $note)
    {
        if ($note->status !== 'Aguardando Aprovação') return back();

        $note->status = 'Aprovada pelo Diretor';
        $note->save();

        return back()->with('success', 'Nota aprovada!');
    }

    public function lancar(Note $note)
    {
        if ($note->status !== 'Aprovada pelo Diretor') return back();

        $note->status = 'Lançada no Financeiro';
        $note->save();

        return back()->with('success', 'Nota lançada no financeiro!');
    }

    public function pagar(Note $note)
    {
        if ($note->status !== 'Lançada no Financeiro') return back();

        $note->status = 'Paga';
        $note->save();

        return back()->with('success', 'Nota marcada como paga!');
    }

    public function index2(Request $request)
    {
        $user = auth()->user();
        $userPositionIds = $user->positions->pluck('id')->toArray();

        $cargosComAcessoTotal = [
            'DIRETOR INDUSTRIAL',
            'DIRETOR COMERCIAL E MKT',
            'DIRETOR ADM. FINANCEIRO',
            'FISCAL DE NOTAS',
            'ANALISTA FINANCEIRO',
        ];

        $temAcessoCompleto = $user->positions()->whereIn('name', $cargosComAcessoTotal)->exists();

        $query = Note::with(['costCenter', 'approvalPosition']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('provider', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('diretoria')) {
            $query->whereHas('approvalPosition', function ($q) use ($request) {
                $q->where('name', $request->diretoria);
            });
        }

        if (!$temAcessoCompleto && !empty($userPositionIds)) {
            $query->whereIn('approval_position_id', $userPositionIds);
        }

        $notes = $query->latest()->paginate(10);

        return view('notes.index2', compact('notes'));
    }

    public function index3(Request $request)
    {
        $query = Note::with(['costCenter'])->where('created_by', auth()->id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('provider', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $notes = $query->latest()->paginate(10);

        return view('notes.index3', compact('notes'));
    }

    public function exportCsv()
    {
        $notes = Note::all();
        $filename = 'notes_' . date('Ymd') . '.csv';

        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['ID', 'Provider', 'Description', 'Value', 'Payday', 'Status']);

        foreach ($notes as $note) {
            fputcsv($handle, [
                $note->id,
                $note->provider,
                $note->description,
                $note->valor,
                $note->payday->format('Y-m-d'),
                $note->status,
            ]);
        }

        fclose($handle);

        return response()->stream(
            function () use ($handle) {
                fclose($handle);
            },
            200,
            [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ]
        );
    }

    public function exportPdf()
    {
        $notes = Note::all();
        $pdf = \PDF::loadView('notes.pdf', compact('notes'));

        return $pdf->download('notes_' . date('Ymd') . '.pdf');
    }
}
