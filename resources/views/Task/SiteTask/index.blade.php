@extends('Partials.main')

@section('title', 'Project WBS')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Project WBS <b>{{ $project->nama_project }}</b></h4>
        </div>

        <div class="card-body">
            <a href="{{ route('task-to-do.create',[$project->kode_project]) }}" class="btn btn-primary mb-3">Tambah WBS</a>
            {{-- <a href="{{ route('wbs.export') }}" class="btn btn-success mb-3">Export WBS</a> --}}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Kode Task</th>
                            <th>Nama Task</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Durasi</th>
                            <th>Progres</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
    @foreach ($groupedTasks as $group)
        {{-- Baris Parent --}}
        <tr style="font-weight: bold; background-color: #f9f9f9;">
            <td>{{ $group['parent']->kode_task }}</td>
            <td>{{ $group['parent']->nama_task }}</td>
            <td>{{ $group['parent']->start_date ? \Carbon\Carbon::parse($group['parent']->start_date)->translatedFormat('d F Y') : '-' }}</td>
            <td>{{ $group['parent']->end_date ? \Carbon\Carbon::parse($group['parent']->end_date)->translatedFormat('d F Y') : '-' }}</td>
            <td>
                {{ $group['parent']->start_date && $group['parent']->end_date
                    ? \Carbon\Carbon::parse($group['parent']->start_date)->diffInDays(\Carbon\Carbon::parse($group['parent']->end_date)) . ' Hari'
                    : '-'
                }}
            </td>
            <td>
                @if($group['parent']->progress >= 100)
                    <span class="badge bg-success">Selesai</span>
                @else
                    <span class="badge bg-warning">{{ $group['parent']->progress }}%</span>
                @endif
            </td>
            <td>
                <a href="{{ route('task-to-do.edit', [$project->kode_project, $group['parent']->kode_task]) }}" class="btn btn-sm btn-warning">Edit</a>
            </td>
        </tr>

        {{-- Baris Anak (Child Tasks) --}}
        @foreach ($group['children'] as $task)
            <tr>
                <td>└── {{ $task->kode_task }}</td>
                <td>{{ $task->nama_task }}</td>
                <td>{{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->translatedFormat('d F Y') : '-' }}</td>
                <td>{{ $task->end_date ? \Carbon\Carbon::parse($task->end_date)->translatedFormat('d F Y') : '-' }}</td>
                <td>
                    {{ $task->start_date && $task->end_date
                        ? \Carbon\Carbon::parse($task->start_date)->diffInDays(\Carbon\Carbon::parse($task->end_date)) . ' Hari'
                        : '-'
                    }}
                </td>
                <td>
                    @if($task->progress >= 100)
                        <span class="badge bg-success">Selesai</span>
                    @else
                        <span class="badge bg-warning">{{ $task->progress }}%</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('task-to-do.edit', [$project->kode_project, $task->kode_task]) }}" class="btn btn-sm btn-warning">Edit</a>
                </td>
            </tr>
        @endforeach
    @endforeach
</tbody>

                </table>
            </div>
        </div>
    </div>
</div>
@endsection
