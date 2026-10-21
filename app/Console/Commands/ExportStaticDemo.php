<?php

namespace App\Console\Commands;

use App\Http\Controllers\HomeController;
use App\Models\Menu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;

/**
 * Membuat versi demo statis beranda (HTML + CSS + JS) untuk GitHub Pages.
 * Menu diambil dari database saat perintah dijalankan; pesanan di demo langsung ke WhatsApp.
 */
class ExportStaticDemo extends Command
{
    protected $signature = 'demo:export
        {--path=docs : Folder tujuan, relatif ke root proyek}
        {--repo=https://github.com/baricomp4ny/bari-coffee : Link kode yang ditampilkan di footer demo}';

    protected $description = 'Ekspor beranda Bari Coffee menjadi situs statis untuk GitHub Pages';

    public function handle(): int
    {
        $dir = base_path($this->option('path'));

        // Semua link & aset dibuat relatif ("./css/bari.css") agar jalan di subfolder GitHub Pages
        URL::forceRootUrl('.');
        request()->setLaravelSession(app('session')->driver());
        View::share(['errors' => new ViewErrorBag, 'staticDemo' => true, 'demoRepoUrl' => $this->option('repo')]);

        $html = app(HomeController::class)->index()->render();
        // asset() tetap menghasilkan "/css/...", ubah jadi relatif untuk subfolder
        $html = preg_replace('~(href|src)="/(css|js|images|storage)/~', '$1="./$2/', $html);

        File::ensureDirectoryExists($dir);
        File::cleanDirectory($dir);
        File::put("{$dir}/index.html", $html);
        File::put("{$dir}/.nojekyll", '');
        File::copy(public_path('favicon.ico'), "{$dir}/favicon.ico");
        foreach (['css/bari.css', 'js/bari.js'] as $asset) {
            File::ensureDirectoryExists(dirname("{$dir}/{$asset}"));
            File::copy(public_path($asset), "{$dir}/{$asset}");
        }

        // Foto bawaan (public/images) dan foto upload admin (storage) ikut disalin
        if (File::isDirectory(public_path('images'))) {
            File::copyDirectory(public_path('images'), "{$dir}/images");
        }
        $photos = 0;
        foreach (Menu::whereNotNull('photo')->where('photo', 'not like', 'images/%')->pluck('photo') as $photo) {
            $source = storage_path("app/public/{$photo}");
            if (File::exists($source)) {
                File::ensureDirectoryExists(dirname("{$dir}/storage/{$photo}"));
                File::copy($source, "{$dir}/storage/{$photo}");
                $photos++;
            }
        }

        $this->info("Demo statis dibuat di {$this->option('path')}/ (".Menu::count()." menu, {$photos} foto upload admin).");
        $this->line('Buka index.html di browser untuk mencoba, lalu commit & push folder ini.');

        return self::SUCCESS;
    }
}
