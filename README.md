<h1 align="center"><strong>Construction Project Management System</strong></h1>
<p align="center"><i>Sistem Informasi Manajemen Proyek Konstruksi</i></p>
<p align="center"><strong>Author:</strong> Faris Iftikhar Alfarisi</p>

<p align="center">
<a href="https://www.instagram.com/frs.alfrs_/"><img src="https://img.shields.io/badge/Instagram-Profile-orange?logo=instagram" alt="Instagram"></a>
<a href="mailto:faris.workingspace@gmail.com"><img src="https://img.shields.io/badge/Email-Contact-blue?logo=gmail" alt="Email"></a>
</p>


SIMAPRO is an integrated system designed to manage the entire lifecycle of construction projects, from planning to completion. It is specifically tailored to meet the needs of construction companies in monitoring project progress, managing resources, and optimizing procurement processes. **This system was developed as part of the Supply Chain Management course project** for the Informatics Engineering study program at Universitas Komputer Indonesia.

---

## Key Features
- **Inventory Management**
  Real-time tracking and control of raw materials, equipment, and workshop products.
- **Supplier Selection & Purchasing**
  Selecting the best suppliers using the Weighted Product method, with integrated Purchase Requisition (PR) and Purchase Order (PO) creation.
- **Project Progression**
  Monitoring work schedules based on the Work Breakdown Structure (WBS), including predecessor settings, duration, and resource requirements.
- **Labour Management**  
  Managing workforce allocation based on project schedules and requirements, and estimating daily wages or worker pay based on attendance.
- **Project Financial Management**
  Recording and tracking project expenses to ensure budget efficiency.
- **Workshop Production Scheduling**
  Scheduling the production of custom items (Aftercraft) according to project plans and material availability.

With SIMAPRO, the entire construction project process becomes more structured, transparent, and efficient, reducing the risks of delays and unnecessary costs.

---

### How To Clone Repository
```bash
git clone https://github.com/FarisIftikharAlfarisi/pm-app.git
```

Navigate to Project Folder
```bash
cd pm-app
```
Install Dependencies
```bash
composer install
npm install
```
Copy .env & Configure
```bash
cp .env.example .env
```
Edit .env file to set your database, mail, and other configurations.

Generate Application Key
```bash
php artisan key:generate
```

Run Database Migration & Seeder
```bash
php artisan migrate --seed
```
Start the Application
```bash
php artisan serve
```
