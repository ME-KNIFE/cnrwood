<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class SiteSettingsPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Site Ayarları';
    protected static \UnitEnum|string|null $navigationGroup = 'Ayarlar';
    protected static ?string $title = 'Site Genel Ayarları';
    protected static ?int $navigationSort = 10;
    protected string $view = 'filament.admin.pages.site-settings';

    public ?array $data = [];

    private const DEFAULTS = [
        // General
        'site_name'         => 'CNRWOOD',
        'site_tagline'      => 'Ahsap Ambalaj & Uretim',
        'founding_year'     => '1998',
        'ispm15_badge_tr'   => 'ISPM 15 Sertifikali Ihracat Ambalaji',
        'ispm15_badge_en'   => 'ISPM 15 Certified Export Packaging',
        // Contact
        'phone_primary'     => '+90 262 751 21 20',
        'email_primary'     => 'info@cnrwood.com',
        'address_street'    => 'Pelitli Mah. Pelitli Yolu Cad. No: 137/A',
        'address_city'      => 'Gebze',
        'address_region'    => 'Kocaeli',
        'address_display'   => 'Gebze OSB, Kocaeli',
        'maps_link_url'     => 'https://www.google.com/maps/search/?api=1&query=CNR+Ahsap+Pelitli+Gebze+Kocaeli',
        // Social
        'social_whatsapp'   => '',
        'social_instagram'  => '',
        'social_facebook'   => '',
        'social_linkedin'   => '',
        'social_youtube'    => '',
        // SEO
        'seo_default_title' => 'CNRWOOD - Ahsap Sandik, Ambalaj, Kereste ve Ahsap Yapi Cozumleri',
        'seo_default_description' => 'Gebze merkezli CNR Ahsap; ihracat sandiklari, ISPM 15 isil islemli ambalaj, kereste & levha ve ahsap yapi projelerinde 1998den beri profesyonel cozum sunar.',
        'seo_org_legal_name' => 'CNR Ahsap Sanayi ve Ticaret',
    ];

    public static function canAccess(): bool
    {
        return auth('admin')->user()?->isSuperAdmin() ?? false;
    }

    public function mount(): void
    {
        $loaded = [];
        foreach (self::DEFAULTS as $key => $default) {
            $loaded[$key] = Setting::get($key, $default);
        }
        $this->form->fill($loaded);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('tabs')
                    ->tabs([

                        Tab::make('Genel')
                            ->icon('heroicon-o-building-office-2')
                            ->schema([
                                Section::make('Site Kimliği')
                                    ->description('Sitenin genel kimlik bilgileri.')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('site_name')
                                                ->label('Site Adı')
                                                ->required()
                                                ->maxLength(100),
                                            TextInput::make('site_tagline')
                                                ->label('Slogan / Alt Başlık')
                                                ->maxLength(200)
                                                ->helperText('Başlık altında kısa slogan.'),
                                        ]),
                                        Grid::make(3)->schema([
                                            TextInput::make('founding_year')
                                                ->label('Kuruluş Yılı')
                                                ->maxLength(10),
                                            TextInput::make('ispm15_badge_tr')
                                                ->label('ISPM15 Rozeti (TR)')
                                                ->maxLength(200)
                                                ->helperText("Başlık çubuğu ve footer'da gösterilir."),
                                            TextInput::make('ispm15_badge_en')
                                                ->label('ISPM15 Rozeti (EN)')
                                                ->maxLength(200),
                                        ]),
                                    ]),
                            ]),

                        Tab::make('İletişim')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Section::make('İletişim Bilgileri')
                                    ->description('Header, footer ve iletişim sayfasında kullanılır.')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            TextInput::make('phone_primary')
                                                ->label('Telefon (Ana)')
                                                ->tel()
                                                ->maxLength(50),
                                            TextInput::make('email_primary')
                                                ->label('E-posta (Ana)')
                                                ->email()
                                                ->maxLength(150),
                                        ]),
                                    ]),
                                Section::make('Adres')
                                    ->schema([
                                        TextInput::make('address_display')
                                            ->label('Kısa Adres (Header)')
                                            ->maxLength(200)
                                            ->helperText('Örn: Gebze OSB, Kocaeli'),
                                        TextInput::make('address_street')
                                            ->label('Cadde / Sokak')
                                            ->maxLength(300),
                                        Grid::make(2)->schema([
                                            TextInput::make('address_city')
                                                ->label('İlçe')
                                                ->maxLength(100),
                                            TextInput::make('address_region')
                                                ->label('İl')
                                                ->maxLength(100),
                                        ]),
                                        TextInput::make('maps_link_url')
                                            ->label('Google Maps Bağlantısı')
                                            ->url()
                                            ->maxLength(500)
                                            ->helperText('İletişim sayfasında "Haritada Gör" linki.'),
                                    ]),
                            ]),

                        Tab::make('Sosyal Medya')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make('Sosyal Medya Hesapları')
                                    ->description('Boş bırakılan hesaplar gösterilmez.')
                                    ->schema([
                                        TextInput::make('social_whatsapp')
                                            ->label('WhatsApp Numarası')
                                            ->placeholder('+905XXXXXXXXX')
                                            ->maxLength(50),
                                        TextInput::make('social_instagram')
                                            ->label('Instagram URL')
                                            ->url()
                                            ->maxLength(300),
                                        TextInput::make('social_facebook')
                                            ->label('Facebook URL')
                                            ->url()
                                            ->maxLength(300),
                                        TextInput::make('social_linkedin')
                                            ->label('LinkedIn URL')
                                            ->url()
                                            ->maxLength(300),
                                        TextInput::make('social_youtube')
                                            ->label('YouTube URL')
                                            ->url()
                                            ->maxLength(300),
                                    ]),
                            ]),

                        Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Section::make('Varsayılan SEO Değerleri')
                                    ->description('Sayfa bazlı başlık/açıklama yoksa kullanılır.')
                                    ->schema([
                                        TextInput::make('seo_org_legal_name')
                                            ->label('Tüzel Kişi Adı')
                                            ->maxLength(200)
                                            ->helperText('JSON-LD schema Organization.legalName'),
                                        TextInput::make('seo_default_title')
                                            ->label('Varsayılan Sayfa Başlığı')
                                            ->maxLength(200),
                                        Textarea::make('seo_default_description')
                                            ->label('Varsayılan Meta Açıklaması')
                                            ->rows(3)
                                            ->maxLength(500),
                                    ]),
                            ]),

                    ])->columnSpanFull(),
            ])->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $groupMap = [
            'site_name'               => 'general',
            'site_tagline'            => 'general',
            'founding_year'           => 'general',
            'ispm15_badge_tr'         => 'general',
            'ispm15_badge_en'         => 'general',
            'phone_primary'           => 'contact',
            'email_primary'           => 'contact',
            'address_street'          => 'contact',
            'address_city'            => 'contact',
            'address_region'          => 'contact',
            'address_display'         => 'contact',
            'maps_link_url'           => 'contact',
            'social_whatsapp'         => 'social',
            'social_instagram'        => 'social',
            'social_facebook'         => 'social',
            'social_linkedin'         => 'social',
            'social_youtube'          => 'social',
            'seo_default_title'       => 'seo',
            'seo_default_description' => 'seo',
            'seo_org_legal_name'      => 'seo',
        ];

        foreach ($data as $key => $value) {
            $group = $groupMap[$key] ?? 'general';
            Setting::set(key: $key, value: (string) ($value ?? ''), group: $group);
        }

        Notification::make()
            ->title('Site ayarları kaydedildi')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Kaydet')
                ->submit('save'),
        ];
    }
}
