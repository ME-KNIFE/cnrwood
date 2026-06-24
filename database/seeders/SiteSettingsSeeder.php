<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['group' => 'general', 'key' => 'site_name',         'value' => 'CNRWOOD'],
            ['group' => 'general', 'key' => 'site_tagline',      'value' => 'Ahsap Ambalaj & Uretim'],
            ['group' => 'general', 'key' => 'founding_year',     'value' => '1998'],
            ['group' => 'general', 'key' => 'ispm15_badge_tr',   'value' => 'ISPM 15 Sertifikali Ihracat Ambalaji'],
            ['group' => 'general', 'key' => 'ispm15_badge_en',   'value' => 'ISPM 15 Certified Export Packaging'],
            // Contact
            ['group' => 'contact', 'key' => 'phone_primary',     'value' => '+90 262 751 21 20'],
            ['group' => 'contact', 'key' => 'email_primary',     'value' => 'info@cnrwood.com'],
            ['group' => 'contact', 'key' => 'address_street',    'value' => 'Pelitli Mah. Pelitli Yolu Cad. No: 137/A'],
            ['group' => 'contact', 'key' => 'address_city',      'value' => 'Gebze'],
            ['group' => 'contact', 'key' => 'address_region',    'value' => 'Kocaeli'],
            ['group' => 'contact', 'key' => 'address_display',   'value' => 'Gebze OSB, Kocaeli'],
            ['group' => 'contact', 'key' => 'maps_link_url',     'value' => 'https://www.google.com/maps/search/?api=1&query=CNR+Ahsap+Pelitli+Gebze+Kocaeli'],
            // Social
            ['group' => 'social',  'key' => 'social_whatsapp',   'value' => ''],
            ['group' => 'social',  'key' => 'social_instagram',  'value' => ''],
            ['group' => 'social',  'key' => 'social_facebook',   'value' => ''],
            ['group' => 'social',  'key' => 'social_linkedin',   'value' => ''],
            ['group' => 'social',  'key' => 'social_youtube',    'value' => ''],
            // SEO
            ['group' => 'seo',     'key' => 'seo_default_title', 'value' => 'CNRWOOD - Ahsap Sandik, Ambalaj, Kereste ve Ahsap Yapi Cozumleri'],
            ['group' => 'seo',     'key' => 'seo_default_description', 'value' => 'Gebze merkezli CNR Ahsap; ihracat sandiklari, ISPM 15 isil islemli ambalaj, kereste & levha ve ahsap yapi projelerinde 1998den beri profesyonel cozum sunar.'],
            ['group' => 'seo',     'key' => 'seo_org_legal_name','value' => 'CNR Ahsap Sanayi ve Ticaret'],
        ];

        foreach ($settings as $row) {
            // Only insert if not already set (do not overwrite admin changes)
            Setting::firstOrCreate(
                ['key' => $row['key']],
                [
                    'group' => $row['group'],
                    'value' => $row['value'],
                    'type'  => 'string',
                ]
            );
        }
    }
}
