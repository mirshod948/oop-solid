<?php
/**
 * Abstraksiya (Abstraction) - faqat kerakli ma'lumotni ko'rsatish va keraksiz tafsilotlarni yashirish.
 * Bu misolda abstrakt metodni ishlatish orqali, uning ichidagi logika foydalanuvchidan yashirilgan.
 * PHP 8.2'da yangi imkoniyatlar, masalan, readonly properties, o'zgaruvchilarni yanada optimallashtirishga yordam beradi.
 */

// Asosiy klassni yaratib olamiz
class Triangle
{
    // private xususiyatlar - faqat sinf ichidan foydalanish mumkin
    private readonly int $a;
    private readonly int $b;
    private readonly int $c;

    // Konstruktorni yozamiz
    public function __construct(int $aVal, int $bVal, int $cVal)
    {
        $this->a = $aVal;
        $this->b = $bVal;
        $this->c = $cVal;
    }

    // Abstrakt metod yaratib olamiz (PHP 8.2 da metodlar faqat kerakli miqdordagi ma'lumotni ko'rsatishi kerak)
    public function calcArea(): float
    {
        // Yuzani hisoblash logikasi - bu biz uchun mavhum
        $a = $this->a;
        $b = $this->b;
        $c = $this->c;

        $p = ($a + $b + $c) / 2;

        // Yuzani hisoblash
        return sqrt($p * ($p - $a) * ($p - $b) * ($p - $c));
    }
}

$triangle = new Triangle(3, 4, 5);

// $triangle obyektida calcArea() nomli metod borligi bizga ma'lum.
// Va ushbu metod berilgan uchburchak yuzasini hisoblab berishi ham ma'lum.
// Ammo bu metod ichida qanday jarayon borishi biz uchun mavhumdir.
echo "Area = " . $triangle->calcArea();
