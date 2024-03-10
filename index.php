<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KarakaslarGroup Construction Management</title>
    <link rel="stylesheet" href="css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

        <!-- header section design -->
    <header class="header">
        <a href="#" class="logo">
            <img src="img/logo.png" alt="KarakaslarGroup Logo">
        </a>

        <div class="bx bx-menu" id="menu-icon"><span class="animate" style="--i:2;"></span></div>

        <nav class="navbar">
            <a href="#home" class="active">Home</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>

            <span class="active-nav"></span>
        </nav>
    </header>

        <!-- home section design -->
    <section class="home show-animate" id="home">
        <div class="slideshow-container">
            <div class="slideshow">
                <img src="img/bamboourla.jpg" alt="Bamboo Urla">
                <img src="img/Mandarin.jpg" alt="Mandarin Garden Kuşadası">
                <img src="img/KirlangicAyvalik.jpg" alt="Kırlangıç Ayvalık">
                <img src="img/teosvita.jpg" alt="Teosvita Seferihisar">
                <img src="img/bamboourla.jpg" alt="Bamboo Urla">
                <img src="img/Mandarin.jpg" alt="Mandarin Garden Kuşadası">
                <img src="img/KirlangicAyvalik.jpg" alt="Kırlangıç Ayvalık">
                <img src="img/teosvita.jpg" alt="Teosvita Seferihisar">
            </div>
        </div>
        <div class="home-content">
            <div class="home-text">
                <h2><span class="highlight">KarakaslarGroup</span> Yonetim Sitesi</h2>
            </div>
        </div>
    </section>

        <!-- about section design -->
    <section class="about" id="about">
        <h2 class="heading">Bizim <span>Hakkımızda</span></h2>

        <div class="about-img">
            <img src="img/logo.png" alt="">
            <span class="circle-spin"></span>
        </div>

        <div class="about-content">
            <h3>Güvenilir çözümler, değerli hayatlar!</h3>
            <p>Karakaşlar Group, 1986 yılında kurulmuş ve kurulduğu günden bu yana birçok önemli proje tamamlamıştır. Şirket, alışveriş merkezleri, villa ve konut projeleri gibi birçok alanda faaliyet göstermektedir. Karakaşlar Grup, kalite, güvenilirlik ve müşteri memnuniyetine odaklanarak inşaat sektöründe güçlü bir itibar kazanmıştır.
                Yenilikçilik ve kararlılıkla hedeflerinden hiç sapmadan projelerini hayata geçiren Karakaşlar Yapı, şimdiye kadar Söke (3), Muğla, Kütahya, Susurluk, Salihli, Uşak ve Nazilli, Urla ve Ayvalık olmak üzere toplam 11 alışveriş merkezi kurdu.
                2017 yılı rakamlarına göre yaklaşık 200 bin metrekare kiralanabilir alışveriş merkezi inşaat projesi geliştiren Karakaşlar Yapı, 300’ün üzerinde kira sözleşmesinin sonucuna varmıştır.
                Faaliyette olan AVM’lerde her yıl 50 milyon üzerinde ziyaretçiyi ağırlayarak, doğru lokasyon seçimiyle alışveriş mağazalarındaki iş ortaklarını oldukça memnun etmiştir. Ayrıca Karakaşlar Yapı, kurmuş olduğu AVM’lerin kiralama ve yönetimini de aktif olarak üstlenmektedir.
                Karakaşlar Group olarak kurduğumuz alışveriş merkezleri ve nitelikli yaşam projeleriyle hep daha çok kitleyi hedefliyoruz. Herkes için erişilebilir olanı bölgeye uyarlıyor; gerçekleştirdiğimiz bütün mekânları yatırımcıların kazancına ve nihai tüketicisinin keyfine dönüştürüyoruz.
            </p>
        </div>
    </section>

        <!-- contact section design -->
    <section class="contact" id="contact">
        <h2 class="heading">Bize <span>Ulaşın!</span></h2>

        <form action="mailto:ekremarmagankarakas@gmail.com" method="post" enctype="text/plain">
            <div class="input-box">
                <div class="input-field">
                    <input type="text" name="name" placeholder="İsim" required>
                    <span class="focus"></span>
                </div>
                <div class="input-field">
                    <input type="text" name="email" placeholder="Email" required>
                    <span class="focus"></span>
                </div>
            </div>

            <div class="input-box">
                <div class="input-field">
                    <input type="number" name="mobile" placeholder="Telefon Numarası" required>
                    <span class="focus"></span>
                </div>
                <div class="input-field">
                    <input type="text" name="subject" placeholder="Email Konusu" required>
                    <span class="focus"></span>
                </div>
            </div>

            <div class="textarea-field">
                <textarea name="message" id="message" cols="30" rows="10" placeholder="Mesajınız" required></textarea>
                <span class="focus"></span>
            </div>

            <div class="btn-box btns">
                <button type="submit" class="btn">Gönder</button>
            </div>
        </form>
    </section>

         <!-- footer section design -->
    <footer>
        <p>&copy; 2024 KarakaslarGroup Construction Management. All rights reserved.</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
