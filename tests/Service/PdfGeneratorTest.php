<?php

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\PdfGenerator;

class PdfGeneratorTest extends TestCase
{
    public function testGenerateUsersTable()
    {
        // Smoke test: ensure no exception is thrown during generation
        // We cannot easily test the output PDF content without a parser, 
        // but we can check if it runs.
        
        $pdf = new PdfGenerator();
        $pdf->setHeaderInfo('Test Report', 'Subtitle');
        $pdf->AddPage();

        $users = [
            [
                'username' => 'user1',
                'email' => 'user1@example.com',
                'nama_lengkap' => 'User One',
                'jurusan' => 'IT',
                'role' => 'mahasiswa',
                'status' => 'active'
            ]
        ];

        $stats = [
            'total' => 1,
            'active' => 1,
            'suspended' => 0,
            'blocked' => 0
        ];

        try {
            $pdf->generateUsersTable($users, $stats);
            $this->assertTrue(true); // If we reach here, no exception occurred
        } catch (\Throwable $e) {
            $this->fail('PDF generation failed: ' . $e->getMessage());
        }
    }
}
