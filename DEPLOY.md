# TMS MVP - Quick Deployment Guide

## 🚀 Get This Running in 5 Minutes

### Step 1: Initialize Git (Run in PowerShell)

```powershell
cd "c:\Users\danii\Desktop\TMS 2.0"
git init
git add .
git commit -m "Initial TMS MVP - Laravel 11"
```

### Step 2: Push to GitHub

```powershell
# Create a new repo on github.com first, then:
git branch -M main
git remote add origin https://github.com/YOUR-USERNAME/tms-mvp.git
git push -u origin main
```

### Step 3: Deploy to Railway (EASIEST)

1. Go to **railway.app** → Sign in with GitHub
2. Click **"New Project"** → **"Deploy from GitHub repo"**
3. Select your `tms-mvp` repository
4. Railway will auto-detect Laravel and start building

### Step 4: Add MySQL Database

1. In Railway project → Click **"New"** → **"Database"** → **"MySQL"**
2. Railway will auto-create `DATABASE_URL` environment variable

### Step 5: Configure Environment Variables

In Railway → Your service → **Variables** tab, add:

```
APP_NAME=TMS MVP
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://YOUR-APP.up.railway.app

# Database (Railway auto-fills these from MySQL service)
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}

SESSION_DRIVER=database
CACHE_STORE=database
FILESYSTEM_DISK=local
```

**Generate APP_KEY**: Run this locally or in Railway shell:
```bash
php artisan key:generate --show
```

### Step 6: Seed Database

Once deployed, open Railway **Shell** (in deployment view) and run:

```bash
php artisan db:seed
```

### Step 7: Access Your App! 🎉

Railway will give you a URL like: `https://tms-mvp-production-xxxx.up.railway.app`

**Login with:**
- Admin: `admin@example.com` / `password`
- Dispatcher: `dispatcher@example.com` / `password`  
- Driver: `driver@example.com` / `password`

---

## Alternative: Render.com

1. **Create Web Service** on render.com
2. Connect GitHub repo
3. **Build Command**: 
   ```
   composer install --no-dev && npm install && npm run build
   ```
4. **Start Command**: 
   ```
   php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
   ```
5. Add PostgreSQL or MySQL database
6. Set environment variables (same as above)
7. Deploy → Run `php artisan db:seed` in Render shell

---

## Troubleshooting

**Build fails?**
- Check PHP version is 8.3 in platform settings
- Ensure `composer.lock` is committed

**500 Error?**
- Check `APP_KEY` is set
- Verify database connection
- Run `php artisan migrate --force` in shell

**Assets not loading?**
- Make sure `npm run build` ran successfully
- Check `APP_URL` matches your actual URL

---

## What to Test

Once live:

✅ Login works  
✅ Create a new load  
✅ Assign driver to load  
✅ Upload a document (PDF/image)  
✅ View dispatch map (OpenStreetMap loads)  
✅ Login as driver → see active load  
✅ Click "Start Tracking" (allow location)  
✅ View driver location on dispatch map  

**Share the live URL when it's deployed so I can verify it works!**
