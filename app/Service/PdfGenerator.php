<?php

namespace App\Service;

use TCPDF;

class PdfGenerator extends TCPDF
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
        // Logo
        $this->SetY(10);
        
        // Header background
        $this->SetFillColor(79, 70, 229); // Indigo
        $this->Rect(0, 0, 210, 35, 'F');
        
        // Title
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('helvetica', 'B', 20);
        $this->Cell(0, 15, $this->headerTitle, 0, 1, 'C', 0);
        
        // Subtitle
        if ($this->headerSubtitle) {
            $this->SetFont('helvetica', '', 10);
            $this->Cell(0, 5, $this->headerSubtitle, 0, 1, 'C', 0);
        }
        
        $this->Ln(5);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0, 10, 'Halaman ' . $this->getAliasNumPage() . ' dari ' . $this->getAliasNbPages(), 0, 0, 'C');
        
        $this->SetY(-15);
        $this->Cell(0, 10, 'Dicetak pada: ' . date('d F Y H:i'), 0, 0, 'R');
    }

    public function generateUsersTable($users, $stats)
    {
        // Info Box - Statistics
        $this->SetY(45);
        
        // Draw statistics boxes
        $boxWidth = 45;
        $boxHeight = 20;
        $startX = 15;
        $startY = 45;
        
        $statistics = [
            ['label' => 'Total User', 'value' => $stats['total'], 'color' => [52, 152, 219]],
            ['label' => 'Aktif', 'value' => $stats['active'], 'color' => [46, 204, 113]],
            ['label' => 'Suspended', 'value' => $stats['suspended'], 'color' => [241, 196, 15]],
            ['label' => 'Blocked', 'value' => $stats['blocked'], 'color' => [231, 76, 60]]
        ];
        
        $x = $startX;
        foreach ($statistics as $stat) {
            // Box background
            $this->SetFillColor($stat['color'][0], $stat['color'][1], $stat['color'][2]);
            $this->RoundedRect($x, $startY, $boxWidth, $boxHeight, 3, '1111', 'F');
            
            // Value
            $this->SetXY($x, $startY + 3);
            $this->SetFont('helvetica', 'B', 18);
            $this->SetTextColor(255, 255, 255);
            $this->Cell($boxWidth, 8, $stat['value'], 0, 1, 'C');
            
            // Label
            $this->SetXY($x, $startY + 11);
            $this->SetFont('helvetica', '', 9);
            $this->Cell($boxWidth, 5, $stat['label'], 0, 1, 'C');
            
            $x += $boxWidth + 3;
        }
        
        $this->Ln(25);
        
        // Table header
        $this->SetFont('helvetica', 'B', 9);
        $this->SetFillColor(79, 70, 229);
        $this->SetTextColor(255, 255, 255);
        $this->SetDrawColor(79, 70, 229);
        $this->SetLineWidth(0.3);
        
        // Column widths
        $w = [8, 35, 50, 30, 25, 20, 20];
        
        // Header
        $this->Cell($w[0], 8, 'No', 1, 0, 'C', 1);
        $this->Cell($w[1], 8, 'Username', 1, 0, 'C', 1);
        $this->Cell($w[2], 8, 'Email', 1, 0, 'C', 1);
        $this->Cell($w[3], 8, 'Nama Lengkap', 1, 0, 'C', 1);
        $this->Cell($w[4], 8, 'Jurusan', 1, 0, 'C', 1);
        $this->Cell($w[5], 8, 'Role', 1, 0, 'C', 1);
        $this->Cell($w[6], 8, 'Status', 1, 0, 'C', 1);
        $this->Ln();
        
        // Data
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(50, 50, 50);
        $this->SetDrawColor(220, 220, 220);
        
        $fill = false;
        $no = 1;
        
        foreach ($users as $user) {
            if ($fill) {
                $this->SetFillColor(248, 249, 250);
            } else {
                $this->SetFillColor(255, 255, 255);
            }
            
            // Check if we need a new page
            if ($this->GetY() > 260) {
                $this->AddPage();
                
                // Reprint header
                $this->SetFont('helvetica', 'B', 9);
                $this->SetFillColor(79, 70, 229);
                $this->SetTextColor(255, 255, 255);
                
                $this->Cell($w[0], 8, 'No', 1, 0, 'C', 1);
                $this->Cell($w[1], 8, 'Username', 1, 0, 'C', 1);
                $this->Cell($w[2], 8, 'Email', 1, 0, 'C', 1);
                $this->Cell($w[3], 8, 'Nama Lengkap', 1, 0, 'C', 1);
                $this->Cell($w[4], 8, 'Jurusan', 1, 0, 'C', 1);
                $this->Cell($w[5], 8, 'Role', 1, 0, 'C', 1);
                $this->Cell($w[6], 8, 'Status', 1, 0, 'C', 1);
                $this->Ln();
                
                $this->SetFont('helvetica', '', 8);
                $this->SetTextColor(50, 50, 50);
            }
            
            $this->Cell($w[0], 7, $no++, 1, 0, 'C', $fill);
            $this->Cell($w[1], 7, $user['username'], 1, 0, 'L', $fill);
            $this->Cell($w[2], 7, $user['email'], 1, 0, 'L', $fill);
            $this->Cell($w[3], 7, $user['nama_lengkap'] ?: '-', 1, 0, 'L', $fill);
            $this->Cell($w[4], 7, $user['jurusan'] ?: '-', 1, 0, 'L', $fill);
            
            // Role dengan warna
            $roleColor = [108, 117, 125]; // Default gray
            if ($user['role'] === 'admin') {
                $roleColor = [220, 53, 69]; // Red
            } elseif ($user['role'] === 'satpam') {
                $roleColor = [13, 110, 253]; // Blue
            }
            $this->SetTextColor($roleColor[0], $roleColor[1], $roleColor[2]);
            $this->Cell($w[5], 7, ucfirst($user['role']), 1, 0, 'C', $fill);
            
            // Status dengan warna
            $this->SetTextColor(50, 50, 50);
            $statusColor = [255, 255, 255];
            $statusTextColor = [255, 255, 255];
            
            switch (strtolower($user['status'])) {
                case 'active':
                    $statusColor = [40, 167, 69];
                    break;
                case 'suspended':
                    $statusColor = [255, 193, 7];
                    break;
                case 'blocked':
                    $statusColor = [220, 53, 69];
                    break;
            }
            
            $this->SetFillColor($statusColor[0], $statusColor[1], $statusColor[2]);
            $this->SetTextColor(255, 255, 255);
            $this->Cell($w[6], 7, $user['status'], 1, 0, 'C', 1);
            $this->SetTextColor(50, 50, 50);
            
            $this->Ln();
            $fill = !$fill;
        }
        
        // Summary footer
        $this->Ln(5);
        $this->SetFont('helvetica', 'I', 8);
        $this->SetTextColor(128, 128, 128);
        $this->Cell(0, 5, 'Total: ' . count($users) . ' pengguna', 0, 1, 'L');
    }
}