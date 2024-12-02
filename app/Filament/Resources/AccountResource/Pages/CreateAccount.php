<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use App\Models\Installment;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

class CreateAccount extends CreateRecord
{
    protected static string $resource = AccountResource::class;

    protected function afterCreate(): void
    {
        $installments = $this->generateInstallments($this->data);
        $this->saveInstallments($installments);
    }

    protected function saveInstallments($installments)
    {
        foreach ($installments as $installment) {
            Installment::create([
                'account_id' => $this->record->id,
                'installment_number' => $installment['installment_number'],
                'amount' => $installment['amount'],
                'due_date' => $installment['due_date'],
            ]);
        }
    }

    protected function generateInstallments($data)
    {
        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);
        $totalAmount = $data['total_amount']; // Monto total
        $installmentsCount = $data['installments_count']; // Número de cuotas
        $installmentAmount = $data['installments_amount']; // Monto de cada cuota

        if ($installmentsCount <= 0) {
            throw new InvalidArgumentException("El número de cuotas debe ser mayor a cero.");
        }

        // Calcular la diferencia de tiempo entre las cuotas
        $intervalInMonths = $startDate->diffInMonths($endDate) + 1;
        if ($installmentsCount > $intervalInMonths) {
            throw new InvalidArgumentException("No se pueden generar más cuotas que meses entre las fechas dadas.");
        }

        $installments = [];
        $currentDate = $startDate;

        for ($i = 0; $i < $installmentsCount; $i++) {
            // Generar cada cuota
            $installments[] = [
                'due_date' => $currentDate->copy()->toDateString(),
                'amount' => $installmentAmount,
                'status' => 'Pendiente', // Estado inicial de la cuota
                'installment_number' => $i + 1,
            ];

            // Avanzar al siguiente mes
            $currentDate->addMonth();
        }

        return $installments;
    }
}
