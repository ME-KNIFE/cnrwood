<?php

namespace App\Filament\Admin\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class HomepageDesignSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationLabel = 'Ana Sayfa Tasarımı';
    protected static \UnitEnum|string|null $navigationGroup = 'Ayarlar';
    protected static ?string $title = 'Ana Sayfa Tasarım Ayarları';
    protected static ?int $navigationSort = 20;
    protected string $view = 'filament.admin.pages.homepage-design-settings';

    public ?array $data = [];

    private const DEFAULTS = [
        // Content overrides (empty = use lang file)
        'hero_badge_tr'                    => '',
        'hero_badge_en'                    => '',
        'hero_title_tr'                    => '',
        'hero_title_en'                    => '',
        'hero_subtitle_tr'                 => '',
        'hero_subtitle_en'                 => '',
        'hero_primary_button_text_tr'      => '',
        'hero_primary_button_text_en'      => '',
        'hero_secondary_button_text_tr'    => '',
        'hero_secondary_button_text_en'    => '',

        // Typography (desktop)
        'hero_title_font_size_mobile'      => '2.25rem',
        'hero_title_font_size_desktop'     => '3.75rem',
        'hero_title_line_height'           => '1.08',
        'hero_title_letter_spacing'        => '-0.02em',
        'hero_title_font_weight'           => '700',
        'hero_title_text_transform'        => 'uppercase',
        'hero_title_color'                 => '#f5f0e8',
        'hero_title_accent_color'          => '#8b5a2b',

        // Typography (mobile overrides)
        'hero_title_line_height_mobile'    => '1.10',
        'hero_title_letter_spacing_mobile' => '0em',
        'hero_mobile_alignment'            => 'left',
        'hero_mobile_content_padding_top'  => '64',
        'hero_mobile_content_padding_bottom' => '96',

        // Layout
        'hero_text_max_width'              => '42rem',
        'hero_alignment'                   => 'left',
        'hero_section_min_height'          => 'auto',
        'hero_content_padding_top'         => '80',
        'hero_content_padding_bottom'      => '128',

        // Background
        'hero_background_type'             => 'gradient',
        'hero_background_color'            => '#3e2006',
        'hero_background_image_position'   => 'center',
        'hero_background_image_size'       => 'cover',
        'hero_overlay_color'               => '#000000',
        'hero_overlay_opacity'             => '0',

        // Buttons
        'hero_primary_button_url'          => '',
        'hero_secondary_button_url'        => '',
        'hero_primary_button_visible'      => '1',
        'hero_secondary_button_visible'    => '1',
        'hero_primary_button_color'        => '#1e3a5f',
        'hero_secondary_button_color'      => '',
        'hero_button_radius'               => 'sm',
        'hero_button_size'                 => 'md',

        // Stats
        'hero_stats_visible'               => '1',
        'hero_stat_1_value'                => '1998',
        'hero_stat_1_label_tr'             => 'KURULUŞ YILI',
        'hero_stat_1_label_en'             => 'FOUNDED',
        'hero_stat_2_value'                => '4',
        'hero_stat_2_label_tr'             => 'ŞUBE',
        'hero_stat_2_label_en'             => 'BRANCHES',
        'hero_stat_3_value'                => '70+',
        'hero_stat_3_label_tr'             => 'ÇALIŞAN',
        'hero_stat_3_label_en'             => 'EMPLOYEES',
        'hero_stat_4_value'                => '7+',
        'hero_stat_4_label_tr'             => 'İHRACAT YAPILAN ÜLKE',
        'hero_stat_4_label_en'             => 'EXPORT COUNTRIES',
        'hero_stat_5_value'                => 'ISPM 15',
        'hero_stat_5_label_tr'             => 'SERTİFİKALI',
        'hero_stat_5_label_en'             => 'CERTIFIED',

        // Hero image
        'homepage_hero_image'              => '',
        'homepage_hero_image_alt_tr'       => '',
        'homepage_hero_image_alt_en'       => '',
        'hero_image_focal_point'           => 'center',
        'hero_image_opacity'               => '100',
        'hero_image_radius'                => 'none',
        'hero_image_shadow'                => '0',
        'hero_image_show_mobile'           => '1',

        // About image
        'homepage_about_image'             => '',
    ];

    public static function canAccess(): bool
    {
        return auth('admin')->user()?->isSuperAdmin() ?? false;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('resetDefaults')
                ->label('Varsayılana Döndür')
                ->icon('heroicon-o-arrow-path')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Varsayılan Ayarlara Dön')
                ->modalDescription('Tüm ana sayfa tasarım ayarları varsayılan değerlere sıfırlanacak. Devam etmek istiyor musunuz?')
                ->modalSubmitActionLabel('Evet, Sıfırla')
                ->action(function () {
                    foreach (self::DEFAULTS as $key => $value) {
                        Setting::set(key: $key, value: (string) $value, group: 'homepage');
                    }
                    $this->form->fill(self::DEFAULTS);
                    Notification::make()
                        ->title('Ayarlar varsayılana döndürüldü')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function mount(): void
    {
        $stored = [];
        foreach (array_keys(self::DEFAULTS) as $key) {
            $stored[$key] = Setting::get($key, self::DEFAULTS[$key]);
        }
        $this->form->fill($stored);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([

                // --- 1. Content overrides ---
                Section::make('İçerik (Opsiyonel Geçersiz Kılma)')
                    ->description('Boş bırakırsanız dil dosyalarındaki çeviriler kullanılır.')
                    ->collapsed()
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('hero_badge_tr')
                                ->label('Rozet metni -- TR')
                                ->placeholder('Boş = dil dosyasından al')
                                ->live(debounce: 600)
                                ->maxLength(80),
                            TextInput::make('hero_badge_en')
                                ->label('Badge text -- EN')
                                ->placeholder('Empty = use lang file')
                                ->live(debounce: 600)
                                ->maxLength(80),
                        ]),
                        Grid::make(2)->schema([
                            Textarea::make('hero_title_tr')
                                ->label('Başlık -- TR')
                                ->rows(3)
                                ->live(debounce: 800)
                                ->placeholder('Boş = dil dosyasından al'),
                            Textarea::make('hero_title_en')
                                ->label('Title -- EN')
                                ->rows(3)
                                ->live(debounce: 800)
                                ->placeholder('Empty = use lang file'),
                        ]),
                        Grid::make(2)->schema([
                            Textarea::make('hero_subtitle_tr')
                                ->label('Alt başlık -- TR')
                                ->rows(3)
                                ->live(debounce: 800)
                                ->placeholder('Boş = dil dosyasından al'),
                            Textarea::make('hero_subtitle_en')
                                ->label('Subtitle -- EN')
                                ->rows(3)
                                ->live(debounce: 800)
                                ->placeholder('Empty = use lang file'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('hero_primary_button_text_tr')
                                ->label('Birincil buton -- TR')
                                ->placeholder('Boş = dil dosyasından al')
                                ->maxLength(60),
                            TextInput::make('hero_primary_button_text_en')
                                ->label('Primary button -- EN')
                                ->placeholder('Empty = use lang file')
                                ->maxLength(60),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('hero_secondary_button_text_tr')
                                ->label('İkincil buton -- TR')
                                ->placeholder('Boş = dil dosyasından al')
                                ->maxLength(60),
                            TextInput::make('hero_secondary_button_text_en')
                                ->label('Secondary button -- EN')
                                ->placeholder('Empty = use lang file')
                                ->maxLength(60),
                        ]),
                    ]),

                // --- 2. Hero Typography ---
                Section::make('Başlık Tipografisi')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('hero_title_font_size_mobile')
                                ->label('Yazı boyutu -- Mobil')
                                ->options([
                                    '1.875rem' => 'text-3xl -- 1.875rem (30px)',
                                    '2.25rem'  => 'text-4xl -- 2.25rem (36px) [varsayılan]',
                                    '3rem'     => 'text-5xl -- 3rem (48px)',
                                ])
                                ->native(false)
                                ->required(),
                            Select::make('hero_title_font_size_desktop')
                                ->label('Yazı boyutu -- Masaüstü (lg+)')
                                ->options([
                                    '2.25rem'  => 'text-4xl -- 2.25rem (36px)',
                                    '3rem'     => 'text-5xl -- 3rem (48px)',
                                    '3.75rem'  => 'text-6xl -- 3.75rem (60px) [varsayılan]',
                                    '4.5rem'   => 'text-7xl -- 4.5rem (72px)',
                                ])
                                ->native(false)
                                ->required(),
                        ]),
                        Grid::make(4)->schema([
                            TextInput::make('hero_title_line_height')
                                ->label('Satır yüksekliği -- Masaüstü')
                                ->numeric()
                                ->minValue(0.85)
                                ->maxValue(1.60)
                                ->step(0.01)
                                ->suffix('x')
                                ->hint('0.85 - 1.60')
                                ->required(),
                            TextInput::make('hero_title_line_height_mobile')
                                ->label('Satır yüksekliği -- Mobil')
                                ->numeric()
                                ->minValue(0.85)
                                ->maxValue(1.60)
                                ->step(0.01)
                                ->suffix('x')
                                ->hint('0.85 - 1.60'),
                            TextInput::make('hero_title_letter_spacing')
                                ->label('Harf aralığı -- Masaüstü')
                                ->placeholder('-0.02em')
                                ->maxLength(12),
                            TextInput::make('hero_title_letter_spacing_mobile')
                                ->label('Harf aralığı -- Mobil')
                                ->placeholder('0em')
                                ->maxLength(12),
                        ]),
                        Grid::make(3)->schema([
                            Select::make('hero_title_font_weight')
                                ->label('Yazı ağırlığı')
                                ->options([
                                    '400' => 'Regular (400)',
                                    '500' => 'Medium (500)',
                                    '600' => 'SemiBold (600)',
                                    '700' => 'Bold (700) [varsayılan]',
                                    '800' => 'ExtraBold (800)',
                                    '900' => 'Black (900)',
                                ])
                                ->native(false)
                                ->required(),
                            Select::make('hero_title_text_transform')
                                ->label('Metin dönüşümü')
                                ->options([
                                    'none'       => 'Normal',
                                    'uppercase'  => 'BÜ YÜ K HARF [varsayılan]',
                                    'lowercase'  => 'küçük harf',
                                    'capitalize' => 'Her Kelime',
                                ])
                                ->native(false)
                                ->required(),
                            Grid::make(2)->schema([
                                ColorPicker::make('hero_title_color')
                                    ->label('Başlık rengi'),
                                ColorPicker::make('hero_title_accent_color')
                                    ->label('Vurgu rengi'),
                            ]),
                        ]),
                    ]),

                // --- 3. Hero Layout ---
                Section::make('Yerleşim ve Boyutlar')
                    ->schema([
                        Grid::make(3)->schema([
                            Select::make('hero_alignment')
                                ->label('Hizalama -- Masaüstü')
                                ->options([
                                    'left'   => 'Sola (left) [varsayılan]',
                                    'center' => 'Ortaya (center)',
                                    'right'  => 'Sağa (right)',
                                ])
                                ->native(false)
                                ->required(),
                            Select::make('hero_mobile_alignment')
                                ->label('Hizalama -- Mobil')
                                ->options([
                                    'left'   => 'Sola (left) [varsayılan]',
                                    'center' => 'Ortaya (center)',
                                    'right'  => 'Sağa (right)',
                                ])
                                ->native(false),
                            TextInput::make('hero_text_max_width')
                                ->label('Maksimum metin genişliği')
                                ->placeholder('42rem')
                                ->maxLength(12),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('hero_section_min_height')
                                ->label('Bölüm min. yükseklik')
                                ->placeholder('auto')
                                ->hint('auto, 400px, 60vh ...')
                                ->maxLength(20),
                            Grid::make(2)->schema([
                                TextInput::make('hero_content_padding_top')
                                    ->label('Padding üst -- Masaüstü')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(240)
                                    ->suffix('px'),
                                TextInput::make('hero_content_padding_bottom')
                                    ->label('Padding alt -- Masaüstü')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(240)
                                    ->suffix('px'),
                            ]),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('hero_mobile_content_padding_top')
                                ->label('Padding üst -- Mobil')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(240)
                                ->suffix('px'),
                            TextInput::make('hero_mobile_content_padding_bottom')
                                ->label('Padding alt -- Mobil')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(240)
                                ->suffix('px'),
                        ]),
                    ]),

                // --- 4. Hero Buttons ---
                Section::make('Butonlar')
                    ->schema([
                        Grid::make(2)->schema([
                            Toggle::make('hero_primary_button_visible')
                                ->label('Birincil buton görünsün'),
                            Toggle::make('hero_secondary_button_visible')
                                ->label('İkincil buton görünsün'),
                        ]),
                        Grid::make(2)->schema([
                            TextInput::make('hero_primary_button_url')
                                ->label('Birincil buton URL')
                                ->placeholder('Boş = varsayılan route')
                                ->url()
                                ->maxLength(255),
                            TextInput::make('hero_secondary_button_url')
                                ->label('İkincil buton URL')
                                ->placeholder('Boş = varsayılan route')
                                ->url()
                                ->maxLength(255),
                        ]),
                        Grid::make(4)->schema([
                            ColorPicker::make('hero_primary_button_color')
                                ->label('Birincil renk'),
                            ColorPicker::make('hero_secondary_button_color')
                                ->label('İkincil renk'),
                            Select::make('hero_button_radius')
                                ->label('Köşe yuvarlama')
                                ->options([
                                    'none' => 'none (0)',
                                    'sm'   => 'sm [varsayılan]',
                                    'md'   => 'md',
                                    'lg'   => 'lg',
                                    'full' => 'full (pill)',
                                ])
                                ->native(false),
                            Select::make('hero_button_size')
                                ->label('Buton boyutu')
                                ->options([
                                    'sm' => 'Küçük (sm)',
                                    'md' => 'Orta (md) [varsayılan]',
                                    'lg' => 'Büyük (lg)',
                                ])
                                ->native(false),
                        ]),
                    ]),

                // --- 5. Hero Background ---
                Section::make('Arka Plan')
                    ->schema([
                        Select::make('hero_background_type')
                            ->label('Arka plan tipi')
                            ->options([
                                'gradient' => 'Degrade (mevcut tasarım) [varsayılan]',
                                'color'    => 'Düz renk',
                                'image'    => 'Resim',
                            ])
                            ->native(false)
                            ->live()
                            ->required(),
                        Grid::make(2)->schema([
                            ColorPicker::make('hero_background_color')
                                ->label('Arka plan rengi (color modunda)'),
                            Grid::make(2)->schema([
                                Select::make('hero_background_image_position')
                                    ->label('Resim konumu')
                                    ->options([
                                        'center'       => 'Orta (center) [varsayılan]',
                                        'top'          => 'Üst (top)',
                                        'bottom'       => 'Alt (bottom)',
                                        'left'         => 'Sol (left)',
                                        'right'        => 'Sağ (right)',
                                        'center top'   => 'Orta üst',
                                        'center bottom'=> 'Orta alt',
                                    ])
                                    ->native(false),
                                Select::make('hero_background_image_size')
                                    ->label('Resim boyutu')
                                    ->options([
                                        'cover'   => 'Kapla (cover) [onerilir]',
                                        'contain' => 'İçer (contain)',
                                        'auto'    => 'Otomatik (auto)',
                                    ])
                                    ->native(false),
                            ]),
                        ]),

                        // Overlay
                        Grid::make(2)->schema([
                            ColorPicker::make('hero_overlay_color')
                                ->label('Overlay rengi'),
                            TextInput::make('hero_overlay_opacity')
                                ->label('Overlay opaklık')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->step(5)
                                ->suffix('%')
                                ->hint('0 = görünmez | 100 = tam opak'),
                        ]),
                    ]),

                // --- 6. Hero Stats ---
                Section::make('Stats Satırı')
                    ->schema([
                        Toggle::make('hero_stats_visible')
                            ->label('Stats satırını göster'),
                        ...(function () {
                            $fields = [];
                            $nums   = [1, 2, 3, 4, 5];
                            foreach ($nums as $i) {
                                $fields[] = Grid::make(3)->schema([
                                    TextInput::make("hero_stat_{$i}_value")
                                        ->label("Stat {$i} -- Değer")
                                        ->maxLength(20),
                                    TextInput::make("hero_stat_{$i}_label_tr")
                                        ->label("Stat {$i} -- Etiket TR")
                                        ->maxLength(60),
                                    TextInput::make("hero_stat_{$i}_label_en")
                                        ->label("Stat {$i} -- Label EN")
                                        ->maxLength(60),
                                ]);
                            }
                            return $fields;
                        })(),
                    ]),

                // --- 7. Hero Image ---
                Section::make('Hero Resmi')
                    ->schema([
                        FileUpload::make('homepage_hero_image')
                            ->label('Hero resmi')
                            ->disk('public')
                            ->directory('homepage')
                            ->image()
                            ->imagePreviewHeight('160')
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Degrade/renk modunda hero sağ tarafında kullanılır.'),
                        Grid::make(2)->schema([
                            TextInput::make('homepage_hero_image_alt_tr')
                                ->label('Alt metin -- TR')
                                ->maxLength(120),
                            TextInput::make('homepage_hero_image_alt_en')
                                ->label('Alt text -- EN')
                                ->maxLength(120),
                        ]),
                        Grid::make(3)->schema([
                            Select::make('hero_image_focal_point')
                                ->label('Odak noktası')
                                ->options([
                                    'center' => 'Orta [varsayılan]',
                                    'top'    => 'Üst',
                                    'bottom' => 'Alt',
                                    'left'   => 'Sol',
                                    'right'  => 'Sağ',
                                ])
                                ->native(false),
                            TextInput::make('hero_image_opacity')
                                ->label('Resim opaklığı')
                                ->numeric()
                                ->minValue(0)
                                ->maxValue(100)
                                ->step(5)
                                ->suffix('%'),
                            Select::make('hero_image_radius')
                                ->label('Köşe yuvarlama')
                                ->options([
                                    'none' => 'none [varsayılan]',
                                    'sm'   => 'sm',
                                    'md'   => 'md',
                                    'lg'   => 'lg',
                                    'xl'   => 'xl',
                                    'full' => 'full',
                                ])
                                ->native(false),
                        ]),
                        Grid::make(2)->schema([
                            Toggle::make('hero_image_shadow')
                                ->label('Gölge ekle'),
                            Toggle::make('hero_image_show_mobile')
                                ->label('Mobilde göster'),
                        ]),
                    ]),

                // --- 8. About Image ---
                Section::make('Hakkımızda Resmi')
                    ->schema([
                        FileUpload::make('homepage_about_image')
                            ->label('Hakkımızda resmi')
                            ->disk('public')
                            ->directory('homepage')
                            ->image()
                            ->imagePreviewHeight('160')
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Ana sayfadaki "Hakkımızda" bölümünde gösterilir.'),
                    ]),

                // --- 9. Live Preview ---
                Section::make('Canlı Önizleme')
                    ->description('Kaydettikten sonra güncellenir. Canlı alanlar (rozet, başlık, alt başlık) yazı yazarken güncellenir.')
                    ->schema([
                        Placeholder::make('hero_preview')
                            ->label('')
                            ->content(function () {
                                $d = $this->data ?? [];
                                $locale = app()->getLocale();

                                $badge    = $d['hero_badge_'    . $locale]    ?? ($d['hero_badge_tr']    ?? '');
                                $title    = $d['hero_title_'    . $locale]    ?? ($d['hero_title_tr']    ?? '');
                                $subtitle = $d['hero_subtitle_' . $locale]    ?? ($d['hero_subtitle_tr'] ?? '');
                                $btn1     = $d['hero_primary_button_text_'   . $locale] ?? ($d['hero_primary_button_text_tr']   ?? '');
                                $btn2     = $d['hero_secondary_button_text_' . $locale] ?? ($d['hero_secondary_button_text_en']  ?? '');

                                $titleColor  = e($d['hero_title_color']        ?? '#f5f0e8');
                                $accentColor = e($d['hero_title_accent_color'] ?? '#8b5a2b');
                                $bgColor     = ($d['hero_background_type'] ?? 'gradient') === 'color'
                                    ? e($d['hero_background_color'] ?? '#3e2006')
                                    : '#3e2006';
                                $fw          = e($d['hero_title_font_weight']     ?? '700');
                                $tt          = e($d['hero_title_text_transform']  ?? 'uppercase');
                                $lh          = e($d['hero_title_line_height']     ?? '1.08');
                                $ls          = e($d['hero_title_letter_spacing']  ?? '-0.02em');

                                $statsVisible = ($d['hero_stats_visible'] ?? '1') === '1';
                                $btn1Visible  = ($d['hero_primary_button_visible']   ?? '1') === '1';
                                $btn2Visible  = ($d['hero_secondary_button_visible'] ?? '1') === '1';

                                $btn1Color = e($d['hero_primary_button_color'] ?? '#1e3a5f');
                                $btn2Color = ($d['hero_secondary_button_color'] ?? '') !== ''
                                    ? 'background:' . e($d['hero_secondary_button_color']) . ';color:#fff;'
                                    : 'background:transparent;border:2px solid rgba(245,240,232,0.3);color:#f5f0e8;';

                                $statsHtml = '';
                                if ($statsVisible) {
                                    $statsHtml = '<div style="display:flex;gap:1px;margin-top:1.5rem;background:rgba(245,240,232,0.15);border-radius:4px;overflow:hidden;">';
                                    for ($i = 1; $i <= 5; $i++) {
                                        $val = e($d["hero_stat_{$i}_value"] ?? '');
                                        $lbl = e($d["hero_stat_{$i}_label_" . $locale] ?? ($d["hero_stat_{$i}_label_tr"] ?? ''));
                                        if ($val !== '') {
                                            $statsHtml .= '<div style="flex:1;padding:10px 6px;text-align:center;background:rgba(62,32,6,0.8);">'
                                                        . '<div style="font-weight:700;font-size:1.1rem;color:#d4a96a;">' . $val . '</div>'
                                                        . '<div style="font-size:0.6rem;color:rgba(245,240,232,0.7);margin-top:2px;">' . $lbl . '</div>'
                                                        . '</div>';
                                        }
                                    }
                                    $statsHtml .= '</div>';
                                }

                                $btn1Html = $btn1Visible
                                    ? '<a href="#" style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;background:' . $btn1Color . ';color:#fff;border-radius:4px;font-weight:600;font-size:0.85rem;text-decoration:none;">' . e($btn1 ?: 'Teklif Al') . '</a>'
                                    : '';
                                $btn2Html = $btn2Visible
                                    ? '<a href="#" style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;' . $btn2Color . 'border-radius:4px;font-weight:600;font-size:0.85rem;text-decoration:none;">' . e($btn2 ?: 'Urunleri Goster') . '</a>'
                                    : '';

                                $html = '
<div style="background:' . $bgColor . ';padding:2rem 1.5rem;border-radius:8px;font-family:sans-serif;position:relative;">
  <div style="max-width:480px;">
    <span style="display:inline-flex;align-items:center;gap:6px;border:1px solid rgba(212,169,106,0.5);background:rgba(62,32,6,0.4);padding:4px 10px;border-radius:3px;font-size:0.7rem;font-weight:600;letter-spacing:0.1em;color:#d4a96a;text-transform:uppercase;">'
    . e($badge ?: 'Kalite & Guven') . '</span>
    <h1 style="margin:12px 0 8px;font-weight:' . $fw . ';text-transform:' . $tt . ';line-height:' . $lh . ';letter-spacing:' . $ls . ';color:' . $titleColor . ';font-size:1.6rem;">'
    . e($title ?: 'Ana Sayfa Baslik Ornegi') . '</h1>
    <p style="color:rgba(245,240,232,0.8);font-size:0.85rem;line-height:1.6;margin:0 0 16px;">'
    . e($subtitle ?: 'Bu alana alt baslik metni gelir. Sol form alanlarina yazdiginizda burada guncellenir.') . '</p>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">' . $btn1Html . $btn2Html . '</div>'
    . $statsHtml . '
  </div>
  <div style="position:absolute;top:8px;right:12px;font-size:0.65rem;color:rgba(245,240,232,0.4);">ONIZLEME</div>
</div>';

                                return new HtmlString($html);
                            }),
                    ]),

            ])->statePath('data');
    }

    public function save(): void
    {
        $data      = $this->form->getState();
        $imageKeys = ['homepage_hero_image', 'homepage_about_image'];
        $boolKeys  = [
            'hero_primary_button_visible',
            'hero_secondary_button_visible',
            'hero_stats_visible',
            'hero_image_shadow',
            'hero_image_show_mobile',
        ];

        foreach ($data as $key => $value) {
            if ($value === null && in_array($key, $imageKeys, true)) {
                continue;
            }
            if (is_array($value)) {
                $value = reset($value) ?: '';
            }
            // Normalize toggle booleans
            if (in_array($key, $boolKeys, true)) {
                $value = ($value === true || $value === '1' || $value === 1) ? '1' : '0';
            }
            Setting::set(key: $key, value: (string) ($value ?? ''), group: 'homepage');
        }

        Notification::make()
            ->title('Ayarlar başarıyla kaydedildi')
            ->success()
            ->send();
    }
}
