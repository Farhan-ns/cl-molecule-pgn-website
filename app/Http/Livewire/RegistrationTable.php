<?php

namespace App\Http\Livewire;

use App\Jobs\GenerateQrAndSend;
use App\Jobs\SendToWhatsapp;
use App\Models\TwilioLog;
use App\Services\PublicPathService;
use App\Services\TwilioService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Log;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Registration;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegistrationTable extends DataTableComponent
{
    use LivewireAlert;

    protected $model = Registration::class;
    private $twilio;
    private $suAccess;

    public array $bulkActions = [
        'sendQrToPhone' => 'Kirim Kode QR'
    ];

    public function __construct()
    {
        $this->twilio = new TwilioService();
        $this->suAccess = auth()->user()->user_type == 'su';
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setEmptyMessage('Tidak ada data');
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
            Column::make("Nama", "name")
                ->searchable()
                ->sortable()
                ->format(
                    fn($value, $row, Column $column) => Str::title($value)
                ),
            Column::make("Nomor HP", "phone")
                ->searchable()
                ->sortable(),
            Column::make("Email", "email")
                ->searchable()
                ->sortable(),
            Column::make("Jabatan", "office")
                ->searchable()
                ->sortable(),
            Column::make("Perusahaan", "company_name")
                ->searchable()
                ->sortable(),
            Column::make("Domisili Perusahaan", "company_address")
                ->searchable()
                ->sortable(),
            Column::make("Tanggal", "created_at")
                ->sortable()
                ->format(
                    fn($value, $row, Column $column) => $row->created_at->isoFormat('D MMMM Y')
                ),
            Column::make("Akan Hadir", "additional_info")
                ->sortable()
                ->format(
                    fn($value, $row, Column $column) => $row->getWillAttendAttribute() ? 'Ya' :  'Tidak'
                ),
            BooleanColumn::make('Telah Scan', 'has_attended'),
            Column::make("Terakhir di Blast", "last_blasted")
                ->sortable()
                ->format(
                    fn($value, $row, Column $column) => empty($value) ? '' : Carbon::parse($value)->diffForHumans()
                ),
            ButtonGroupColumn::make('Actions')
                ->attributes(function ($row) {
                    return [
                        'class' => 'space-x-2'
                    ];
                })
                ->buttons([
                    LinkColumn::make('QR')
                        ->title(fn($row) => 'QR')
                        ->location(fn($row) => route('admin.registration.downloadQr', $row->id))
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-primary mb-1',
                            ];
                        }),
                ])->hideIf($this->suAccess), // show only if not super admin
            ButtonGroupColumn::make('Actions')
                ->attributes(function ($row) {
                    return [
                        'class' => 'space-x-2'
                    ];
                })
                ->buttons([
                    LinkColumn::make('Edit')
                        ->title(fn($row) => 'Edit')
                        ->location(fn($row) => route('admin.registration.edit', $row->id))
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-warning mb-1',
                            ];
                        }),
                    // LinkColumn::make('Detail')
                    //     ->title(fn ($row) => 'Detail')
                    //     ->location(fn ($row) => route('admin.registration.show', $row->id))
                    //     ->attributes(function ($row) {
                    //         return [
                    //             'class' => 'btn btn-primary mb-1',
                    //             'target' => '_blank'
                    //         ];
                    //     }),
                    // LinkColumn::make('ID Card')
                    //     ->title(fn ($row) => 'ID Card')
                    //     ->location(fn ($row) => route('admin.registration.nametag', $row->id))
                    //     ->attributes(function ($row) {
                    //         return [
                    //             'class' => 'btn btn-primary mb-1',
                    //             'target' => '_blank'
                    //         ];
                    //     }),
                    LinkColumn::make('QR')
                        ->title(fn($row) => 'QR')
                        ->location(fn($row) => route('admin.registration.downloadQr', $row->id))
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-primary mb-1',
                            ];
                        }),

                    LinkColumn::make('Hapus')
                        ->title(fn($row) => 'Hapus')
                        ->location(fn($row) => route('admin.registration.destroy', $row->id))
                        ->attributes(function ($row) {
                            return [
                                'class' => 'btn btn-danger mb-1',
                            ];
                        }),
                ])->hideIf(!$this->suAccess), // show only if super admin
        ];
    }

    public function sendQrToPhone()
    {
        if (!$this->suAccess) {
            $this->alert('error', 'Anda memerlukan akses super-admin', [
                'position' => 'center',
                'toast' => false
            ]);

            $this->clearSelected();
            return;
        }

        $ids = collect($this->getSelected());

        $pathService = new PublicPathService();

        foreach ($ids as $id) {
            $registration = Registration::find($id);

            $registration->last_blasted = now();
            $registration->save();

            GenerateQrAndSend::dispatch($registration);
        }

        $this->alert('success', 'kode QR akan segera dikirim ke Whatsapp, proses ini dapat memakan waktu beberapa menit.', [
            'position' => 'center',
            'toast' => false
        ]);

        $this->clearSelected();
    }
}
