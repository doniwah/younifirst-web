<?php

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\ActivityLogPdfGenerator;

class ActivityLogPdfGeneratorTest extends TestCase
{
    public function testGenerate()
    {
        // Smoke test
        $pdf = new ActivityLogPdfGenerator();
        $pdf->setHeaderInfo('Activity Log', 'Subtitle');
        $pdf->AddPage();

        $stats = [
            'diaktifkan' => 1,
            'dihapus' => 0,
            'suspended' => 0,
            'blocked' => 0
        ];

        $logs = [
            [
                'type' => 'reactivated',
                'title' => 'Reactivated',
                'user' => 'User 1',
                'admin' => 'Admin 1',
                'reason' => 'Reason',
                'date' => date('Y-m-d H:i:s')
            ]
        ];

        try {
            $pdf->generateStatisticsSection($stats);
            $pdf->generateActivityTimeline($logs);
            $pdf->generateSummaryPage($stats, 1);
            $this->assertTrue(true);
        } catch (\Throwable $e) {
            $this->fail('PDF generation failed: ' . $e->getMessage());
        }
    }
}
