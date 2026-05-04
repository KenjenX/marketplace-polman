<footer class="pt-5 pb-4 mt-5" style="font-family: sans-serif; background-color: #013780; border-top: 3px solid #FFD700;">
    <div class="container-fluid px-lg-5">
        <div class="row justify-content-between g-4">
            
            {{-- 1. Brand & Slogan --}}
            <div class="col-lg-4 col-md-6">
                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                    <img src="{{ asset('assets/img/logo-polman.png') }}" alt="Logo" style="height: 35px; width: auto; margin-right: 12px; filter: brightness(0) invert(1);">
                    <span style="font-weight: 800; color: #ffffff; letter-spacing: 1px; font-size: 1.1rem; text-transform: uppercase;">MARKETPLACE POLMAN</span>
                </div>
                <p style="color: rgba(255, 255, 255, 0.8); font-size: 14px; line-height: 1.8; margin: 0; text-align: justify; max-width: 350px;">
                    Solusi manufaktur, pengecoran, dan elektronik industri. Platform resmi produk inovasi mahasiswa dan layanan teknis Politeknik Manufaktur Bandung.
                </p>
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

            {{-- 3. Informasi --}}
            <div class="col-lg-2 col-md-6">
                <h6 style="font-weight: 700; font-size: 13px; color: #FFD700; text-transform: uppercase; margin-bottom: 25px; margin-top: 0;">INFORMASI</h6>
                <div style="display: block; padding: 0; margin: 0;">
                    <a href="{{ url('/about') }}" style="display: block; color: rgba(255, 255, 255, 0.8); font-size: 14px; text-decoration: none; margin-bottom: 10px; transition: 0.3s;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">About Us</a>
                    <a href="{{ url('/contact') }}" style="display: block; color: rgba(255, 255, 255, 0.8); font-size: 14px; text-decoration: none; margin-bottom: 10px; transition: 0.3s;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">Contact Us</a>
                </div>
            </div>

            {{-- 4. Media Sosial Resmi (Custom Ikon PNG) --}}
            <div class="col-lg-3 col-md-6">
                <h6 style="font-weight: 700; font-size: 13px; color: #FFD700; text-transform: uppercase; margin-bottom: 25px; margin-top: 0;">MEDIA SOSIAL</h6>
                <div style="display: block; padding: 0; margin: 0;">
                    {{-- Instagram --}}
                    <a href="https://www.instagram.com/polmanbandung" target="_blank" style="display: flex; align-items: center; color: rgba(255, 255, 255, 0.8); font-size: 14px; text-decoration: none; margin-bottom: 15px; transition: 0.3s;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">
                        <img src="{{ asset('assets/img/social.png') }}" alt="IG" style="height: 18px; width: auto; margin-right: 12px;"> Instagram
                    </a>
                    {{-- YouTube --}}
                    <a href="https://www.youtube.com/@POLMAN.BANDUNG" target="_blank" style="display: flex; align-items: center; color: rgba(255, 255, 255, 0.8); font-size: 14px; text-decoration: none; margin-bottom: 15px; transition: 0.3s;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">
                        <img src="{{ asset('assets/img/youtube.png') }}" alt="YT" style="height: 18px; width: auto; margin-right: 12px;"> YouTube
                    </a>
                    {{-- LinkedIn --}}
                    <a href="https://www.linkedin.com/school/politeknik-manufaktur-bandung" target="_blank" style="display: flex; align-items: center; color: rgba(255, 255, 255, 0.8); font-size: 14px; text-decoration: none; margin-bottom: 15px; transition: 0.3s;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">
                        <img src="{{ asset('assets/img/linkedin.png') }}" alt="LI" style="height: 18px; width: auto; margin-right: 12px;"> LinkedIn
                    </a>
                    {{-- Twitter / X --}}
                    <a href="https://twitter.com/polmanbandung" target="_blank" style="display: flex; align-items: center; color: rgba(255, 255, 255, 0.8); font-size: 14px; text-decoration: none; margin-bottom: 15px; transition: 0.3s;" onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='rgba(255, 255, 255, 0.8)'">
                        <img src="{{ asset('assets/img/twitter.png') }}" alt="X" style="height: 18px; width: auto; margin-right: 12px;"> Twitter / X
                    </a>
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