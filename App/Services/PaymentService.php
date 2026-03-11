<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymentService {
    public function process($data) {
        // Lista de gateways por prioridade
        $gateways = [
            ['name' => 'gateway1', 'url' => 'http://gateways-mock:3001/transactions'],
            ['name' => 'gateway2', 'url' => 'http://gateways-mock:3002/transacoes']
        ];

        foreach ($gateways as $gw) {
            try {
                // Formata os dados (Gateway 2 usa termos em português)
                $payload = $this->formatPayload($gw['name'], $data);
                
                $response = Http::post($gw['url'], $payload);

                if ($response->successful()) {
                    return [
                        'status' => 'success',
                        'gateway' => $gw['name'],
                        'external_id' => $response->json()['id'] ?? null
                    ];
                }
            } catch (\Exception $e) {
                // Se der erro em um, o "continue" pula para o próximo gateway
                continue;
            }
        }
        throw new \Exception("Falha em todos os gateways de pagamento.");
    }

    private function formatPayload($name, $data) {
        if ($name === 'gateway2') {
            return [
                'valor' => $data['amount'],
                'nome' => $data['name'],
                'numeroCartao' => $data['cardNumber'],
                'cvv' => $data['cvv']
            ];
        }
        return $data;
    }
}
