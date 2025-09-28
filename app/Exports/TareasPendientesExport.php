<?php

namespace App\Exports;

use App\Models\Tarea;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TareasPendientesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Tarea::with('usuario')
            ->where('estado', 'pendiente')
            ->get();
    }

    public function map($tarea): array
    {
        return [
            $tarea->titulo,
            $tarea->descripcion,
            $tarea->usuario ? $tarea->usuario->nombre : '',
            $tarea->fecha_vencimiento,
        ];
    }

    public function headings(): array
    {
        return [
            'Título',
            'Descripción',
            'Usuario asignado',
            'Fecha de vencimiento',
        ];
    }
}
