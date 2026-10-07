1. อัปเดต .env.example เพื่อนจะได้ copy ไปใช้ได้เลย (ไม่ใส่รหัสผ่านจริง ค่านี้เป็นแค่ค่า dev ของ lab):

env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=sail
DB_PASSWORD=password

APP_PORT=8000
FORWARD_DB_PORT=3307
WWWUSER=1000
WWWGROUP=1000

2. ตรวจว่าจะ push อะไรบ้าง

powershell
git status

ต้องเห็น: database/migrations/*, docker-compose.yml, .env.example, app/Models/User.php (ที่แก้ $fillable)
ต้องไม่เห็น: .env, vendor/, node_modules/ (ปกติถูกกันไว้ใน .gitignore แล้ว ถ้าโผล่มาให้หยุดก่อนแล้วเช็ก .gitignore)

3. commit และ push

powershell
git add database/migrations docker-compose.yml .env.example app/Models
git commit -m "Add database migrations for shop tables"
git push
ฝั่งเพื่อน: ทำทุกคน (ครั้งแรกครั้งเดียว)
powershell
git pull
cp .env.example .env

แล้วติดตั้ง vendor/ ผ่าน Docker (เพราะไม่ถูก push มาและเครื่องไม่มี PHP):

powershell
docker run --rm -v "${PWD}:/var/www/html" -w /var/www/html laravelsail/php85-composer:latest composer install --ignore-platform-reqs
docker compose up -d
docker compose exec -u sail laravel.test php artisan key:generate
docker compose exec -u sail laravel.test php artisan migrate

(ถ้าใช้ Mac/Linux ให้ใส่ WWWUSER/WWWGROUP ใน .env ตามผล id -u / id -g) จากนั้นเปิด DBeaver ต่อ localhost:3307, user sail, password password ก็จะได้ฐานข้อมูลหน้าตาเหมือนในรูป

ถ้าเพื่อนเคยสร้าง DB ของตัวเองไว้แล้ว (เช่นเคยรัน migration ชุดเก่า) ให้ docker compose down -v แล้ว up -d และ migrate ใหม่ เพื่อไม่ให้ตารางไม่ตรงกัน

ข้อควรรู้ตอนทำงานร่วมกัน
เรื่อง	กติกา
ข้อมูลไม่ถูกแชร์	git ส่งแค่โค้ด ข้อมูลในตารางของแต่ละคนอยู่ใน Docker volume ของเครื่องตัวเอง ถ้าต้องการข้อมูลตัวอย่างเหมือนกันต้องทำเป็น Seeder แล้วทุกคนรัน php artisan db:seed
หลัง pull ทุกครั้ง	รัน php artisan migrate ถ้ามี migration ใหม่จากคนอื่น
ห้ามแก้ migration ที่ push แล้ว	ถ้าต้องเปลี่ยนโครงสร้าง ให้สร้าง migration ใหม่ (make:migration) เพราะเครื่องเพื่อนรันของเก่าไปแล้ว มันจะไม่รันซ้ำ แก้แล้วก็ไม่เปลี่ยนใน DB ของเขา
.env เป็นของแต่ละเครื่อง	พอร์ตชนกันก็แก้เฉพาะในเครื่องตัวเอง (FORWARD_DB_PORT, APP_PORT) ไม่ต้อง push
แยก branch ต่อ feature	git checkout -b feature/cart แล้วเปิด Pull Request เข้า main จะได้ไม่ชนกัน
ไฟล์ migration ใหม่ของหลายคน	ถ้าสองคนสร้าง migration พร้อมกัน ชื่อจะมี timestamp ต่างกัน รันได้ปกติ แต่ถ้าคนหนึ่งอ้างตารางที่อีกคนยังไม่ได้ push จะ error ให้คุย schema กันก่อน