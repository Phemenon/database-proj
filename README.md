# Fantasy Shop – คู่มือสำหรับคน pull ไปทำต่อ

เว็บ e-commerce ขายไอเทมแฟนตาซี (Laravel + MySQL รันด้วย Docker แบบ Laravel Sail)
Extra features ที่เลือก: **Rating, Wish List, Comments/Reviews**

---

## 1. สิ่งที่ต้องมีในเครื่อง

- **Docker Desktop** (ต้องเปิดค้างไว้และขึ้นสถานะ *Engine running* ก่อนรันทุกคำสั่ง)
- **Git**, **VS Code**, **DBeaver**
- ไม่ต้องลง PHP / Composer / Node ในเครื่อง ทุกอย่างรันใน Docker

> ถ้าโปรเจกต์อยู่ในโฟลเดอร์ OneDrive ให้ย้ายไปที่อื่น เช่น `C:\dev\` ก่อน เพราะ `vendor/` มีไฟล์หลายหมื่นไฟล์ OneDrive จะซิงก์ช้าและบางครั้งทำไฟล์พัง

---

## 2. ตั้งค่าครั้งแรก (ทำครั้งเดียว)

เปิด terminal ที่รากโปรเจกต์ (โฟลเดอร์ที่มี `artisan`) แล้วรันทีละบรรทัด (ตัวอย่างเป็น PowerShell)

```powershell
git pull
cp .env.example .env
```

เปิด `.env` ตรวจว่ามีค่าเหล่านี้ (ถ้าพอร์ตชนกับโปรแกรมอื่นให้แก้เฉพาะในเครื่องตัวเอง ไม่ต้อง push)

```env
APP_PORT=8000
FORWARD_DB_PORT=3307

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=sail
DB_PASSWORD=password

WWWUSER=1000
WWWGROUP=1000
```

- `DB_USERNAME` **ห้ามเป็น `root`** (MySQL จะไม่สตาร์ต)
- **ถ้าใช้ WSL / Mac / Linux ต้องตั้ง `WWWUSER` / `WWWGROUP` ให้ตรงกับผลของ `id -u` / `id -g` ของเครื่องตัวเอง** (ไม่ใช่ทุกเครื่องเป็น 1000 เช่นบาง WSL เป็น 1001) ถ้าไม่ตรงจะเจอ `Permission denied` ตอนเขียนไฟล์ แก้ `.env` แล้วรัน `docker compose up -d --build` ใหม่
- ถ้าใช้ Windows PowerShell ปกติ (ไม่ผ่าน WSL) ใช้ 1000 ได้เลย

จากนั้น

```powershell
# 1) ติดตั้งแพ็กเกจ PHP (สร้างโฟลเดอร์ vendor/) ผ่าน container ชั่วคราว
#    ใช้ php84-composer (php85-composer ไม่มีใน Docker Hub) ใช้แค่ติดตั้งแพ็กเกจ ไม่เกี่ยวกับเวอร์ชัน PHP ที่รันจริง
docker run --rm -v "${PWD}:/var/www/html" -w /var/www/html laravelsail/php84-composer:latest composer install --ignore-platform-reqs
#    ถ้า pull image ไม่ได้ ใช้ตัวนี้แทน:
#    docker run --rm -v "${PWD}:/app" -w /app composer:2 composer install --ignore-platform-reqs

# 2) เปิดระบบ (ครั้งแรกใช้เวลาหลายนาที)
docker compose up -d
docker compose ps          # รอจน mysql ขึ้น healthy

# 3) ตั้งค่า Laravel และสร้างตาราง
docker compose exec -u sail laravel.test php artisan key:generate
docker compose exec -u sail laravel.test php artisan migrate

# 4) ติดตั้งและ build ส่วน frontend (ถ้าหน้าเว็บฟ้อง Vite manifest)
docker compose exec -u sail laravel.test npm install
docker compose exec -u sail laravel.test npm run build
```

เปิดเบราว์เซอร์ไปที่ **http://localhost:8000**

> `docker-compose.yml` อ้างถึงไฟล์ใน `vendor/laravel/sail/` จึงต้องมีแพ็กเกจ `laravel/sail` ใน `composer.json` (`require-dev`) และรัน `composer install` ให้เสร็จก่อน `docker compose up` เสมอ ถ้าหลัง `composer install` แล้วยังไม่มีโฟลเดอร์ `vendor/laravel/sail` ให้ดูหัวข้อแก้ปัญหาด้านล่าง

---

## 3. เชื่อม DBeaver

DBeaver อยู่นอก Docker จึงใช้ค่าคนละชุดกับใน `.env`

| ช่อง | ค่า |
|---|---|
| Host | `localhost` |
| Port | `3307` (= `FORWARD_DB_PORT`) |
| Database | `ecommerce` |
| Username / Password | `sail` / `password` |

ถ้าขึ้น *Public Key Retrieval is not allowed* ไปที่ Driver properties ตั้ง `allowPublicKeyRetrieval=true` และ `useSSL=false`

> **จำง่าย ๆ:** Laravel (ใน container) ใช้ `mysql:3306` · DBeaver (บน Windows) ใช้ `localhost:3307`

---

## 4. ขั้นตอนทำงานประจำวัน

```powershell
git pull                                                   # ดึงงานล่าสุด
docker compose up -d                                       # เปิดระบบ
docker compose exec -u sail laravel.test php artisan migrate   # ถ้ามี migration ใหม่จากคนอื่น
```

ทุกคำสั่ง Laravel ต้องขึ้นต้นด้วย `docker compose exec -u sail laravel.test` เช่น

```powershell
docker compose exec -u sail laravel.test php artisan make:controller ProductController
docker compose exec -u sail laravel.test php artisan route:list
docker compose exec -u sail laravel.test php artisan tinker
```

ทางลัด (ใช้ได้เฉพาะ terminal ที่เปิดอยู่ตอนนั้น):

```powershell
function sail { docker compose exec -u sail laravel.test @args }
sail php artisan migrate
```

ปิดระบบ: `docker compose stop` (ข้อมูลไม่หาย) · ล้างทุกอย่างรวมข้อมูล DB: `docker compose down -v`

---

## 5. โครงสร้างฐานข้อมูล (9 ตาราง)

| ตาราง | คีย์หลัก | หมายเหตุ |
|---|---|---|
| `users` | `id` | ลูกค้า (มี `phone`, `address`) ใช้ระบบ login ของ Laravel |
| `categories` | `id` | หมวดหมู่ เช่น Weapons, Armor, Potions |
| `products` | `id` | มี `rarity` (common → legendary), `stock`, FK `category_id` |
| `carts` | `id` | 1 user : 1 cart (`user_id` UNIQUE) |
| `cart_items` | `(cart_id, product_id)` | ตารางเชื่อม มี `quantity` |
| `orders` | `id` | `status`: pending / paid / shipped / completed |
| `order_items` | `(order_id, item_no)` | **Weak entity** ของ orders เก็บ `unit_price` ณ ตอนซื้อ |
| `wishlists` | `(user_id, product_id)` | ตารางเชื่อม |
| `reviews` | `(user_id, product_id)` | `rating` 1-5 + `comment` (คนละ 1 รีวิวต่อสินค้า) |

ตารางของ Laravel เอง (`migrations`, `sessions`, `cache`, `jobs`, `password_reset_tokens` ฯลฯ) ไม่ต้องยุ่ง

**ความสัมพันธ์หลัก:** category 1:N product · user 1:1 cart · user 1:N order · order 1:N order_items N:1 product · cart M:N product (cart_items) · user M:N product (wishlists, reviews)

---

## 6. วิธีใช้ Model (สำคัญ: มี composite PK)

Model อยู่ใน `app/Models/` ความสัมพันธ์ที่ใช้ได้เลย: `$product->category`, `$product->reviews`, `$order->items`, `$user->orders`, `$user->wishlist` ฯลฯ

**Cart / Wishlist** ใช้เป็น pivot ไม่มี Model แยก

```php
$cart->products()->attach($productId, ['quantity' => 2]);   // ใส่ของลงตะกร้า
$cart->products()->updateExistingPivot($productId, ['quantity' => 3]);
$cart->products()->detach($productId);

$user->wishlist()->syncWithoutDetaching([$productId]);      // เพิ่ม wishlist
$user->wishlist()->detach($productId);
```

**Review** ไม่มี `id` ห้ามใช้ `$review->save()` แก้ของเดิม ให้ใช้

```php
Review::updateOrCreate(
    ['user_id' => $uid, 'product_id' => $pid],
    ['rating' => 5, 'comment' => '...']
);
$product->averageRating();   // คะแนนเฉลี่ย
```

**OrderItem** ต้องสร้างผ่าน order และกำหนด `item_no` เอง (1, 2, 3...)

```php
foreach ($cart->products as $i => $p) {
    $order->items()->create([
        'item_no'    => $i + 1,
        'product_id' => $p->id,
        'quantity'   => $p->pivot->quantity,
        'unit_price' => $p->price,      // เก็บราคา ณ ตอนซื้อ
    ]);
}
```

---

## 7. กติกาของระบบที่ต้องทำในโค้ด

- **Checkout ต้องอยู่ใน `DB::transaction()`:** สร้าง order → สร้าง order_items → ตัด `stock` → คำนวณ `total` → ล้าง cart ถ้าขั้นไหนพัง ให้ rollback ทั้งหมด
- **ทุก order ต้องมีอย่างน้อย 1 รายการ** (DB บังคับให้ไม่ได้ ต้องเช็กในโค้ดก่อนสร้าง)
- **เช็ก stock** ก่อนใส่ตะกร้า/ก่อนสั่งซื้อ และห้ามให้ `quantity` ≤ 0
- **rating ต้อง 1-5** (DB มี CHECK กันไว้อีกชั้น) validate ใน controller ด้วย
- เฉพาะสมาชิกที่ login แล้วเท่านั้นที่สั่งซื้อ / รีวิว / wishlist ได้ ใช้ middleware `auth`
- รหัสผ่านถูก hash อัตโนมัติจาก Model `User` ห้าม hash ซ้ำเอง

---

## 8. กติกาการทำงานร่วมกัน (Git)

1. **แยก branch ต่อ feature** เช่น `feature/cart`, `feature/reviews` แล้วเปิด Pull Request เข้า `main` ห้าม push ตรงเข้า `main`
2. **ห้าม commit `.env`** (push แค่ `.env.example`) ห้าม commit `vendor/` และ `node_modules/` ส่วน **`composer.lock` และ `package-lock.json` ให้ commit** เพื่อให้ทุกคนได้แพ็กเกจเวอร์ชันเดียวกัน (ถ้ามีคนเพิ่ม/อัปเดตแพ็กเกจ ให้ commit ไฟล์ lock พร้อม `composer.json` / `package.json` เสมอ)
3. **ห้ามแก้ไฟล์ migration ที่ push ไปแล้ว** เพราะเครื่องคนอื่นรันไปแล้วจะไม่รันซ้ำ ถ้าต้องเปลี่ยนโครงสร้างให้สร้างไฟล์ใหม่
   ```powershell
   docker compose exec -u sail laravel.test php artisan make:migration add_xxx_to_products_table --table=products
   ```
4. **ต้องการเพิ่ม/แก้ตารางหรือคอลัมน์ → แจ้งคนดูแล database ก่อน** เพื่อให้ ER, relational mapping และ Model ตรงกันทั้งหมด
5. หลัง `git pull` ทุกครั้ง รัน `php artisan migrate` ถ้ามี migration ใหม่
6. **ข้อมูลในตารางไม่ถูกแชร์ผ่าน Git** (อยู่ใน Docker volume ของแต่ละเครื่อง) ข้อมูลตัวอย่างต้องมาจาก Seeder: `php artisan db:seed`
7. `migrate:fresh` จะ **ลบทุกตารางแล้วสร้างใหม่** ข้อมูลที่ใส่เองหายหมด ใช้ตอนพัฒนาเท่านั้น

---

## 9. แก้ปัญหาที่เจอบ่อย

| อาการ | วิธีแก้ |
|---|---|
| `failed to connect to the docker API ... dockerDesktopLinuxEngine` | เปิด Docker Desktop แล้วรอจนขึ้น *Engine running* (ถ้าไม่ขึ้นให้ restart Docker Desktop / `wsl --update`) |
| Laravel ขึ้น `Connection refused` ที่ `127.0.0.1` | `DB_HOST` ใน `.env` ต้องเป็น `mysql` ไม่ใช่ `127.0.0.1` แล้วรัน `php artisan config:clear` |
| `getaddrinfo for mysql failed` | container mysql ไม่ทำงาน ดู `docker compose ps -a` และ `docker compose logs mysql` |
| `Access denied for user 'sail'` | MySQL จำรหัสผ่านเก่าใน volume รัน `docker compose down -v` แล้ว `docker compose up -d` ใหม่ |
| `laravel.log ... Permission denied` | `docker compose exec laravel.test chmod -R 777 storage bootstrap/cache` |
| หน้าเว็บฟ้อง `Vite manifest not found` | รัน `npm install` และ `npm run build` ผ่าน container (ดูข้อ 2) |
| พอร์ตชน (`port is already allocated`) | เปลี่ยน `APP_PORT` หรือ `FORWARD_DB_PORT` ใน `.env` ของเครื่องตัวเอง แล้ว `docker compose up -d` |
| แก้ `.env` แล้วไม่เปลี่ยน | `php artisan config:clear` และ `docker compose up -d` ใหม่ |
| ต่อ DBeaver ไม่ได้ | ใช้ `localhost:3307` ไม่ใช่ `mysql:3306` |
| `repository does not exist` ตอน pull `laravelsail/php85-composer` | ใช้ `laravelsail/php84-composer:latest` หรือ `composer:2` พร้อม `--ignore-platform-reqs` (ดูข้อ 2) |
| `docker compose up` ฟ้องหา `vendor/laravel/sail/...` ไม่เจอ | `composer.json` ไม่มี `laravel/sail` หรือยังไม่ได้ `composer install` รัน `docker run --rm -v "${PWD}:/var/www/html" -w /var/www/html laravelsail/php84-composer:latest composer require laravel/sail --dev --ignore-platform-reqs` แล้ว commit `composer.json` + `composer.lock` |
| `Permission denied` เขียนไฟล์ใน `storage/` หรือสร้างไฟล์ไม่ได้ (WSL/Linux/Mac) | `id -u` / `id -g` ของเครื่องไม่ใช่ 1000 แก้ `WWWUSER` / `WWWGROUP` ใน `.env` ให้ตรง แล้ว `docker compose up -d --build` |
