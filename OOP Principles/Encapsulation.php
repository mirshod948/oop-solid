<?php
/**
 * Encapsulation - klassdagi muhim xususiyatlarni tashqaridan turib o'zgartirishni oldini olish.
 * Encapsulation konsepti orqali private metodlar va xususiyatlarga faqat public getter/setter metodlar orqali murojaat qilishni ta'minlaymiz.
 * Misol: Parolni xavfsiz saqlash va yangilash.
 */

class User
{
    // private xususiyat - tashqaridan o'zgartirilishi mumkin emas
    private string $password;

    // Konstruktor orqali qiymatlarni belgilash (agar kerak bo'lsa)
    public function __construct(private string $username)
    {
    }

// Setter metod - Parolni yangilash va xavfsiz saqlash
public function updatePassword(string $password): void
{
    // Parolni xavfsiz saqlash uchun password_hash() funksiyasini ishlatamiz
    $this->password = password_hash($password, PASSWORD_BCRYPT);
}

// Getter metod - Parolni olish (faqat tasdiqlash uchun)
public function getPassword(): string
{
    return 'Your password is safely stored.';
}

// Username olish
public function getUsername(): string
{
    return $this->username;
}

// Parolni tekshirish
public function verifyPassword(string $password): bool
{
    return password_verify($password, $this->password);
}
}

// User obyektini yaratamiz
$obj = new User('john_doe');

// Parolni yangilaymiz (setter yordamida)
$obj->updatePassword('12345678');

// Parolni olish (getter yordamida)
echo $obj->getPassword(); // Parolni ochiq ko'rsatish xavfsiz emas

// Parolni tekshiramiz (verifyPassword yordamida)
if ($obj->verifyPassword('12345678')) {
    echo "\nPassword is correct!";
} else {
    echo "\nIncorrect password!";
}
