<?php
/**
 * Proxy pattern:
 * Ushbu patternning vazifasi biror klass obyektiga to'g'ridan-to'g'ri emas balki vositachi/yordamchi
 * klass orqali murojat qilishdir. Ushbu vostiachi klass proxy deb nomlanadi. Proxydan foydalanish oraqli
 * obyektga murojaatlarni boshqarishimiz mumkun bo'ladi. Masalan ko'p resurs talab qiladigan jarayonlarni
 * keshlash, audit qilish yoki foydalanuchi huquq/rollarini tekshrish uchun proxy klassdan foydalanish mumkun.
 * Ushbu pattern uchta asosiy qismdan iborat bo'lib ular: real klass uchun interfeys, real klass va proxy klass.
 * Bunda proxy klass haqiqiy real klassning kerakli metodlarini Liskov prinsipi asosida override qiladi.
 * Poxy klass real klass uchun vositachi bo'lgani sababli ikkalasi bir xil interfeysga ega bo'lishi kerak.
 * Shunda biz real klassga murojaatlarni proxy klassda nazorat qilish orqali asosiy klassda "single responsibility"
 * prinsipini saqlab qolamiz. Ushbu patternni quyidagi sodda misolda ko'rib chiqamiz
 */


// Asosiy klass interfeysini yaratib olamiz
interface ProductServiceInterface
{
    public function calculateProductPrice(int $productId);
}

// Haqiqiy klassni yaratib olamiz va asosiy interfeysdagi metodlarni elon qilamiz
class ProductService implements ProductServiceInterface
{
    public function calculateProductPrice(int $productId)
    {
        // Bazadan maxsulot narxlarini topib, qaytaramiz.
        // Bu yerda maxsulot hisob kitob jarayoniga oid amallarni bajarish mumkun.
        return Product::getPriceFromDB($productId);
    }
}


// Proxy klassni yaratib olamiz
// Proxy klass ham asosiy klass bilan bir xil interfeysda bo'lishi kerak
class ProductServiceProxy implements ProductServiceInterface
{
    // Proxy klass o'zida real klass obyektini saqlovchi xususiyatga ega bo'lishi kerak.
    private ProductService $productService;

    // Konstruktor orqali real klass obyektini qabul qilib uni proxy klassga yuklab olamiz
    public function __construct(ProductService $realProductService)
    {
        $this->productService = $realProductService;
    }

    // LSP prinspi asosida override qilingan metod.
    // Ushbu metod orqali asosiy klass metodiga murojaat qilinadi.
    public function calculateProductPrice(int $productId)
    {
        // Bu yerda asosiy klassga murojaatlarni nazorat qilish mumkun:
        // Masalan: Foydalanuvchi huquq va rollarini tekshirish mumkun

        If(User::role() !== 'Admin'){

            // Yoki murojaatlarni logga yozib borish mumkun.
            Log::info('Calculate product price', [
                'product_id' => $productId,
                'user' => User::getId()
            ]);
        }

        // Ko'p resurs talab qiladigan murojaatlarni keshlash mumkun

        $cacheKey = 'product_price_' . $productId;

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $price = $this->realProductService->calculateProductPrice($productId);

        Cache::add($cacheKey, $price, 3600);

        return $price;
    }
}

// Proxy klassni yaratib olamiz.
$productService = new ProductServiceProxy(new ProductService());

// Endi proxy klass oraqali asosiy klassdan foydalanishimiz mumkun.
// Barcha murojaatlarimiz proxy klassda nazorat qilinadi.
$product = $productService->calculateProductPrice(123);
