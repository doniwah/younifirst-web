<?php

namespace App\Controller;

use App\App\View;
use App\Model\Feature;
use App\Model\Stat;
use App\Model\Testimonial;
use App\Model\Faq;

class HomeController
{
    public function index()
    {
        $features = Feature::getAll();
        $stats = Stat::getAll();
        $testimonials = Testimonial::getAll();
        $faqs = Faq::getAll();

        View::render('index', [
            'title' => 'YouNiFirst - Platform Komunitas Kampus',
            'features' => $features,
            'stats' => $stats,
            'testimonials' => $testimonials,
            'faqs' => $faqs
        ]);
    }
}
