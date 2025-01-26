## Ideasoft Case
Bu proje, Laravel framework'ü kullanılarak geliştirilmiş bir sipariş yönetim sistemidir. Proje, müşteri, ürün ve sipariş yönetimi gibi temel işlemleri gerçekleştirir. Ayrıca, siparişlere otomatik indirimler uygulanır ve stok yönetimi sağlanır.

### 📦 Kullanılan Teknolojiler
- PHP: 8.2
- Laravel: 11.31
- MySQL: Veritabanı olarak kullanıldı.
- Docker: Geliştirme ve dağıtım ortamı için kullanıldı.
- Composer: Bağımlılık yönetimi için kullanıldı.

### 🛠️ Proje Yapısı
Proje, Repository Pattern ve Service Layer kullanılarak modüler bir şekilde geliştirilmiştir. Bu sayede kodun daha okunabilir, sürdürülebilir ve test edilebilir olması sağlanmıştır.

### 🧩 Kullanılan Desenler
#### 1. Repository Pattern:
- Veritabanı işlemleri için repository sınıfları kullanıldı.
- OrderRepositoryInterface, ProductRepositoryInterface ve CustomerRepositoryInterface gibi interface'ler üzerinden soyutlama sağlandı.

#### 2. Service Layer:
- İş mantığı (business logic) service sınıflarında tutuldu.
- OrderService, ProductService, CustomerService ve DiscountService gibi sınıflar, temel işlemleri yönetir.

#### 3. Dependency Injection:
- Controller'lar, service sınıflarını constructor üzerinden enjekte eder.
- Bu sayede bağımlılıklar daha kolay yönetilir ve test edilebilirlik artar.

#### 4. Exception Handling:
- Özel exception sınıfları (OrderNotFoundException, ProductNotFoundException, CustomerNotFoundException, InsufficientStockException) kullanılarak hata yönetimi sağlandı.

### 🚀 Kurulum
Projeyi yerel ortamınızda çalıştırmak için aşağıdaki adımları takip edin.

### 1. Docker ile Kurulum
Proje, Docker üzerinde çalışacak şekilde yapılandırılmıştır. Kurulum için:

1. #### Docker Container'larını Başlatın:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`docker-compose up -d`

2. #### Composer Bağımlılıklarını Yükleyin:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`docker-compose exec app composer install`

3. #### Veritabanı Migrasyonlarını Çalıştırın:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`docker-compose exec app php artisan migrate`

4. #### Uygulamayı Çalıştırın:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`docker-compose exec app php artisan serve`

### 🌐 API Endpoint'leri
Proje, aşağıdaki API endpoint'lerini sunar:

### Müşteri İşlemleri
* #### Tüm Müşterileri Listeleme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`GET /api/customers`

* #### Belirli Bir Müşteriyi Görüntüleme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`GET /api/customers/{id}`

* #### Yeni Müşteri Ekleme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`POST /api/customers`

* #### Müşteri Bilgilerini Güncelleme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`PUT /api/customers/{id}`

* #### Müşteri Silme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`DELETE /api/customers/{id}`

### Ürün İşlemleri
* #### Tüm Ürünleri Listeleme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`GET /api/products`

* #### Belirli Bir Ürünü Görüntüleme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`GET /api/products/{id}`

* #### Yeni Ürün Ekleme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`POST /api/products`

* #### Ürün Bilgilerini Güncelleme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`PUT /api/products/{id}`

* #### Ürün Silme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`DELETE /api/products/{id}`

### Sipariş İşlemleri
* #### Tüm Siparişleri Listeleme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`GET /api/orders`

* #### Belirli Bir Siparişi Görüntüleme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`GET /api/orders/{id}`

* #### Yeni Sipariş Ekleme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`POST /api/orders`

* #### Sipariş Bilgilerini Güncelleme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`PUT /api/orders/{id}`

* #### Sipariş Silme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`DELETE /api/orders/{id}`

### İndirim İşlemleri
* #### Siparişe İndirim Uygulama:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`GET /api/discounts/{orderId}`


### 🛠️ Geliştirme
Projeyi geliştirirken aşağıdaki komutları kullanabilirsiniz:

* #### Cache Temizleme:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`docker-compose exec app php artisan cache:clear`

* #### Config Cache:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`docker-compose exec app php artisan config:cache`

* #### Route Cache:
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`docker-compose exec app php artisan route:cache`

### 🚀 Postman Collection

API'leri test etmek için Postman Collection'ımızı kullanabilirsiniz:

[Postman Collection Linki](IDEASOFT-APP.postman_collection)
