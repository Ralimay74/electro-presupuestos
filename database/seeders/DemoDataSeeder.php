<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Material;
use App\Models\Budget;
use App\Models\BudgetLine;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // === CLIENTES ===
        $clientsData = [
            [
                'name' => 'Constructora Martínez SL',
                'email' => 'info@constructoramartinez.com',
                'phone' => '915 234 567',
                'address' => 'Avda. de la Industria 45, 28001 Madrid',
                'nif_cif' => 'B-85123456',
            ],
            [
                'name' => 'Restaurante El Rincón',
                'email' => 'administracion@elrincon.es',
                'phone' => '630 987 654',
                'address' => 'Calle Mayor 23, 28013 Madrid',
                'nif_cif' => 'B-76234567',
            ],
            [
                'name' => 'Centro Médico SaludPlus',
                'email' => 'compras@saludplus.es',
                'phone' => '917 456 789',
                'address' => 'Plaza de España 12, 28008 Madrid',
                'nif_cif' => 'B-91345678',
            ],
            [
                'name' => 'Nave Industrial LogiTrans',
                'email' => 'mantenimiento@logitrans.com',
                'phone' => '914 567 890',
                'address' => 'Polígono Industrial Norte, Calle 3, Nave 15',
                'nif_cif' => 'B-82456789',
            ],
            [
                'name' => 'Comunidad de Propietarios C/ Sol 45',
                'email' => 'presidente@csol45.com',
                'phone' => '620 111 222',
                'address' => 'Calle del Sol 45, 28013 Madrid',
                'nif_cif' => 'H-28123456',
            ],
        ];

        foreach ($clientsData as $clientData) {
            Client::firstOrCreate(
                ['email' => $clientData['email']],
                $clientData
            );
        }

        echo "✅ Clientes creados: " . count($clientsData) . "\n";

        // === MATERIALES ===
        $materialsData = [
            [
                'name' => 'Cable Unipolar 1.5mm',
                'description' => 'Cable cobre aislado PVC, color azul',
                'price' => 0.45,
                'category' => 'cableado',
                'stock' => 800,
            ],
            [
                'name' => 'Cable Unipolar 4mm',
                'description' => 'Cable cobre aislado PVC, color marrón',
                'price' => 1.25,
                'category' => 'cableado',
                'stock' => 350,
            ],
            [
                'name' => 'Cable Unipolar 6mm',
                'description' => 'Cable cobre aislado PVC, color negro',
                'price' => 1.85,
                'category' => 'cableado',
                'stock' => 200,
            ],
            [
                'name' => 'Interruptor Automático 2P 25A',
                'description' => 'Interruptor magnetotérmico 2 polos',
                'price' => 12.50,
                'category' => 'protección',
                'stock' => 50,
            ],
            [
                'name' => 'Interruptor Automático 2P 40A',
                'description' => 'Interruptor magnetotérmico 2 polos',
                'price' => 18.75,
                'category' => 'protección',
                'stock' => 30,
            ],
            [
                'name' => 'Diferencial 2P 40A 30mA',
                'description' => 'Interruptor diferencial clase A',
                'price' => 35.00,
                'category' => 'protección',
                'stock' => 25,
            ],
            [
                'name' => 'Placa de mecanismo blanca',
                'description' => 'Marco embellecedor 1 módulo',
                'price' => 0.85,
                'category' => 'conexiones',
                'stock' => 500,
            ],
            [
                'name' => 'Enchufe Schuko 16A',
                'description' => 'Base de enchufe con toma tierra',
                'price' => 3.50,
                'category' => 'conexiones',
                'stock' => 150,
            ],
            [
                'name' => 'Interruptor conmutado',
                'description' => 'Interruptor 10A unipolar conmutado',
                'price' => 2.80,
                'category' => 'conexiones',
                'stock' => 120,
            ],
            [
                'name' => 'Tubo corrugado 25mm',
                'description' => 'Tubo protección cables, rollo 50m',
                'price' => 25.00,
                'category' => 'cableado',
                'stock' => 40,
            ],
        ];

        foreach ($materialsData as $materialData) {
            Material::firstOrCreate(
                ['name' => $materialData['name']],
                $materialData
            );
        }

        echo "✅ Materiales creados: " . count($materialsData) . "\n";

        // === PRESUPUESTOS ===
        $budgetsData = [
            [
                'client_email' => 'info@constructoramartinez.com',
                'number' => 'PRES-2026-002',
                'date' => '2026-03-05',
                'status' => 'sent',
                'iva_percent' => 21,
                'notes' => 'Instalación eléctrica oficina nueva',
                'lines' => [
                    ['material_name' => 'Cable Unipolar 4mm', 'quantity' => 200, 'unit_price' => 1.25],
                    ['material_name' => 'Tubo corrugado 25mm', 'quantity' => 2, 'unit_price' => 25.00],
                    ['material_name' => 'Interruptor Automático 2P 25A', 'quantity' => 10, 'unit_price' => 12.50],
                    ['material_name' => 'Placa de mecanismo blanca', 'quantity' => 50, 'unit_price' => 0.85],
                    ['material_name' => 'Enchufe Schuko 16A', 'quantity' => 30, 'unit_price' => 3.50],
                ],
            ],
            [
                'client_email' => 'administracion@elrincon.es',
                'number' => 'PRES-2026-003',
                'date' => '2026-03-05',
                'status' => 'approved',
                'iva_percent' => 21,
                'notes' => 'Cambio de cuadro eléctrico cocina',
                'lines' => [
                    ['material_name' => 'Cable Unipolar 6mm', 'quantity' => 100, 'unit_price' => 1.85],
                    ['material_name' => 'Interruptor Automático 2P 40A', 'quantity' => 4, 'unit_price' => 18.75],
                    ['material_name' => 'Diferencial 2P 40A 30mA', 'quantity' => 2, 'unit_price' => 35.00],
                    ['material_name' => 'Placa de mecanismo blanca', 'quantity' => 20, 'unit_price' => 0.85],
                    ['material_name' => 'Interruptor conmutado', 'quantity' => 8, 'unit_price' => 2.80],
                ],
            ],
            [
                'client_email' => 'compras@saludplus.es',
                'number' => 'PRES-2026-004',
                'date' => '2026-03-04',
                'status' => 'approved',
                'iva_percent' => 21,
                'notes' => 'Instalación iluminación sala espera',
                'lines' => [
                    ['material_name' => 'Cable Unipolar 1.5mm', 'quantity' => 150, 'unit_price' => 0.45],
                    ['material_name' => 'Cable Unipolar 2.5mm', 'quantity' => 100, 'unit_price' => 0.85],
                    ['material_name' => 'Interruptor Automático 2P 25A', 'quantity' => 6, 'unit_price' => 12.50],
                    ['material_name' => 'Enchufe Schuko 16A', 'quantity' => 15, 'unit_price' => 3.50],
                    ['material_name' => 'Interruptor conmutado', 'quantity' => 10, 'unit_price' => 2.80],
                ],
            ],
            [
                'client_email' => 'mantenimiento@logitrans.com',
                'number' => 'PRES-2026-005',
                'date' => '2026-03-03',
                'status' => 'draft',
                'iva_percent' => 21,
                'notes' => 'Mantenimiento anual instalación',
                'lines' => [
                    ['material_name' => 'Cable Unipolar 6mm', 'quantity' => 300, 'unit_price' => 1.85],
                    ['material_name' => 'Cable Unipolar 4mm', 'quantity' => 200, 'unit_price' => 1.25],
                    ['material_name' => 'Interruptor Automático 2P 40A', 'quantity' => 8, 'unit_price' => 18.75],
                    ['material_name' => 'Diferencial 2P 40A 30mA', 'quantity' => 4, 'unit_price' => 35.00],
                    ['material_name' => 'Tubo corrugado 25mm', 'quantity' => 5, 'unit_price' => 25.00],
                ],
            ],
            [
                'client_email' => 'presidente@csol45.com',
                'number' => 'PRES-2026-006',
                'date' => '2026-03-02',
                'status' => 'sent',
                'iva_percent' => 10,
                'notes' => 'Cambio iluminación portal y escalera',
                'lines' => [
                    ['material_name' => 'Cable Unipolar 1.5mm', 'quantity' => 200, 'unit_price' => 0.45],
                    ['material_name' => 'Cable Unipolar 2.5mm', 'quantity' => 50, 'unit_price' => 0.85],
                    ['material_name' => 'Interruptor conmutado', 'quantity' => 12, 'unit_price' => 2.80],
                    ['material_name' => 'Enchufe Schuko 16A', 'quantity' => 6, 'unit_price' => 3.50],
                    ['material_name' => 'Placa de mecanismo blanca', 'quantity' => 30, 'unit_price' => 0.85],
                ],
            ],
        ];

        foreach ($budgetsData as $budgetData) {
            // Buscar cliente por email
            $client = Client::where('email', $budgetData['client_email'])->first();
            
            if (!$client) {
                echo "⚠️  Cliente no encontrado: {$budgetData['client_email']}\n";
                continue;
            }

            // Crear presupuesto
            $budget = Budget::firstOrCreate(
                ['number' => $budgetData['number']],
                [
                    'client_id' => $client->id,
                    'date' => $budgetData['date'],
                    'status' => $budgetData['status'],
                    'iva_percent' => $budgetData['iva_percent'],
                    'notes' => $budgetData['notes'],
                ]
            );

            // Crear líneas del presupuesto
            foreach ($budgetData['lines'] as $lineData) {
                BudgetLine::firstOrCreate(
                    [
                        'budget_id' => $budget->id,
                        'description' => $lineData['material_name'],
                    ],
                    [
                        'material_id' => Material::where('name', $lineData['material_name'])->value('id'),
                        'quantity' => $lineData['quantity'],
                        'unit_price' => $lineData['unit_price'],
                    ]
                );
            }

            // Calcular y guardar total
            $budget->total = $budget->calculateTotal();
            $budget->save();
        }

        echo "✅ Presupuestos creados: " . count($budgetsData) . "\n";
        echo "\n🎉 ¡Datos de ejemplo creados correctamente!\n";
    }
}