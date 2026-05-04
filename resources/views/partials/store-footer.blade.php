<footer class="pt-5 pb-4 mt-5" style="font-family: sans-serif; background-color: #013780; border-top: 3px solid #FFD700;">
    <div class="container-fluid px-lg-5">
        <div class="row justify-content-between g-4">
            
            {{-- 1. Brand & Slogan --}}
            <div class="col-lg-4 col-md-6">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    {{-- Logo difilter putih agar kontras dengan background biru --}}
                    <img src="{{ asset('assets/img/logo-polman.png') }}" alt="Logo" style="height: 35px; width: auto; margin-right: 12px; filter: brightness(0) invert(1);">
                    <span style="font-weight: 800; color: #ffffff; letter-spacing: 1px; font-size: 1.1rem; text-transform: uppercase;">MARKETPLACE POLMAN</span>
                </div>
                <p style="color: rgba(255, 255, 255, 0.8); font-size: 14px; line-height: 1.8; margin: 0; text-align: justify; max-width: 350px;">
                    Solusi manufaktur, pengecoran, dan elektronik industri. Platform resmi produk inovasi mahasiswa dan layanan teknis Politeknik Manufaktur Bandung.
                </p>
                <div style="display: flex; gap: 15px; margin-top: 20px;">
                    <a href="#" style="color: rgba(255, 255, 255, 0.8); font-size: 18px; transition: 0.3s;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'"><i class="bi bi-instagram"></i></a>
                    <a href="#" style="color: rgba(255, 255, 255, 0.8); font-size: 18px; transition: 0.3s;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'"><i class="bi bi-facebook"></i></a>
                    <a href="#" style="color: rgba(255, 255, 255, 0.8); font-size: 18px; transition: 0.3s;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

            {{-- 2. Katalog Cepat --}}
            <div class="col-lg-2 col-md-6">
                <h6 style="font-weight: 700; font-size: 13px; color: #FFD700; text-transform: uppercase; margin-bottom: 25px; margin-top: 0;">KATALOG</h6>
                <div style="display: block; padding: 0; margin: 0;">
                    <a href="{{ route('products.index') }}" style="display: block; color: rgba(255, 255, 255, 0.8); font-size: 14px; text-decoration: none; margin-bottom: 10px; transition: 0.3s;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">Semua Produk</a>
                    @foreach($topCategories as $cat)
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" style="display: block; color: rgba(255, 255, 255, 0.8); font-size: 14px; text-decoration: none; margin-bottom: 10px; transition: 0.3s;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">{{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>

            {{-- 3. Hubungi Kami --}}
            <div class="col-lg-4 col-md-12">
                <h6 style="font-weight: 700; font-size: 13px; color: #FFD700; text-transform: uppercase; margin-bottom: 25px; margin-top: 0;">HUBUNGI KAMI</h6>
                <div style="display: block; padding: 0; margin: 0;">
                    {{-- Alamat --}}
                    <div style="display: flex; align-items: flex-start; margin-bottom: 15px;">
                        <div style="width: 25px; color: #FFD700;"><i class="bi bi-geo-alt"></i></div>
                        <div style="color: rgba(255, 255, 255, 0.8); font-size: 14px; line-height: 1.5; flex: 1;">Jl. Kanayakan No.21, Dago, Kecamatan Coblong, Kota Bandung, Jawa Barat 40135</div>
                    </div>
                    {{-- Email --}}
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div style="width: 25px; color: #FFD700;"><i class="bi bi-envelope"></i></div>
                        <div style="color: rgba(255, 255, 255, 0.8); font-size: 14px;">marketplace@polman-bandung.ac.id</div>
                    </div>
                    {{-- WA --}}
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div style="width: 25px; color: #FFD700;"><i class="bi bi-whatsapp"></i></div>
                        <div style="color: rgba(255, 255, 255, 0.8); font-size: 14px;">+62 812-xxxx-xxxx</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Bottom Copyright --}}
        <div style="border-top: 1px solid rgba(255, 255, 255, 0.1); margin-top: 40px; padding-top: 20px;">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p style="color: rgba(255, 255, 255, 0.5); font-size: 13px; margin: 0;">© 2026 Politeknik Manufaktur Bandung. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</footer>