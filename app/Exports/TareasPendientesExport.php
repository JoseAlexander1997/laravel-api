namespace App\Exports;

use App\Models\Tarea;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TareasPendientesExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Tarea::with('usuario')
            ->where('estado', 'pendiente')
            ->get()
            ->map(function($tarea) {
                return [
                    'ID' => $tarea->id,
                    'Título' => $tarea->titulo,
                    'Descripción' => $tarea->descripcion,
                    'Usuario' => $tarea->usuario->nombre ?? 'Sin asignar',
                    'Fecha Vencimiento' => $tarea->fecha_vencimiento ?? '-',
                    'Estado' => $tarea->estado,
                ];
            });
    }

    public function headings(): array
    {
        return ['ID', 'Título', 'Descripción', 'Usuario', 'Fecha Vencimiento', 'Estado'];
    }
}
