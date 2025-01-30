# 🏢 Asistan Yönetim Sistemi

## 📖 Proje Açıklaması
Asistan Yönetim Sistemi, asistanların müşteri görüşmelerini yönetmesine ve aylık prim bilgilerini takip etmesine olanak sağlayan bir web tabanlı uygulamadır. Bu sistem, asistanların görüşmelerini kaydetmesini, prim bilgilerini görüntülemesini ve prim itirazlarını yapmasını sağlayan bir arayüze sahiptir.

## 🚀 Özellikler
- **Asistan Girişi**: Asistanların sisteme giriş yaparak verilerine erişmesi sağlanır.
- **Görüşme Yönetimi**: Asistanların müşteri görüşmelerini kaydetmesini ve yönetmesini sağlar.
- **Aylık Prim Takibi**: Asistanların prim bilgilerini görüntüleyebilmesini sağlar.
- **Prim İtiraz Sistemi**: Asistanlar, yalnızca güncel aya ait primler için itirazda bulunabilir.
- **Takım Hiyerarşisi**:
  - **Asistanlar**, **Takım Liderleri**'ne bağlıdır.
  - **Takım Liderleri**, **Grup Yöneticileri**'ne bağlıdır.
  - **Takım Liderleri**, asistanların işlerini görüntüleyebilir ve yönetebilir.
  - **Grup Yöneticileri**, takım liderlerinin işlemlerini takip edebilir.
  - **Takım Lideri bir değişiklik yaptığında**, grup yöneticisine otomatik olarak **e-posta bildirimi** gönderilir.
- **Güvenli Oturum Yönetimi**: Kullanıcı oturum kontrolü ile yetkisiz erişimlerin önüne geçilir.

## 🛠️ Kullanılan Teknolojiler
- **Backend**: PHP (SQLSRV kütüphanesi ile MSSQL bağlantısı)
- **Database**: Microsoft SQL Server
- **Frontend**: HTML, CSS, JavaScript
- **Sunucu Tarafı**: SQL Server ile güvenli veri yönetimi

## 📦 Kurulum ve Kullanım
### 🔧 Gereksinimler
- PHP 7+ yüklü bir sunucu (Apache, Nginx vb.)
- Microsoft SQL Server
- Tarayıcı (Google Chrome, Firefox, Edge vb.)

### ⬇️ Kurulum
1. **Projeyi klonlayın:**
   ```sh
   git clone https://github.com/kullaniciAdi/asistan-yonetim-sistemi.git
   cd asistan-yonetim-sistemi
   ```
2. **Veritabanı bağlantısını yapılandırın:**
   - `db_config.php` dosyasını açın.
   - MSSQL bağlantı bilgilerinizi (`serverName`, `database`, `username`, `password`) girin.
3. **Veritabanı tablolarını oluşturun:**
   - `Gorusmeler`, `Primler`, `Asistanlar`, `Kullanicilar` tablolarını oluşturun.
4. **Web sunucusunu başlatın:**
   - XAMPP/WAMP kullanıyorsanız Apache ve SQL Server'ı açın.
   - Projeyi tarayıcıda çalıştırın (`http://localhost/asistan-yonetim-sistemi`).

## 🔒 Güvenlik Önlemleri
- Kullanıcı giriş çıkışları **oturum yönetimi** ile kontrol edilmektedir.
- SQL enjeksiyonuna karşı **parametreli sorgular** kullanılmaktadır.
- Yetkisiz erişimlerin önüne geçmek için **oturum doğrulaması** yapılmaktadır.

## 📌 Kullanım Senaryosu
1. **Asistan giriş yapar.**
2. **Görüşme kaydeder ve yönetir.**
3. **Aylık prim listesini görüntüler.**
4. **Son aya ait prim için gerekirse itirazda bulunur.**
5. **Takım liderleri asistanların işlemlerini yönetir.**
6. **Grup yöneticileri takım liderlerinin işlemlerini takip eder.**
7. **Takım lideri bir değişiklik yaptığında, grup yöneticisine e-posta bildirimi gider.**

## 🏗️ Geliştirme Aşamaları
- [x] Kullanıcı oturum yönetimi
- [x] Müşteri görüşme yönetimi
- [x] Prim bilgisi gösterimi
- [x] Prim itiraz sistemi
- [x] Takım lideri ve grup yöneticisi yetkilendirme
- [ ] Yönetici paneli (Geliştirme aşamasında)

## 🤝 Katkıda Bulunma
Eğer projeye katkı sağlamak isterseniz, **pull request** gönderebilir veya bir **issue** oluşturabilirsiniz.

## 📄 Lisans
Bu proje **MIT Lisansı** ile lisanslanmıştır.

## 📧 İletişim
Herhangi bir sorunuz veya öneriniz varsa benimle iletişime geçebilirsiniz:
- **E-posta**: simaygnlu@gmail.com
- **LinkedIn**: www.linkedin.com/in/simay-ayanoğlu-0b02a8255

---
**💡 Not:** Bu proje, asistanların iş süreçlerini daha verimli yönetmelerine yardımcı olmak için geliştirilmiştir. Kullanım sırasında herhangi bir hata ile karşılaşırsanız, lütfen bir hata bildirimi oluşturun! 🚀

