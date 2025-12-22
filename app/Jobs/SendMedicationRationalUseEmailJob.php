<?php

namespace App\Jobs;

use App\Mail\MedicationRationalUseMail;
use App\Models\Quality\Records\Products\DispenseRecord;
use App\Models\Quality\Records\Products\MailLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMedicationRationalUseEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public DispenseRecord $record
    ) {
        //$this->dispenseRecordId = $dispenseRecordId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $record = $this->record;
        if (! $record) return;

        $medications = config('medications');
        $med = $medications[$record->medication_key] ?? null;
        if (! $med) return; // opcional: loguear

        $teamId = $record->team_id;
        $teamName = null;
        $teamPhone = null;
        $teamEmail = null;
        $teamAddress = null;
        try {
            $team = null;
            if ($teamId) {
                $team = \App\Models\Team::find($teamId); // si tienes modelo Team
                $teamName = $team?->name;
                $teamPhone = $team?->phone;
                $teamEmail = $team?->email;
                $teamAddress = $team?->address;
            }
        } catch (\Throwable $e) {
            // ignore team resolution
        }

        Mail::to($record->client_email)
            ->send(new MedicationRationalUseMail(
                $med,
                $record->client_name,
                $teamName,
                $record->id,
                null, // payload
                $record->client_email,
                $teamPhone,
                $teamAddress,
                $teamEmail,
            ));

        // Guardar MailLog
        MailLog::create([
            'team_id' => $teamId,
            'dispense_record_id' => $record->id,
            'email' => $record->client_email,
            'subject' => $med['name'] . ' — Información sobre uso racional',
            'medication_key' => $record->medication_key,
        ]);
    }
}
