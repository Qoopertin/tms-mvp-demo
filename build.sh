#!/bin/bash

# TMS MVP - Railway Deployment Script
# This script prepares the application for Railway deployment

echo "🚀 Preparing TMS MVP for Railway deployment..."

# Build frontend assets for production
echo "📦 Building frontend assets..."
npm install
npm run build

# Optimize for production
echo "⚡ Optimizing Laravel..."
if [ -f "artisan" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

echo "✅ Build complete! Ready for Railway deployment."
echo ""
echo "📋 Next steps:"
echo "1. Make sure .env is configured on Railway"
echo "2. Run migrations: php artisan migrate --force"
echo "3. Seed database: php artisan db:seed --force"
