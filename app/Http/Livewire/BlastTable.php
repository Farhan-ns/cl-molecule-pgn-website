<?php

namespace App\Http\Livewire;

use App\Jobs\GenerateQrAndSend;
use App\Jobs\SendWhatsappInvitation;
use Illuminate\Support\Carbon;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Blast;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BlastTable extends DataTableComponent
{
    use LivewireAlert;

    protected $model = Blast::class;

    public array $bulkActions = [
        'sendInvitation' => 'Kirim Invitation',
        'deleteSelected' => 'Hapus',
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setEmptyMessage('Belum ada data');
        $this->setDebugStatus(false);
        $this->setSearchEnabled();
        $this->setSearchVisibilityEnabled();
        $this->setHideBulkActionsWhenEmptyEnabled();

        Carbon::setLocale('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Phone number", "phone_number")
                ->searchable()
                ->sortable(),
            Column::make("Terakhir di Blast", "last_blasted")
                ->sortable()
                ->format(
                    fn($value, $row, Column $column) => empty($value) ? '' : Carbon::parse($value)->diffForHumans()
                 ),
        ];
    }

    public function sendInvitation()
    {
        $ids = collect($this->getSelected());

        foreach ($ids as $id) {
            $blastCandidate = Blast::find($id);

            SendWhatsappInvitation::dispatch($blastCandidate);
            $blastCandidate->last_blasted = now();
            $blastCandidate->save();
        }

        $this->alert('success', 'Blast Invitation sedang diproses, proses ini dapat memakan beberapa menit.', [
            'position' => 'center',
            'toast' => false
        ]);

        $this->clearSelected();
    }

    public function deleteSelected()
    {
        $ids = collect($this->getSelected());

        Blast::destroy($ids);

        $this->alert('success', 'Berhasil menghapus data terpilih', [
            'position' => 'center',
            'toast' => false
        ]);

        $this->clearSelected();
    }
}
