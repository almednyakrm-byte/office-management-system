# نظام إدارة المكاتب والموظفين (الدفاتر، الموافقات، الموارد)
==========================

## Overview & Project Purpose
---------------------------

نظام إدارة المكاتب والموظفين هو تطبيق إلكتروني مُصمم لتعزيز كفاءة و效قاية في إدارة المكاتب والموظفين في المنظمات. يُتيح هذا النظام إدارة دفاتر، الموافقات، والموارد بشكل مركزي ومُحسّن. يهدف هذا المشروع إلى توفير نظام إدارة شامل ومُتكامِل لتعزيز إدارة المكاتب والموظفين بشكل أفضل.

## Project Structure Mapping
---------------------------

### Backend

* `app`: الملفات الرئيسية للبرنامج
* `config`: إعدادات البرنامج
* `database`: قاعدة البيانات
* `models`: نموذج البيانات
* `routes`: مسارات البرنامج
* `services`: خدمات البرنامج

### Frontend

* `public`: الملفات العامة للبرنامج
* `src`: الملفات الأساسية للبرنامج
* `components`: مكونات البرنامج
* `containers`: مكونات البرنامج
* `actions`: إجراءات البرنامج
* `reducers`: خوارزميات البرنامج

### Docker

* `docker-compose.yml`: ملف إعدادات docker-compose
* `Dockerfile`: ملف إعدادات docker

## Step-by-Step Instructions for Running the Environment
---------------------------------------------------------

### 1. تثبيت الحاجيات

bash
# تثبيت الحاجيات
sudo apt-get update
sudo apt-get install -y docker.io
sudo systemctl start docker
sudo systemctl enable docker


### 2. تشغيل docker-compose

bash
# تشغيل docker-compose
docker-compose up -d


### 3. تشغيل البرنامج

bash
# تشغيل البرنامج
docker-compose exec backend npm start


### 4. تشغيل الخادم

bash
# تشغيل الخادم
docker-compose exec backend node app.js


## Modules, Tables, and Roles
---------------------------

### Modules

* `دفاتر`: إدارة دفاتر المكاتب
* `موافقات`: إدارة الموافقات
* `موارد`: إدارة الموارد

### Tables

* `دفاتر`: جدول دفاتر المكاتب
* `موافقات`: جدول الموافقات
* `موارد`: جدول الموارد
* `موظفين`: جدول الموظفين

### Roles

* `مدير`: دور المدير
* `موظف`: دور الموظف
* `مستخدم`: دور المستخدم

## Contact Developer Details
---------------------------

* **اسم المطور**: [اسم المطور]
* **بريد الإلكتروني**: [بريد الإلكتروني]
* **رقم الهاتف**: [رقم الهاتف]
* **موقع الويب**: [موقع الويب]

---

## 📧 للتواصل (Contact)
almednyakrm@gmail.com
