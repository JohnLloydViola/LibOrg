Download this project as a ZIP and extract it into the htdocs folder.
Rename the folder to LibOrg.
Follow the steps below.


## Steps to run app:

1. **Run Apache and MySQL on xampp**

2. **Create the database and the books table by running this on the browser:**
   ```text
   localhost/LibOrg/config/setup.php
   ```


3. **Navigate to laravel-backend folder and in that folder there is ".env.example" file. remove the ".example" the file name should now be ".env"**

4. **Go to that .env file and under the DB_CONNECTION theres database credentials. Remove the hastags(#) to apply the credentials for this project**

5. **Create the members and transactions table through Laravel by running these on the terminal:**
   ```bash
   cd {htdocs location}/LibOrg/laravel-backend
   composer install
   php artisan migrate
   ```

6. **Create the database seeds by running this on the same terminal:**
   ```bash
   php artisan db:seed
   ```

7. **Run the Laravel server on the same terminal using this command:**
   ```bash
   php artisan serve
   ```

8. **Open the app on the browser:**
   ```text
   localhost/LibOrg/index.php
   ```

---

## Important Notes:

**Default admin:**
* **email:** `admin@liborg.com`
* **password:** `admin12345`
