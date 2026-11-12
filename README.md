## Steps to run app:

1. **Run Apache and MySQL on xampp**

2. **Create the database and the books table by running this on the browser:**
   ```text
   localhost/LibOrg/config/setup.php
   ```

3. **Create the members and transactions table through Laravel by running these on the terminal:**
   ```bash
   cd {htdocs location}/LibOrg/laravel-backend
   php artisan migrate
   ```

4. **Create the database seeds by running this on the same terminal:**
   ```bash
   php artisan db:seed
   ```

5. **Run the Laravel server on the same terminal using this command:**
   ```bash
   php artisan serve
   ```

6. **Open the app on the browser:**
   ```text
   localhost/LibOrg/index.php
   ```

---

## Important Notes:

**Default admin:**
* **email:** `admin@liborg.com`
* **password:** `admin12345`
