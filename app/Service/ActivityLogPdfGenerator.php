<?php

namespace App\Service;

use TCPDF;

class ActivityLogPdfGenerator extends TCPDF
{
    private $headerTitle = '';
    private $headerSubtitle = '';

    public function setHeaderInfo($title, $subtitle = '')
    {
        $this->headerTitle = $title;
        $this->headerSubtitle = $subtitle;
    }

    public function Header()
    {
        // Header background with gradient effect
        $this->SetFillColor(79, 70, 229);
        $this->Rect(0, 0, 210, 40, 'F');
        
        // Add subtle pattern
        $this->SetAlpha(0.1);
        $this->SetFillColor(255, 255, 255);
        for ($i = 0; $i < 20; $i++) {
            $this->Circle(rand(0, 210), rand(0, 40), rand(5, 15), 0, 360, 'F');
        }
        $this->SetAlpha(1);
        
        // Icon/Logo area
        $this->SetY(10);
        $this->SetFont('helvetica', 'B', 24);
        $this->SetTextColor(255, 255, 255);
        
        // Title with icon
        $this->Cell(0, 12, '📋  ' . $this->headerTitle, 0, 1, 'C', 0);
        
        // Subtitle
        if ($this->headerSubtitle) {
            $this->SetFont('helvetica', '', 10);
            $this->SetTextColor(230, 230, 255);
            $this->Cell(0, 6, $this->headerSubtitle, 0, 1, 'C', 0);
        }
        
        // Date range or generation info
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(200, 200, 255);
        $this->Cell(0, 5, 'Digenerate pada: ' . date('d F Y, H:i') . ' WIB', 0, 1, 'C', 0);
        
        $this->Ln(3);
    }

    public function Footer()
    {
        $this->SetY(-15);
        
        // Footer line
        $this->SetDrawColor(79, 70, 229);
        $this->SetLineWidth(0.5);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        
        $this->Ln(2);
        
        // Footer text
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(100, 100, 100);
        
        // Left: Document info
        $this->Cell(60, 5, 'Log Aktivitas Admin', 0, 0, 'L');
        
        // Center: Page number
        $this->Cell(70, 5, 'Halaman ' . $this->getAliasNumPage() . ' dari ' . $this->getAliasNbPages(), 0, 0, 'C');
        
        // Right: Confidential
        $this->Cell(60, 5, 'Confidential', 0, 0, 'R');
    }

    public function generateStatisticsSection($stats)
    {
        $this->SetY(50);
        
        // Section title
        $this->SetFont('helvetica', 'B', 14);
        $this->SetTextColor(50, 50, 50);
        $this->Cell(0, 8, 'Ringkasan Statistik', 0, 1, 'L');
        $this->Ln(2);
        
        // Statistics boxes
        $boxWidth = 46;
        $boxHeight = 25;
        $startX = 15;
        $startY = $this->GetY();
        
        $statistics = [
            [
                'label' => 'Diaktifkan Kembali',
                'value' => $stats['diaktifkan'],
                'icon' => '✓',
                'color' => [40, 167, 69],
                'lightColor' => [212, 237, 218]
            ],
            [
                'label' => 'Dihapus',
                'value' => $stats['dihapus'],
                'icon' => '✗',
                'color' => [220, 53, 69],
                'lightColor' => [248, 215, 218]
            ],
            [
                'label' => 'Suspended',
                'value' => $stats['suspended'],
                'icon' => '⚠',
                'color' => [255, 193, 7],
                'lightColor' => [255, 243, 205]
            ],
            [
                'label' => 'Blocked',
                'value' => $stats['blocked'],
                'icon' => '🚫',
                'color' => [220, 53, 69],
                'lightColor' => [248, 215, 218]
            ]
        ];
        
        $x = $startX;
        foreach ($statistics as $stat) {
            // Shadow effect
            $this->SetAlpha(0.1);
            $this->SetFillColor(0, 0, 0);
            $this->RoundedRect($x + 1, $startY + 1, $boxWidth, $boxHeight, 4, '1111', 'F');
            $this->SetAlpha(1);
            
            // Box background
            $this->SetFillColor($stat['lightColor'][0], $stat['lightColor'][1], $stat['lightColor'][2]);
            $this->RoundedRect($x, $startY, $boxWidth, $boxHeight, 4, '1111', 'F');
            
            // Border
            $this->SetDrawColor($stat['color'][0], $stat['color'][1], $stat['color'][2]);
            $this->SetLineWidth(0.5);
            $this->RoundedRect($x, $startY, $boxWidth, $boxHeight, 4, '1111', 'D');
            
            // Icon
            $this->SetXY($x, $startY + 3);
            $this->SetFont('helvetica', 'B', 16);
            $this->SetTextColor($stat['color'][0], $stat['color'][1], $stat['color'][2]);
            $this->Cell($boxWidth, 8, $stat['icon'], 0, 1, 'C');
            
            // Value
            $this->SetXY($x, $startY + 10);
            $this->SetFont('helvetica', 'B', 20);
            $this->Cell($boxWidth, 8, $stat['value'], 0, 1, 'C');
            
            // Label
            $this->SetXY($x, $startY + 18);
            $this->SetFont('helvetica', '', 8);
            $this->SetTextColor(80, 80, 80);
            $this->Cell($boxWidth, 5, $stat['label'], 0, 1, 'C');
            
            $x += $boxWidth + 2;
        }
        
        $this->Ln(30);
    }

    public function generateActivityTimeline($logs)
    {
        // Section title
        $this->SetFont('helvetica', 'B', 14);
        $this->SetTextColor(50, 50, 50);
        $this->Cell(0, 8, 'Timeline Aktivitas', 0, 1, 'L');
        $this->Ln(3);
        
        $currentDate = '';
        
        foreach ($logs as $log) {
            // Check if we need a new page
            if ($this->GetY() > 260) {
                $this->AddPage();
                $this->SetFont('helvetica', 'B', 14);
                $this->SetTextColor(50, 50, 50);
                $this->Cell(0, 8, 'Timeline Aktivitas (lanjutan)', 0, 1, 'L');
                $this->Ln(3);
            }
            
            // Extract date from log
            $logDate = date('d F Y', strtotime($log['date']));
            
            // Date separator if date changed
            if ($currentDate !== $logDate) {
                if ($currentDate !== '') {
                    $this->Ln(3);
                }
                
                $this->SetFont('helvetica', 'B', 11);
                $this->SetTextColor(79, 70, 229);
                $this->SetFillColor(245, 245, 255);
                $this->Cell(0, 7, '📅  ' . $logDate, 0, 1, 'L', true);
                $this->Ln(2);
                
                $currentDate = $logDate;
            }
            
            // Activity item
            $this->drawActivityItem($log);
            $this->Ln(1);
        }
    }

    private function drawActivityItem($log)
    {
        $startY = $this->GetY();
        $startX = 20;
        
        // Determine colors based on type
        $colors = $this->getActivityColors($log['type']);
        
        // Timeline dot and line
        $this->SetFillColor($colors['main'][0], $colors['main'][1], $colors['main'][2]);
        $this->Circle($startX, $startY + 4, 2.5, 0, 360, 'F');
        
        // Vertical line (if not last item)
        $this->SetDrawColor(220, 220, 220);
        $this->SetLineWidth(0.3);
        $this->Line($startX, $startY + 6.5, $startX, $startY + 20);
        
        // Content box
        $boxX = $startX + 5;
        $boxWidth = 170;
        
        // Shadow
        $this->SetAlpha(0.05);
        $this->SetFillColor(0, 0, 0);
        $this->RoundedRect($boxX + 1, $startY + 1, $boxWidth, 18, 3, '1111', 'F');
        $this->SetAlpha(1);
        
        // Background
        $this->SetFillColor($colors['bg'][0], $colors['bg'][1], $colors['bg'][2]);
        $this->RoundedRect($boxX, $startY, $boxWidth, 18, 3, '1111', 'F');
        
        // Left border accent
        $this->SetFillColor($colors['main'][0], $colors['main'][1], $colors['main'][2]);
        $this->Rect($boxX, $startY, 2, 18, 'F');
        
        // Icon
        $iconX = $boxX + 5;
        $this->SetXY($iconX, $startY + 2);
        $this->SetFont('zapfdingbats', '', 12);
        $this->SetTextColor($colors['main'][0], $colors['main'][1], $colors['main'][2]);
        $this->Cell(6, 6, $this->getActivityIcon($log['type']), 0, 0, 'C');
        
        // Title and time
        $contentX = $iconX + 8;
        $this->SetXY($contentX, $startY + 2);
        $this->SetFont('helvetica', 'B', 10);
        $this->SetTextColor(50, 50, 50);
        $this->Cell(100, 5, $log['title'], 0, 0, 'L');
        
        // Time on the right
        $this->SetXY($boxX + $boxWidth - 35, $startY + 2);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(30, 5, date('H:i', strtotime($log['date'])), 0, 1, 'R');
        
        // User info
        $this->SetXY($contentX, $startY + 7);
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(70, 4, 'Pengguna: ' . $log['user'], 0, 0, 'L');
        
        // Admin info
        $this->SetXY($contentX + 70, $startY + 7);
        $this->Cell(70, 4, 'Oleh: ' . $log['admin'], 0, 1, 'L');
        
        // Reason if exists
        if (!empty($log['reason'])) {
            $this->SetXY($contentX, $startY + 11);
            $this->SetFont('helvetica', 'I', 7);
            $this->SetTextColor(80, 80, 80);
            
            $reason = $log['reason'];
            if (strlen($reason) > 100) {
                $reason = substr($reason, 0, 97) . '...';
            }
            
            $this->Cell(155, 4, 'Alasan: ' . $reason, 0, 1, 'L');
        }
        
        $this->SetY($startY + 20);
    }

    private function getActivityColors($type)
    {
        $colorMap = [
            'reactivated' => [
                'main' => [40, 167, 69],
                'bg' => [212, 237, 218]
            ],
            'suspended' => [
                'main' => [255, 193, 7],
                'bg' => [255, 243, 205]
            ],
            'blocked' => [
                'main' => [220, 53, 69],
                'bg' => [248, 215, 218]
            ],
            'deleted' => [
                'main' => [220, 53, 69],
                'bg' => [248, 215, 218]
            ]
        ];
        
        return $colorMap[$type] ?? $colorMap['reactivated'];
    }

    private function getActivityIcon($type)
    {
        $iconMap = [
            'reactivated' => '4', // Checkmark
            'suspended' => '!',   // Exclamation
            'blocked' => '8',     // X mark
            'deleted' => '✗'      // Cross
        ];
        
        return $iconMap[$type] ?? '•';
    }

    public function generateSummaryPage($stats, $totalLogs)
    {
        $this->AddPage();
        
        // Title
        $this->SetFont('helvetica', 'B', 16);
        $this->SetTextColor(79, 70, 229);
        $this->Cell(0, 10, 'Ringkasan & Analisis', 0, 1, 'C');
        $this->Ln(5);
        
        // Summary box
        $this->SetFillColor(245, 247, 250);
        $this->RoundedRect(20, $this->GetY(), 170, 60, 5, '1111', 'F');
        
        $this->SetXY(25, $this->GetY() + 5);
        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor(50, 50, 50);
        $this->Cell(0, 8, 'Informasi Umum', 0, 1, 'L');
        
        $this->SetX(25);
        $this->SetFont('helvetica', '', 10);
        $this->SetTextColor(80, 80, 80);
        
        $summaryText = "Total aktivitas tercatat: {$totalLogs} aktivitas\n\n";
        $summaryText .= "Detail aktivitas:\n";
        $summaryText .= "• Akun diaktifkan kembali: {$stats['diaktifkan']} kali\n";
        $summaryText .= "• Akun disuspend: {$stats['suspended']} kali\n";
        $summaryText .= "• Akun diblokir: {$stats['blocked']} kali\n";
        $summaryText .= "• Akun dihapus: {$stats['dihapus']} kali\n";
        
        $this->MultiCell(160, 5, $summaryText, 0, 'L');
        
        $this->Ln(10);
        
        // Notes section
        $this->SetFont('helvetica', 'B', 11);
        $this->SetTextColor(79, 70, 229);
        $this->Cell(0, 8, 'Catatan', 0, 1, 'L');
        
        $this->SetFont('helvetica', '', 9);
        $this->SetTextColor(100, 100, 100);
        $notes = "• Dokumen ini bersifat rahasia dan hanya untuk keperluan internal\n";
        $notes .= "• Data yang ditampilkan adalah snapshot pada waktu generate dokumen\n";
        $notes .= "• Untuk informasi lebih detail, silakan akses sistem secara langsung";
        
        $this->MultiCell(0, 5, $notes, 0, 'L');
    }
}